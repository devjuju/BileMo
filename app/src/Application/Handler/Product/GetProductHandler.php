<?php

namespace App\Application\Handler\Product;

use App\Application\Query\Product\GetProductQuery;
use App\Repository\ProductRepository;
use App\Api\Representation\ProductDetailRepresentation;

class GetProductHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function handle(GetProductQuery $query): ?array
    {
        $product = $this->productRepository->find($query->id);

        if (!$product) {
            return null;
        }

        return (new ProductDetailRepresentation($product))->toArray();
    }
}
