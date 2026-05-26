<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class UserByNameSpec extends AbstractSpecification
{
    public function __construct(private string $name) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.firstname LIKE :name OR $alias.lastname LIKE :name")
            ->setParameter('name', '%' . $this->name . '%');
    }
}
