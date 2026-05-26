<?php

namespace App\Application\Specification\User;

use App\Application\Specification\SpecificationInterface;
use Doctrine\ORM\QueryBuilder;

class UserByClientSpec implements SpecificationInterface
{
    public function __construct(
        private $client
    ) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb->andWhere("$alias.client = :client")
            ->setParameter('client', $this->client);
    }
}
