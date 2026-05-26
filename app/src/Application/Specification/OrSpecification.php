<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

class OrSpecification extends AbstractSpecification
{
    /** @var SpecificationInterface[] */
    private array $specs;

    public function __construct(SpecificationInterface ...$specs)
    {
        $this->specs = $specs;
    }

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $expr = $qb->expr()->orX();

        foreach ($this->specs as $index => $spec) {
            $subQb = clone $qb;

            // reset WHERE pour construire condition indépendante
            $subQb->resetDQLPart('where');

            $spec->apply($subQb, $alias);

            $wherePart = $subQb->getDQLPart('where');

            if ($wherePart) {
                $expr->add($wherePart);
            }
        }

        $qb->andWhere($expr);
    }
}
