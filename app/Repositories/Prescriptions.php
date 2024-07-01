<?php

use App\Exceptions\ValidationException;

/**
 *
 */
class Prescriptions extends Repository
{
    /**
     *
     */
    static protected $entity = 'Prescription';


    /**
     *
     */
    public function createFromLineItem(LineItem $line_item, $refills = NULL, $instructions = NULL, $expires = NULL, $consultationEntity = null)
    {
        $order        = $line_item->getOrder();
        $consultation = $order->getConsultation();
        $prescription = $this->create();

        if (!$consultation && $consultationEntity) {
            $consultation = $consultationEntity;
        }

        $prescription->setStatus(Prescription::STATUS_ACTIVE);
        $prescription->setLineItem($line_item);
        $prescription->setConsultation($consultation);
        $prescription->setInstructions($instructions);
        $prescription->setDateEnd(new \DateTime($expires));
        $prescription->setCreditCard($order->getCreditCard());
        $prescription->setRefills($refills);
        $prescription->setSentReorderNotification(0);

        return $prescription;
    }


    /**
     *
     */
    public function findForPerson(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.consultation', 'c')
                ->join('c.order', 'o')
                ->where('o.person = ?1')
                ->setParameter(1, $person)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findOpenForPerson(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.consultation', 'c')
                ->join('c.order', 'o')
                ->where('o.person = ?1')
                ->andWhere('this.refills > 0')
                ->andWhere('this.dateEnd > CURRENT_DATE()')
                ->setParameter(1, $person)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findExpiredForPerson(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->join('this.consultation', 'c')
                ->join('c.order', 'o')
                ->join('this.lineItem', 'l')
                ->where('o.person = ?1')
                ->andWhere('((this.refills > 0 AND this.filled = this.refills + 1) OR (this.dateEnd < CURRENT_DATE()))')
                ->setParameter(1, $person)
            ;
        })->getResult();
    }

    /**
     *
     */
    public function findReadyForReorderNotification()
    {
        $activeStates = (app()->make('States'))->getActiveStatesAsArray();
        return $this->query(function($query) use ($activeStates) {
            $query
                ->join('this.lineItem', 'l')
                ->join('l.order', 'o')
                ->where("((this.refills > 0 AND this.filled = this.refills + 1 AND DATE_ADD(this.dateLastRefilled, l.period, 'day') BETWEEN ?1 AND ?2) OR (DATE_ADD(this.dateEnd, l.period, 'day') BETWEEN ?1 AND ?2))")
                ->andWhere('this.sentReorderNotification = 0')
                ->andWhere('o.state IN (?3)')
                ->setParameter(1, new DateTime('yesterday'))
                ->setParameter(2, new DateTime('today'))
                ->setParameter(3, $activeStates)
            ;
        })->getResult();
    }

    /**
     *
     */
    public function findAllExpiredForReorderNotification()
    {
        $activeStates = (app()->make('States'))->getActiveStatesAsArray();
        return $this->query(function($query) use ($activeStates) {
            $query
                ->join('this.lineItem', 'l')
                ->join('l.order', 'o')
                ->where("((this.refills > 0 AND this.filled = this.refills + 1 AND DATE_ADD(this.dateLastRefilled, l.period, 'day') < ?1) OR (DATE_ADD(this.dateEnd, l.period, 'day') < ?1))")
                ->andWhere('this.status = ?2')
                ->andWhere('this.sentReorderNotification = 0')
                ->andWhere('o.state IN (?3)')
                ->setParameter(1, new DateTime('yesterday'))
                ->setParameter(2, Prescription::STATUS_ACTIVE)
                ->setParameter(3, $activeStates)
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findForResupply()
    {
        return $this->query(function($query) {
            $query
                ->join('this.lineItem', 'l')
                ->where("this.dateEnd >= ?1")
                ->andWhere('this.status = ?2')
                ->andWhere("l.period IS NOT NULL")
                ->andWhere("this.refills >= this.filled")
                ->andWhere("DATE_ADD(this.dateLastRefilled, l.period, 'day') <= ?1")
                ->setParameter(1, new DateTime('today'))
                ->setParameter(2, Prescription::STATUS_ACTIVE)
            ;
        })->getResult();
    }
}
