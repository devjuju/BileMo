<?php

namespace App\Application\Handler\Product;

use App\Application\DTO\Product\ProductListDTO;
use App\Application\Query\Product\GetProductsQuery;
use App\Repository\ProductRepository;

class GetProductsHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function handle(GetProductsQuery $query): array
    {
        $products = $this->productRepository->findBy(
            [],
            null,
            $query->limit,
            ($query->page - 1) * $query->limit
        );

        return array_map(
            fn($product) => new ProductListDTO(
                $product->getId(),
                $product->getName(),
                $product->getBrand(),
                $product->getPrice(),
            ),
            $products
        );
    }
}
