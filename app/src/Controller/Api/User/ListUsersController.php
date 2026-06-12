<?php

namespace App\Controller\Api\User;

use App\Api\Representation\UserListRepresentation;
use App\Application\Handler\User\GetUsersHandler;
use App\Application\Query\User\GetUsersQuery;
use App\Entity\Client;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Controller API permettant de récupérer
 * la liste paginée des utilisateurs liés à un client authentifié.
 *
 * Implémente CQRS (Query side), Specification Pattern,
 * et cache HTTP pour optimisation.
 */
final class ListUsersController extends AbstractController
{
    #[OA\Get(
        path: '/api/users',
        summary: 'Liste des utilisateurs',
        description: 'Retourne la liste paginée des utilisateurs liés au client authentifié.',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Numéro de page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
            new OA\Parameter(
                name: 'limit',
                description: 'Nombre d’éléments par page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 5)
            ),
            new OA\Parameter(
                name: 'email',
                description: 'Filtrer par email',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'name',
                description: 'Filtrer par prénom ou nom',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des utilisateurs récupérée avec succès'
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié'
            )
        ]
    )]
    #[Route('/api/users', name: 'api_users_list', methods: ['GET'])]
    public function __invoke(
        GetUsersHandler $handler,
        Security $security,
        Request $request
    ): JsonResponse {

        /**
         * Récupération du client authentifié (JWT)
         */
        /** @var Client $client */
        $client = $security->getUser();

        /**
         * Paramètres de pagination
         */
        $page = $request->query->getInt('page', 1);
        $limit = 5;

        /**
         * Filtres (Specification Pattern)
         */
        $email = $request->query->get('email');
        $name = $request->query->get('name');

        /**
         * Appel CQRS Query → Handler
         */
        $result = $handler->handle(
            new GetUsersQuery(
                $client,
                $page,
                $limit,
                email: $email,
                name: $name
            )
        );

        /**
         * Transformation DTO → Representation (HATEOAS)
         */
        $data = array_map(
            fn($dto) => (new UserListRepresentation($dto))->toArray(),
            $result['data']
        );

        /**
         * Réponse JSON paginée
         */
        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $result['total'],
            'total_pages' => ceil(
                $result['total'] / $limit
            ),
            'data' => $data
        ]);

        /*
         * =========================
         * CACHE HTTP (optimisation API)
         * =========================
         */

        $response->setPrivate();
        $response->setMaxAge(30);

        /**
         * ETag basé sur client + page + données
         */
        $etag = md5(
            $client->getId()
                . $page
                . json_encode($result['data'])
        );

        $response->setEtag($etag);

        /**
         * Retour 304 si cache valide
         */
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
