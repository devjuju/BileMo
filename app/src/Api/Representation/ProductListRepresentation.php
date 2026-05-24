<?php

namespace App\Api\Representation;

use App\Entity\Product;

class ProductListRepresentation
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
            'price' => $this->product->getPrice(),

            '_links' => [
                'self' => [
                    'href' => '/api/products/' . $this->product->getId(),
                    'method' => 'GET'
                ],
                'collection' => [
                    'href' => '/api/products',
                    'method' => 'GET'
                ],
                'documentation' => [
                    'href' => '/api/doc',
                    'method' => 'GET'
                ]
            ]
        ];
    }
}
