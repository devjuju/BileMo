<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class UserByLastnameSpec extends AbstractSpecification
{
    public function __construct(private string $lastname) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.lastname = :lastname")
            ->setParameter('lastname', $this->lastname);
    }
}
