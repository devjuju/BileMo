<?php

namespace App\Application\Query\Product;

class GetProductsQuery
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 5
    ) {}
}
