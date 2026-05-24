<?php

namespace App\Application\Query\Product;

class GetProductQuery
{
    public function __construct(
        public readonly int $id
    ) {}
}
