<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class People extends Repository
{
    static protected $entity = 'Person';


    /**
     *
     */
    public function __construct(EntityManager $em, Providers $providers, Accounts $accounts, Roles $roles, Consultations $consultations, States $states)
    {
        $this->providers     = $providers;
        $this->consultations = $consultations;
        $this->accounts      = $accounts;
        $this->states        = $states;
        $this->roles         = $roles;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function findDefaultRecipients(Person $person)
    {
        return new Collection([$person->getOrders()->first()->getProvider()]);
    }

    /**
     *
     */
    public function findForReply(Message $parent_message, Person $sender)
    {
        $people = new Collection([]);

        if ($parent_message->getSender() !== $sender) {
            $people->add($parent_message->getSender());
        }

        foreach ($parent_message->getReceipts() as $receipt) {
            if ($people->contains($receipt->getRecipient())) {
                continue;
            }

            if ($receipt->getRecipient() === $sender) {
                continue;
            }

            $people->add($receipt->getRecipient());
        }

        if (!count($people)) {
            $people->add($sender);
        }

        return $people;
    }


    /**
     *
     */
    public function getAccounts()
    {
        return $this->accounts;
    }


    /**
     *
     */
    public function getConsultations()
    {
        return $this->consultations;
    }


    /**
     *
     */
    public function getGenders()
    {
        return [
            'F' => 'Female',
            'M' => 'Male',
            'U' => 'Other'
        ];
    }


    /**
     *
     */
    public function getProviders()
    {
        return $this->providers;
    }


    /**
     *
     */
    public function getRoles()
    {
        return $this->roles;
    }


    /**
     *
     */
    public function getStates()
    {
        return $this->states;
    }
}
