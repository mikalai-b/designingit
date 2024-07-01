<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

/**
 *
 */
class Photos extends Repository
{
    static protected $entity = 'Photo';

    /**
     *
     */
    public function __construct(EntityManager $em, PhotoTypes $types)
    {
        $this->types = $types;

        parent::__construct($em, $em->getclassMetaData(static::$entity));
    }


    /**
     *
     */
    public function getTypes()
    {
        return $this->types;
    }
}
