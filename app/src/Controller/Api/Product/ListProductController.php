<?php

namespace App\Controller\Api\Product;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ListProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products_list', methods: ['GET'])]
    public function list(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'brand' => $product->getBrand(),
                'price' => $product->getPrice(),
                'stock' => $product->getStock(),
                'color' => $product->getColor(),
                'storage' => $product->getStorage(),
                'screenSize' => $product->getScreenSize(),
            ];
        }

        return $this->json($data);
    }
}
