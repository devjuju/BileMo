<?php

namespace App\Repository;

use App\Application\Specification\SpecificationInterface;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, User::class);
    }

    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.client = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();
    }

    public function findOneForClient(int $id, Client $client): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->andWhere('u.client = :client')
            ->setParameter('id', $id)
            ->setParameter('client', $client)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user, bool $flush = false): void
    {
        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(User $user, bool $flush = false): void
    {
        $this->entityManager->remove($user);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function match(SpecificationInterface $spec, string $alias, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder($alias);

        $spec->apply($qb, $alias);

        return $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function countMatch(SpecificationInterface $spec, string $alias): int
    {
        $qb = $this->createQueryBuilder($alias)
            ->select("COUNT($alias.id)");

        $spec->apply($qb, $alias);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
