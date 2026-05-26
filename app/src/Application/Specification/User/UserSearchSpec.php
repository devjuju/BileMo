<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class UserSearchSpec extends AbstractSpecification
{
    public function __construct(private string $search) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb->andWhere("
            ($alias.firstname LIKE :search
            OR $alias.lastname LIKE :search
            OR $alias.email LIKE :search)
        ")
            ->setParameter('search', '%' . $this->search . '%');
    }
}
