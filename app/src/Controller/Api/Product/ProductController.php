<?php

namespace App\Controller\Api\Product;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

final class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_api_products_list', methods: ['GET'])]
    public function list(ProductRepository $productRepository, Request $request): JsonResponse
    {
        // Pagination
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $products = $productRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'brand' => $product->getBrand(),
                'price' => $product->getPrice(),
            ];
        }

        return $this->json([
            'page' => $page,
            'limit' => $limit,
            'data' => $data
        ]);
    }
}
