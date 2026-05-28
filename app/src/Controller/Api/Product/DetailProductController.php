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
        $dto = $handler->handle(
            new GetProductQuery($id)
        );

        if (!$dto) {
            return $this->json([
                'message' => 'Produit non trouvé'
            ], 404);
        }

        $data = (new ProductDetailRepresentation($dto))->toArray();

        $response = $this->json($data);

        $response->setPublic();
        $response->setMaxAge(60);

        $etag = md5(
            (string) $id . json_encode($data)
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
