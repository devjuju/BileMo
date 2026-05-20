<?php

namespace App\Controller\Api\Product;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products_list', methods: ['GET'])]
    public function list(
        ProductRepository $productRepository,
        Request $request
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $products = $productRepository->findBy(
            [],
            null,
            $limit,
            $offset
        );

        $total = $productRepository->count([]);

        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $total,
            'total_pages' => ceil($total / $limit),
            'data' => $products
        ], 200, [], [
            'groups' => 'product:list'
        ]);

        /*
         * CACHE HTTP
         */

        // public car catalogue identique pour tous
        $response->setPublic();

        // cache 60 secondes
        $response->setMaxAge(60);

        // ETag basé sur page + ids produits
        $etag = md5(
            $page .
                implode(',', array_map(
                    fn($product) => $product->getId(),
                    $products
                ))
        );

        $response->setEtag($etag);

        // si pas modifié -> 304
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
