<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class UserByFirstnameSpec extends AbstractSpecification
{
    public function __construct(private string $firstname) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.firstname = :firstname")
            ->setParameter('firstname', $this->firstname);
    }
}
