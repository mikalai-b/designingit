<?php

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory as RepositoryFactoryInterface;

/**
 *
 */
final class RepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * The list of EntityRepository instances.
     *
     * @var \Doctrine\Common\Persistence\ObjectRepository[]
     */
    private $repositoryList = array();


    /**
     *
     */
    public function __construct(Illuminate\Container\Container $container)
    {
        $this->container = $container;
    }


    /**
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryHash = $entityManager->getClassMetadata($entityName)->getName() . spl_object_hash($entityManager);

        if (isset($this->repositoryList[$repositoryHash])) {
            return $this->repositoryList[$repositoryHash];
        }

        return $this->repositoryList[$repositoryHash] = $this->createRepository($entityManager, $entityName);
    }


    /**
     *
     */
    private function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata            = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->customRepositoryClassName
            ?: $entityManager->getConfiguration()->getDefaultRepositoryClassName();

        return $this->container->make($repositoryClassName);
    }
}
