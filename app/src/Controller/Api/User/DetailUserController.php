<?php

namespace App\Controller\Api\User;

use App\Api\Representation\UserDetailRepresentation;
use App\Application\Handler\User\GetUserHandler;
use App\Application\Query\User\GetUserQuery;
use App\Entity\Client;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DetailUserController extends AbstractController
{
    #[OA\Get(
        path: '/api/users/{id}',
        summary: 'Détail d’un utilisateur',
        description: 'Retourne le détail d’un utilisateur lié au client authentifié.',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Identifiant de l’utilisateur',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Utilisateur trouvé'
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié'
            ),
            new OA\Response(
                response: 404,
                description: 'Utilisateur non trouvé'
            )
        ]
    )]
    #[Route('/api/users/{id}', name: 'api_user_detail', methods: ['GET'])]
    public function __invoke(
        int $id,
        GetUserHandler $handler,
        Security $security,
        Request $request
    ): JsonResponse {
        /** @var Client $client */
        $client = $security->getUser();

        $dto = $handler->handle(
            new GetUserQuery($id, $client)
        );

        if (!$dto) {
            return $this->json([
                'error' => 'Utilisateur non trouvé'
            ], 404);
        }

        $data = (new UserDetailRepresentation($dto))->toArray();

        $response = $this->json($data);

        /*
         * CACHE HTTP
         */
        $response->setPrivate();
        $response->setMaxAge(60);

        $etag = md5(
            $client->getId()
                . json_encode($data)
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
