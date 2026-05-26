<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractSpecification implements SpecificationInterface
{
    abstract public function apply(QueryBuilder $qb, string $alias): void;

    /**
     * @param SpecificationInterface[] $specs
     */
    public static function and(SpecificationInterface ...$specs): AndSpecification
    {
        return new AndSpecification(...$specs);
    }

    /**
     * @param SpecificationInterface[] $specs
     */
    public static function or(SpecificationInterface ...$specs): OrSpecification
    {
        return new OrSpecification(...$specs);
    }
}
