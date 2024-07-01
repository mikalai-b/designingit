<?php

use Doctrine\Common\EventArgs;

class ModifiableListener
{
    /**
     *
     */
    public function preUpdate(Entity $entity, EventArgs $args)
    {
        $entity->setDateModified(new \DateTime());
    }
}
