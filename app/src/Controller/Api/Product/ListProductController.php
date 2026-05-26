<?php

namespace App\Controller\Api\Product;

use App\Api\Representation\ProductListRepresentation;
use App\Application\Handler\Product\GetProductsHandler;
use App\Application\Query\Product\GetProductsQuery;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products_list', methods: ['GET'])]
    public function __invoke(
        Request $request,
        GetProductsHandler $handler,
        ProductRepository $productRepository
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $data = $handler->handle(
            new GetProductsQuery($page, $limit)
        );

        $formatted = array_map(
            fn($dto) => (new ProductListRepresentation($dto))->toArray(),
            $data
        );

        $total = $productRepository->count([]);

        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $total,
            'total_pages' => ceil($total / $limit),
            'data' => $formatted
        ]);

        /*
         * CACHE HTTP
         */

        $response->setPublic();
        $response->setMaxAge(60);

        $etag = md5(
            $page . json_encode($data)
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
