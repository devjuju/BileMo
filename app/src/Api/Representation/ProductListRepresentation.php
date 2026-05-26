<?php

namespace App\Api\Representation;

use App\Application\DTO\Product\ProductListDTO;

class ProductListRepresentation
{
    public function __construct(
        private ProductListDTO $product
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'brand' => $this->product->brand,
            'price' => $this->product->price,

            '_links' => [
                'self' => [
                    'href' => '/api/products/' . $this->product->id,
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
