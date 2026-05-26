<?php

namespace App\Api\Representation;

use App\Application\DTO\Product\ProductDetailDTO;

class ProductDetailRepresentation
{
    public function __construct(
        private ProductDetailDTO $product
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'brand' => $this->product->brand,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'stock' => $this->product->stock,
            'color' => $this->product->color,
            'storage' => $this->product->storage,
            'screenSize' => $this->product->screenSize,

            '_links' => [
                'self' => [
                    'href' => '/api/products/' . $this->product->id,
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
