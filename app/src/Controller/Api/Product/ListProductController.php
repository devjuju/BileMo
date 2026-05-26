<?php

namespace App\Controller\Api\Product;

use App\Api\Representation\ProductListRepresentation;
use App\Application\Handler\Product\GetProductsHandler;
use App\Application\Query\Product\GetProductsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products_list', methods: ['GET'])]
    public function __invoke(
        Request $request,
        GetProductsHandler $handler
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $brand = $request->query->get('brand');

        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');

        $minPrice = $minPrice !== null ? (float) $minPrice : null;
        $maxPrice = $maxPrice !== null ? (float) $maxPrice : null;

        $result = $handler->handle(
            new GetProductsQuery(
                $page,
                $limit,
                $brand,
                $minPrice,
                $maxPrice
            )
        );

        $data = array_map(
            fn($dto) => (new ProductListRepresentation($dto))->toArray(),
            $result['data']
        );

        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $result['total'],
            'total_pages' => ceil($result['total'] / $limit),
            'data' => $data
        ]);

        /*
         * CACHE HTTP
         */
        $response->setPublic();
        $response->setMaxAge(60);

        $etag = md5(
            $page
                . $brand
                . $minPrice
                . $maxPrice
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
