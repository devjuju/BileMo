<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

interface SpecificationInterface
{
    public function apply(QueryBuilder $qb, string $alias): void;
}
