<?php

namespace App\Controller\Api\Product;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ShowProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'api_products_show', methods: ['GET'])]
    public function __invoke(?Product $product): JsonResponse
    {
        if (!$product) {
            return $this->json([
                'message' => 'Product not found'
            ], 404);
        }

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'brand' => $product->getBrand(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
        ]);
    }
}
