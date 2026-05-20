<?php

namespace App\Controller\Api\Product;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function detail(
        Product $product,
        Request $request
    ): JsonResponse {
        $response = $this->json(
            $product,
            200,
            [],
            [
                'groups' => 'product:detail'
            ]
        );

        /*
         * CACHE HTTP
         */

        // public : catalogue partagé
        $response->setPublic();

        // 60 sec
        $response->setMaxAge(60);

        // ETag basé sur le produit
        $etag = md5(
            $product->getId()
                . $product->getName()
                . $product->getPrice()
        );

        $response->setEtag($etag);

        // Last Modified via Timestampable
        if ($product->getUpdatedAt()) {
            $response->setLastModified($product->getUpdatedAt());
        }

        // 304 automatique
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
