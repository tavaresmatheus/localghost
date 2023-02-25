<?php

namespace App\Repository\Base;

use App\Repository\Base\Contracts\BaseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository implements BaseRepositoryInterface
{
    protected string $entityClass;

    public function __construct(
        ManagerRegistry $registry,
        string $entityClass
    )
    {
        $this->entityClass = $entityClass;
        parent::__construct(
            $registry,
            $entityClass
        );
    }

    public function create(object $entity): array
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return [
            'id' => $entity->getId(),
        ];
    }

    public function findById(
        string $entityId
    ): ?object
    {
        $entityFound = $this->getEntityManager()->find(
            $this->entityClass,
            $entityId
        );

        return $entityFound;
    }

    public function listAll(): array
    {
        $entities = $this->getEntityManager()->getRepository($this->entityClass);
        $entitiesList = $entities->findAll();

        return $entitiesList;
    }

    public function delete(string $entityId): bool
    {
        $entity = $this->getEntityManager()->find(
            $this->entityClass,
            $entityId
        );
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

        return true;
    }
}