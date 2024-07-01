<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 */
class Collection extends ArrayCollection
{
    /**
     *
     */
    public function toBase()
    {
        return new Illuminate\Support\Collection($this->toArray());
    }

    /**
     *
     */
    public function mapInto($class)
    {
        return $this->toBase()->map(function($value, $key) use ($class) {
            return new $class($value, $key);
        });
    }
}
