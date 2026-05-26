<?php

namespace App\Application\Specification\Product;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class ProductPriceRangeSpec extends AbstractSpecification
{
    public function __construct(
        private ?float $min,
        private ?float $max
    ) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        if ($this->min !== null) {
            $qb->andWhere("$alias.price >= :min")
                ->setParameter('min', $this->min);
        }

        if ($this->max !== null) {
            $qb->andWhere("$alias.price <= :max")
                ->setParameter('max', $this->max);
        }
    }
}
