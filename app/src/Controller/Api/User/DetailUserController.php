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

/**
 * Controller API permettant de récupérer
 * le détail d’un utilisateur lié à un client authentifié.
 *
 * Implémente CQRS (Query side), HATEOAS et cache HTTP.
 */
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

        /**
         * Récupération du client authentifié (JWT)
         */
        /** @var Client $client */
        $client = $security->getUser();

        /**
         * Query CQRS (lecture utilisateur)
         */
        $dto = $handler->handle(
            new GetUserQuery($id, $client)
        );

        /**
         * Cas utilisateur introuvable
         */
        if (!$dto) {
            return $this->json([
                'error' => 'Utilisateur non trouvé'
            ], 404);
        }

        /**
         * Transformation DTO → Representation (HATEOAS)
         */
        $data = (new UserDetailRepresentation($dto))->toArray();

        /**
         * Création de la réponse JSON
         */
        $response = $this->json($data);

        /*
         * =========================
         * CACHE HTTP (optimisation API)
         * =========================
         */

        $response->setPrivate();
        $response->setMaxAge(60);

        /**
         * ETag basé sur le client + données
         */
        $etag = md5(
            $client->getId()
                . json_encode($data)
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
