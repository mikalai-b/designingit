<?php

use Doctrine\ORM\QueryBuilder;

/**
 *
 */
class PersonCriteria extends AbstractCriteria
{
    /**
     *
     */
    protected $email = NULL;


    /**
     *
     */
    protected $firstName = NULL;


    /**
     *
     */
    protected $hasRoles = NULL;


    /**
     *
     */
    protected $keyword = NULL;


    /**
     *
     */
    protected $lastName = NULL;


    /**
     *
     */
    protected $name = NULL;


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
    public function orderBy($key, $direction = 'ASC')
    {
        $this->orderKey = $key;
        $this->orderDir = strtoupper($direction);
    }


    /**
     *
     */
    public function setEmail($email)
    {
        if ($email) {
            $this->email = $email;
        }

        return $this;
    }


    /**
     *
     */
    public function setFirstName($first_name)
    {
        if ($first_name) {
            $this->firstName = $first_name;
        }

        return $this;
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
    public function setLastName($last_name)
    {
        if ($last_name) {
            $this->lastName = $last_name;
        }

        return $this;
    }


    /**
     *
     */
    public function setName($name)
    {
        if ($name) {
            $this->name = $name;
        }

        return $this;
    }


    /**
     *
     */
    public function setHasRoles($has_roles)
    {
        $this->hasRoles = $has_roles;

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
    protected function apply(QueryBuilder $builder)
    {
        if ($this->firstName) {
            $builder
                ->andWhere('this.firstName = :firstName')
                ->setParameter('firstName', trim($this->firstName))
            ;
        }

        if ($this->lastName) {
            $builder
                ->andWhere('this.lastName = :lastName')
                ->setParameter('lastName', trim($this->lastName))
            ;
        }

        if ($this->name) {
            $name      = '%' . str_replace(' ', '%', trim($this->name)) . '%';
            $like_name = $builder->expr()->like(
                $builder->expr()->concat('this.firstName',
                    $builder->expr()->concat(
                        $builder->expr()->literal(' '),
                        'this.lastName'
                    )
                ),
                ':name'
            );

            $builder
                ->andWHERE('this.firstName LIKE :name')
                ->orWhere('this.lastName LIKE :name')
                ->orWhere($like_name)
                ->setParameter('name', $name)
            ;
        }

        if ($this->email) {
            $builder
                ->andWhere('this.email LIKE :email')
                ->setParameter('email', '%' . trim($this->email) . '%')
            ;
        }

        if ($this->keyword) {
            $keyword      = '%' . str_replace(' ', '%', trim($this->keyword)) . '%';
            $like_keyword = $builder->expr()->like(
                $builder->expr()->concat('this.firstName',
                    $builder->expr()->concat(
                        $builder->expr()->literal(' '),
                        'this.lastName'
                    )
                ),
                ':keyword'
            );

            $builder
                ->andWHERE('this.firstName LIKE :keyword')
                ->orWhere('this.lastName LIKE :keyword')
                ->orWhere('this.email LIKE :keyword')
                ->orWhere($like_keyword)
                ->setParameter('keyword', $keyword)
            ;
        }

        if ($this->provider) {
            $builder
                ->join('this.orders', 'o')
                ->andWhere('o.provider = :provider')
                ->andWhere('o.dateCreated = (SELECT max(o2.dateCreated) FROM Order o2 WHERE o2.person = this.id)')
                ->setParameter('provider', $this->provider)
            ;
        }

        if ($this->hasRoles !== NULL) {
            $builder->leftJoin('this.account', 'account');
            $builder->leftJoin('account.roles', 'role');

            if ($this->hasRoles) {
                $builder->andWhere('role.id IS NOT NULL');
            } else {
                $builder->andWhere('role.id IS NULL');
            }
        }

        if ($this->orderKey) {
            switch($this->orderKey) {
                case 'name':
                    $builder->orderBy('this.firstName', $this->orderDir);
                    $builder->addOrderBy('this.lastName', $this->orderDir);
                    break;

                case 'email':
                    $builder->orderBy('this.email', $this->orderDir);
                    break;

                case 'consultDate':
                    $builder->orderBy('o.dateCreated', $this->orderDir);
                    break;
            }
        }
    }
}
