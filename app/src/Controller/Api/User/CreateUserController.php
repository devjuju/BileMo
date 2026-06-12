<?php

namespace App\Controller\Api\User;

use App\Application\Command\User\CreateUserCommand;
use App\Application\DTO\User\CreateUserDTO;
use App\Application\Handler\User\CreateUserHandler;
use App\Entity\Client;
use App\Entity\User;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Controller API permettant de créer
 * un utilisateur lié à un client authentifié.
 *
 * Il applique validation, CQRS Command,
 * et sécurisation B2B.
 */
final class CreateUserController extends AbstractController
{
    #[OA\Post(
        path: '/api/users',
        summary: 'Créer un utilisateur',
        description: 'Crée un nouvel utilisateur lié au client authentifié.',
        tags: ['Users'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'firstname', 'lastname'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'john.doe@test.com'
                    ),
                    new OA\Property(
                        property: 'firstname',
                        type: 'string',
                        example: 'John'
                    ),
                    new OA\Property(
                        property: 'lastname',
                        type: 'string',
                        example: 'Doe'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur créé avec succès'
            ),
            new OA\Response(
                response: 400,
                description: 'Erreur de validation ou JSON invalide'
            ),
            new OA\Response(
                response: 401,
                description: 'Non authentifié'
            )
        ]
    )]
    #[Route('/api/users', name: 'api_users_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
        Security $security,
        ValidatorInterface $validator,
        CreateUserHandler $handler
    ): JsonResponse {

        /**
         * Récupération du client authentifié (JWT)
         */
        /** @var Client $client */
        $client = $security->getUser();

        /**
         * Décodage du JSON reçu
         */
        $data = json_decode(
            $request->getContent(),
            true
        );

        // Vérification JSON valide
        if (!$data) {
            return $this->json([
                'message' => 'Invalid JSON'
            ], 400);
        }

        /**
         * Mapping vers DTO
         */
        $dto = new CreateUserDTO(
            $data['email'] ?? '',
            $data['firstname'] ?? '',
            $data['lastname'] ?? ''
        );

        /*
         * =========================
         * VALIDATION MÉTIER
         * =========================
         */

        $user = new User();
        $user->setEmail($dto->email);
        $user->setFirstname($dto->firstname);
        $user->setLastname($dto->lastname);
        $user->setClient($client);

        $violations = $validator->validate($user);

        if (count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json([
                'status' => 400,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 400);
        }

        /*
         * =========================
         * CQRS COMMAND
         * =========================
         */

        $command = new CreateUserCommand(
            $dto->email,
            $dto->firstname,
            $dto->lastname,
            $client
        );

        $createdUser = $handler->handle($command);

        /**
         * Réponse API finale
         */
        return $this->json([
            'id' => $createdUser->getId(),
            'email' => $createdUser->getEmail(),
            'firstname' => $createdUser->getFirstname(),
            'lastname' => $createdUser->getLastname(),
        ], 201);
    }
}
