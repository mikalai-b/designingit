<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class Orders extends Repository
{
    /**
     *
     */
    static protected $entity = 'Order';


    /**
     *
     */
    static protected $order = ['dateCreated' => 'DESC'];


    /**
     *
     */
    public function __construct(EntityManager $em, LineItems $line_items)
    {
        $this->lineItems = $line_items;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function create()
    {
        $order = parent::create();

        $order->setStatus(constant(static::$entity . '::STATUS_NEW'));

        return $order;
    }


    /**
     *
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }


    /**
     *
     */
    public function findAllIncompleteForPerson(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->where('this.person = ?1')
                ->andWhere('this.status = ?2')
                ->setParameter(1, $person)
                ->setParameter(2, constant(static::$entity . '::STATUS_NEW'))
            ;
        })->getResult();
    }


    /**
     *
     */
    public function findAllNotClosedForPerson(Person $person)
    {
        return $this->query(function($query) use ($person) {
            $query
                ->where('this.person = ?1')
                ->andWhere('this.status != ?2')
                ->setParameter(1, $person)
                ->setParameter(2, constant(static::$entity . '::STATUS_CLOSED'))
            ;
        })->getResult();
    }


    /**
     * 
     */
    public function findIncompleteForPerson(Person $person, $orderId)
    {
        return $this->query(function($query) use ($person, $orderId) {
            $query
                ->where('this.person = ?1')
                ->andWhere('this.status = ?2')
                ->andWhere('this.id = ?3')
                ->setParameter(1, $person)
                ->setParameter(2, constant(static::$entity . '::STATUS_NEW'))
                ->setParameter(3, $orderId)
            ;
        })->getSingleResult();
    }
}
