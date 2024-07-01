<?php

/**
 *
 */
class Entity
{
    /**
     *
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function __clone() {
        $this->id = null;
    }
    
}
