<?php

namespace App\Application\Query\Product;

class GetProductsQuery
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 5,
        public readonly ?string $brand = null,
        public readonly ?float $minPrice = null,
        public readonly ?float $maxPrice = null,
        public readonly ?bool $inStock = null
    ) {}
}
