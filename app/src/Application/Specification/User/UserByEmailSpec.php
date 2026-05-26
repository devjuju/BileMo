<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class UserByEmailSpec extends AbstractSpecification
{
    public function __construct(private string $email) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.email LIKE :email")
            ->setParameter('email', '%' . $this->email . '%');
    }
}
