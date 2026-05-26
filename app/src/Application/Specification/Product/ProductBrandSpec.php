<?php

namespace App\Application\Specification\Product;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

class ProductBrandSpec extends AbstractSpecification
{
    public function __construct(private string $brand) {}

    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.brand = :brand")
            ->setParameter('brand', $this->brand);
    }
}
