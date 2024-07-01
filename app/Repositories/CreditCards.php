<?php

use Illuminate\Auth\AuthManager;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class CreditCards extends Repository
{
    static protected $entity = 'CreditCard';

    protected $auth = NULL;

    /**
     *
     */
    public function __construct(EntityManager $em, AuthManager $auth)
    {
        $this->auth = $auth;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }

    /**
     *
     */
     public function findIfAuthorized($id)
     {
         $card = $this->find($id);

         return $this->findForPerson($this->auth->user())->contains($card)
            ? $card
            : NULL;
     }


    /**
     *
     */
    public function findActiveForPerson(Person $person)
    {
        return $this->collect($this->query(function ($query) use ($person) {
            $query
                ->leftJoin('this.orders', 'orders')
                ->leftJoin('this.prescriptions', 'prescriptions')
                ->leftJoin('prescriptions.consultation', 'consultation')
                ->leftJoin('consultation.order', 'consultationOrder')
                ->where('prescriptions.refills > 0')
                ->where('orders.person = ?1')
                ->orWhere('consultationOrder.person = ?1')
                ->orderBy('this.id', 'DESC')
                ->setParameter(1, $person);
        }));

    }



    /**
     *
     */
    public function findForPerson(Person $person)
    {
        return $this->collect($this->query(function($query) use ($person) {
            $query
                ->leftJoin('this.orders', 'orders')
                ->leftJoin('this.prescriptions', 'prescriptions')
                ->leftJoin('prescriptions.consultation', 'consultation')
                ->leftJoin('consultation.order', 'consultationOrder')
                ->where('orders.person = ?1')
                ->orWhere('consultationOrder.person = ?1')
                ->orderBy('this.id', 'DESC')
                ->setParameter(1, $person)
            ;
        }));
    }
}
