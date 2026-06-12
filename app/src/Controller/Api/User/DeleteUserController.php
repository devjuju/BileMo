<?php

namespace App\Controller\Api\User;

use App\Application\Command\User\DeleteUserCommand;
use App\Application\Handler\User\DeleteUserHandler;
use App\Entity\Client;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Controller API permettant la suppression
 * d’un utilisateur lié à un client authentifié.
 *
 * Respecte le modèle CQRS (Command) et la sécurité B2B.
 */
final class DeleteUserController extends AbstractController
{
    #[OA\Delete(
        path: '/api/users/{id}',
        summary: 'Supprimer un utilisateur',
        description: 'Supprime un utilisateur lié au client authentifié.',
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
                response: 204,
                description: 'Utilisateur supprimé avec succès'
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
    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        DeleteUserHandler $handler,
        Security $security
    ): Response {

        /**
         * Récupération du client authentifié (JWT)
         */
        /** @var Client $client */
        $client = $security->getUser();

        /**
         * Command CQRS de suppression
         */
        $deleted = $handler->handle(
            new DeleteUserCommand($id, $client)
        );

        /**
         * Cas utilisateur introuvable
         */
        if (!$deleted) {
            return $this->json([
                'status' => 404,
                'message' => 'User not found',
                'error_code' => 'NOT_FOUND'
            ], 404);
        }

        /**
         * Réponse REST standard : suppression réussie
         */
        return new Response(null, 204);
    }
}
