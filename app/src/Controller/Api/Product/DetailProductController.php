<?php

namespace App\Controller\Api\Product;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function detail(Product $product): JsonResponse
    {
        return $this->json(
            $product,
            200,
            [],
            [
                'groups' => 'product:detail'
            ]
        );
    }
}
