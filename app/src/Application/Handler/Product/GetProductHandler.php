<?php

namespace App\Application\Handler\Product;

use App\Application\DTO\Product\ProductDetailDTO;
use App\Application\Query\Product\GetProductQuery;
use App\Repository\ProductRepository;

class GetProductHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function handle(GetProductQuery $query): ?ProductDetailDTO
    {
        $product = $this->productRepository->find($query->id);

        if (!$product) {
            return null;
        }

        return new ProductDetailDTO(
            $product->getId(),
            $product->getName(),
            $product->getBrand(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock(),
            $product->getColor(),
            $product->getStorage(),
            $product->getScreenSize()
        );
    }
}
