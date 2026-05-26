<?php

namespace App\Application\DTO\Product;

class ProductDetailDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $brand,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly string $color,
        public readonly string $storage,
        public readonly string $screenSize
    ) {}
}
