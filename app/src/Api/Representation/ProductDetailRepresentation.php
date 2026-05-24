<?php

namespace App\Api\Representation;

use App\Entity\Product;

class ProductDetailRepresentation
{
    public function __construct(
        private Product $product
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->product->getId(),
            'name' => $this->product->getName(),
            'brand' => $this->product->getBrand(),
            'description' => $this->product->getDescription(),
            'price' => $this->product->getPrice(),
            'stock' => $this->product->getStock(),

            '_links' => [
                'self' => [
                    'href' => '/api/products/' . $this->product->getId(),
                    'method' => 'GET'
                ],
                'collection' => [
                    'href' => '/api/products',
                    'method' => 'GET'
                ]
            ]
        ];
    }
}
