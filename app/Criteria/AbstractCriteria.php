<?php

use Doctrine\ORM\QueryBuilder;

/**
 *
 */
abstract class AbstractCriteria
{
    /**
     *
     */
    protected $limit = NULL;


    /**
     *
     */
    protected $page = NULL;


    /**
     *
     */
    protected $totalMatchCount = NULL;


    /**
     *
     */
    public function build(QueryBuilder $builder)
    {
        $this->apply($builder);

        if ($this->limit) {
            $builder->setMaxResults($this->limit);

            if ($this->page) {
                $builder->setFirstResult(($this->page - 1) * $this->limit);
            }
        }
    }


    /**
     *
     */
    public function getTotalMatchCount()
    {
        return $this->totalMatchCount;
    }


    /**
     *
     */
    public function getLimit()
    {
        return $this->limit;
    }


    /**
     *
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     *
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }


    /**
     *
     */
    public function setPage($page)
    {
        $this->page = $page;
    }


    /**
     *
     */
    public function setTotalMatchCount($count)
    {
        $this->totalMatchCount = $count;

        return $this;
    }


    /**
     *
     */
    abstract protected function apply(QueryBuilder $builder);
}
