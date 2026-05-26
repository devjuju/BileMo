<?php

namespace App\Application\DTO\Product;

class ProductListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $brand,
        public readonly float $price,
    ) {}
}
