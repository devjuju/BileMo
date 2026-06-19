<?php

namespace App\Controller\Api\Product;

use App\Api\Representation\ProductListRepresentation;
use App\Application\Handler\Product\GetProductsHandler;
use App\Application\Query\Product\GetProductsQuery;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ListProductsController extends AbstractController
{
    #[OA\Get(
        path: '/api/products',
        summary: 'Liste des produits',
        description: 'Retourne la liste paginée des produits BileMo avec filtres optionnels.',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Numéro de page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'brand',
                description: 'Filtrer par marque',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'minPrice',
                description: 'Prix minimum',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'number', format: 'float')
            ),
            new OA\Parameter(
                name: 'maxPrice',
                description: 'Prix maximum',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'number', format: 'float')
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Nombre d’éléments par page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 5)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des produits récupérée avec succès'
            )
        ]
    )]
    #[Route('/api/products', name: 'api_products_list', methods: ['GET'])]
    public function __invoke(
        Request $request,
        GetProductsHandler $handler
    ): JsonResponse {

        /**
         * Pagination sécurisée
         */
        $page = max(1, $request->query->getInt('page', 1));
        $limit = max(1, $request->query->getInt('limit', 5));

        /**
         * Filtres
         */
        $brand = $request->query->get('brand');

        $minPriceRaw = $request->query->get('minPrice');
        $maxPriceRaw = $request->query->get('maxPrice');

        $minPrice = is_numeric($minPriceRaw) ? (float) $minPriceRaw : null;
        $maxPrice = is_numeric($maxPriceRaw) ? (float) $maxPriceRaw : null;

        /**
         * CQRS Query
         */
        $result = $handler->handle(
            new GetProductsQuery(
                $page,
                $limit,
                $brand,
                $minPrice,
                $maxPrice
            )
        );

        /**
         * DTO → HATEOAS representation
         */
        $data = array_map(
            fn($dto) => (new ProductListRepresentation($dto))->toArray(),
            $result['data']
        );

        /**
         * Sécurisation pagination
         */
        $totalItems = (int) $result['total'];
        $totalPages = $limit > 0 ? (int) ceil($totalItems / $limit) : 0;

        /**
         * Réponse JSON
         */
        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'data' => $data
        ]);

        /**
         * Cache HTTP
         */
        $response->setPublic();
        $response->setMaxAge(60);

        /**
         * ETag robuste (stable et unique)
         */
        $etag = md5(json_encode([
            $page,
            $limit,
            $brand,
            $minPrice,
            $maxPrice,
            $totalItems
        ]));

        $response->setEtag($etag);

        /**
         * 304 Not Modified
         */
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
