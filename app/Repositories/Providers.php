<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class Providers extends Repository
{
    /**
     *
     */
    static protected $entity = 'Provider';


    /**
     *
     */
    protected $states = NULL;


    /**
     *
     */
    public function __construct(EntityManager $em, States $states)
    {
        $this->states = $states;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function findForPerson(Person $person)
    {
        //
        // TODO: Replace with strategy
        //

        if ($person->getPrescriptionState()) {
            return $this->findForState($person->getPrescriptionState());
        } else {
            return NULL;
        }
    }


    /**
     *
     */
    public function findForState(State $state)
    {
        return $this->collect($this->query(function($query) use ($state) {
            $query
                ->join('this.states', 'state', 'WITH', 'state = ?1')
                ->setParameter(1, $state)
                ->setMaxResults(1)
            ;
        }));
    }


    /**
     *
     */
    public function getStates()
    {
        return $this->states;
    }
}
