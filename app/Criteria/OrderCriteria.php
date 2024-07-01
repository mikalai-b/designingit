<?php

use Doctrine\ORM\QueryBuilder;

/**
 *
 */
class OrderCriteria extends AbstractCriteria implements Countable
{
    /**
     *
     */
    protected $keyword = NULL;

    /**
     *
     */
    protected $provider = NULL;

    /**
     *
     */
    protected $orderKey = NULL;


    /**
     *
     */
    protected $orderDir = NULL;


    /**
     *
     */
    protected $states = array();


    /**
     *
     */
    public function count()
    {
        return $this->keyword || $this->provider || $this->states;
    }


    /**
     *
     */
    public function hasState($state)
    {
        return in_array($state, $this->states);
    }


    /**
     *
     */
    public function orderBy($key, $direction = 'ASC')
    {
        $this->orderKey = $key;
        $this->orderDir = strtoupper($direction);
    }


    /**
     *
     */
    public function setKeyword($keyword)
    {
        if ($keyword) {
            $this->keyword = $keyword;
        }

        return $this;
    }


    /**
     *
     */
    public function addStatus($status)
    {
        if ($status) {
            $this->states[] = $status;
        }

        return $this;
    }


    /**
     *
     */
    public function setProvider($provider)
    {
        if ($provider) {
            $this->provider = $provider;
        }

        return $this;
    }


    /**
     *
     */
    public function setStates(array $states) {
        foreach ($states as $status) {
            $this->addStatus($status);
        }
    }

    /**
     *
     */
    protected function apply(QueryBuilder $builder)
    {
        $builder->join('this.person', 'p');

        if ($this->keyword) {
            $keyword      = '%' . str_replace(' ', '%', trim($this->keyword)) . '%';
            $like_keyword = $builder->expr()->like(
                $builder->expr()->concat('p.firstName',
                    $builder->expr()->concat(
                        $builder->expr()->literal(' '),
                        'p.lastName'
                    )
                ),
                ':keyword'
            );

            $builder
                ->andWhere('p.firstName LIKE :keyword')
                ->orWhere('p.lastName LIKE :keyword')
                ->orWhere('p.email LIKE :keyword')
                ->orWhere($like_keyword)
                ->setParameter('keyword', $keyword)
            ;
        }

        if ($this->provider) {
            $builder
                ->andWhere('this.provider = :provider')
                ->setParameter('provider', $this->provider)
            ;
        }

        if ($this->orderKey) {
            switch($this->orderKey) {
                case 'name':
                    $builder->orderBy('p.firstName', $this->orderDir);
                    $builder->addOrderBy('p.lastName', $this->orderDir);
                    break;

                case 'email':
                    $builder->orderBy('p.email', $this->orderDir);
                    break;

                case 'consultDate':
                    $builder->orderBy('this.dateCreated', $this->orderDir);
                    break;
            }
        }

        if ($this->states) {
            $expressions = array();
            $builder->leftJoin('this.consultation', 'c');
            $builder->leftJoin('c.prescriptions', 'x');

            if (in_array('incomplete', $this->states)) {
                $expressions[] = $builder->expr()->orX(
                    $builder->expr()->isNull('c.id'),
                    $builder->expr()->in('c.status', [Consultation::STATUS_NEW])
                );
                $expressions[] = $builder->expr()->isNull('c.id');
            }

            if (in_array('open', $this->states)) {
                $expressions[] = $builder->expr()->in('c.status', [
                    Consultation::STATUS_OPEN,
                    Consultation::STATUS_PENDING
                ]);
            }

            if (in_array('prescribed', $this->states)) {
                $expressions[] = $builder->expr()->andX(
                    $builder->expr()->in('c.status', [Consultation::STATUS_COMPLETED]),
                    $builder->expr()->isNotNull('x.id')
                );
            }

            if (in_array('declined', $this->states)) {
                $expressions[] = $builder->expr()->andX(
                    $builder->expr()->in('c.status', [Consultation::STATUS_COMPLETED]),
                    $builder->expr()->isNull('x.id')
                );
            }

            $builder->andWhere($builder->expr()->orX(...$expressions));
        }
    }
}
