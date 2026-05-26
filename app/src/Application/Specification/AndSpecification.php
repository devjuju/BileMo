<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

class AndSpecification extends AbstractSpecification
{
    /** @var SpecificationInterface[] */
    private array $specs;

    public function __construct(SpecificationInterface ...$specs)
    {
        $this->specs = $specs;
    }

    public function apply(QueryBuilder $qb, string $alias): void
    {
        foreach ($this->specs as $spec) {
            $spec->apply($qb, $alias);
        }
    }
}
