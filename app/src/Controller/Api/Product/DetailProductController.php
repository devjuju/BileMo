<?php

namespace App\Controller\Api\Product;

use App\Application\Handler\Product\GetProductHandler;
use App\Application\Query\Product\GetProductQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class DetailProductController extends AbstractController
{
    #[Route('/api/products/{id}', name: 'api_product_detail', methods: ['GET'])]
    public function __invoke(
        int $id,
        GetProductHandler $handler,
        Request $request
    ): JsonResponse {
        $data = $handler->handle(
            new GetProductQuery($id)
        );

        if (!$data) {
            return $this->json([
                'message' => 'Product not found'
            ], 404);
        }

        $response = $this->json($data);

        /*
         * CACHE HTTP
         */

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
