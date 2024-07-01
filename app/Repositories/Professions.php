<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class Professions extends Repository
{
    static protected $entity = 'Profession';

    /**
     *
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }
}
