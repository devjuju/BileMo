<?php

namespace App\Controller\Api\Product;

use App\Api\Representation\ProductDetailRepresentation;
use App\Application\Handler\Product\GetProductHandler;
use App\Application\Query\Product\GetProductQuery;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller API permettant de récupérer
 * le détail d’un produit BileMo.
 *
 * Il orchestre la couche Application (CQRS)
 * et la représentation HATEOAS.
 */
final class DetailProductController extends AbstractController
{
    #[OA\Get(
        path: '/api/products/{id}',
        summary: 'Détail d’un produit',
        description: 'Retourne le détail d’un produit BileMo par son identifiant.',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant du produit',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Produit trouvé'
            ),
            new OA\Response(
                response: 404,
                description: 'Produit non trouvé'
            )
        ]
    )]
    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function __invoke(
        int $id,
        GetProductHandler $handler,
        Request $request
    ): JsonResponse {

        /**
         * Création de la Query CQRS
         * envoyée au handler.
         */
        $dto = $handler->handle(
            new GetProductQuery($id)
        );

        // Cas d’erreur : produit inexistant
        if (!$dto) {
            return $this->json([
                'message' => 'Produit non trouvé'
            ], 404);
        }

        /**
         * Transformation du DTO en représentation API
         * (HATEOAS / format JSON final)
         */
        $data = (new ProductDetailRepresentation($dto))->toArray();

        /**
         * Création de la réponse JSON
         */
        $response = $this->json($data);

        /**
         * Cache HTTP (optimisation performance API)
         */
        $response->setPublic();
        $response->setMaxAge(60);

        /**
         * ETag pour validation de cache côté client
         */
        $etag = md5(
            (string) $id . json_encode($data)
        );

        $response->setEtag($etag);

        /**
         * Retour 304 Not Modified si cache valide
         */
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
