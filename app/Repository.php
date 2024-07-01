<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 *
 */
class Repository extends EntityRepository
{
	/**
	 *
	 */
	static protected $entity = NULL;


	/**
	 *
	 */
	static protected $collection = 'Collection';


	/**
	 *
	 */
	static protected $order = [];


	/**
	 *
	 */
	public function __construct(EntityManager $em)
	{
		parent::__construct($em, $em->getclassMetaData(static::$entity));
	}


	/**
	 *
	 */
	public function __invoke()
	{
		return $this->findAll();
	}


	/**
	 *
	 */
	public function collect(Query ...$queries)
	{
		$collection = new static::$collection([]);

		foreach ($queries as $query) {
			$collection = new static::$collection(array_merge(
				$collection->toArray(),
				$query->getResult()
			));
		}

		return $collection;
	}


	/**
	 *
	 */
	 public function count(QueryBuilder $builder, $non_limited = TRUE)
	 {
		 $bclone = clone $builder;

		 $bclone->select('count(this)');

         if ($non_limited) {
             $bclone->setMaxResults(NULL);
             $bclone->setFirstResult(0);
         }

		 return $bclone->getQuery()->getSingleScalarResult();
	 }


	/**
	 *
	 */
	public function create()
	{
		return new static::$entity;
	}


	/**
	 *
	 */
	public function detach($entity)
	{
		$this->_em->detach($entity);
	}


	/**
	 *
	 */
	public function merge($entity)
	{
		$this->_em->merge($entity);
	}


	/**
	 *
	 */
	public function remove($entity)
	{
		$this->_em->remove($entity);
	}


	/**
	 * Standard findAll with the option to add an orderBy
	 *
	 * @param array $orderBy The order by clause to add
	 *
	 * {@inheritDoc}
	 *
	 */
	public function findAll(array $orderBy = array())
	{
		return $this->findBy(array(), $orderBy);
	}


	/**
	 * {@inheritDoc}
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$orderBy   = array_merge((array) $orderBy, static::$order);
		$persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);

		foreach ($criteria as $key => $value) {
			if ($value instanceof Collection) {
				$criteria[$key] = $value->getValues();
			}
		}

		return new static::$collection($persister->loadAll($criteria, $orderBy, $limit, $offset));
	}


	/**
	 * {@inheritDoc}
	 */
	public function findOneBy(array $criteria, array $orderBy = null)
	{
		$orderBy = array_merge((array) $orderBy, static::$order);

		return parent::findOneBy($criteria, $orderBy);
	}


	/**
	 *
	 */
	public function paginate(QueryBuilder $builder, $limit, $page)
	{
		$count = $this->count($builder);
		$pages = $count / $limit;

		$builder->setMaxResults($limit);

		if ($pages < 1) {
			$builder->setFirstResult(0);

		} else {
			if ($page >= 1) {
				$builder->setFirstResult(($page - 1) * $limit);
			} elseif ($page <= -1) {
				$builder->setFirstResult($count + ($page * $limit));
			} else {
				// TODO: Throw exception
			}
		}

		return $count;
	}

	/**
	 * 
	 */
	public function getQueryBuilder()
	{
		return $this->_em
			->createQueryBuilder()
			->select('this')
			->from(static::$entity, 'this');
	}


	/**
	  *
	 */
	public function query($build_callback, &$nonlimited_count = NULL)
	{
		$builder = $this->_em
			-> createQueryBuilder()
			-> select('this')
			-> from(static::$entity, 'this')
		;

		foreach (static::$order as $property => $direction) {
			$builder->addOrderBy('this.' . $property, $direction);
		}

		if (is_callable($build_callback)) {
			$build_callback($builder);

		} elseif (is_string($build_callback) || is_array($build_callback)) {
			settype($build_callback, 'array');

			foreach ($build_callback as $method) {
				if (!is_callable($method)) {
					$method = [$this, 'query' . ucfirst($method)];
				}

				$method($builder);
			}

		} else {
			throw new InvalidArgumentException('Invalid builder type');
		}

		if (func_num_args() == 2) {
			$nonlimited_count = $this->count($builder);
		}

		return $builder->getQuery();
	}


	/**
     *
     */
    public function search(AbstractCriteria $criteria)
    {
        $query = $this->query(function($builder) use ($criteria) {
            $criteria->build($builder);
        }, $count);

        $criteria->setTotalMatchCount($count);

        return $this->collect($query);
    }


	/**
	 *
	 */
	public function store($entity, $flush = FALSE)
	{
		$this->_em->persist($entity);

		if ($flush) {
			$this->_em->flush($entity);
		}
	}
}
