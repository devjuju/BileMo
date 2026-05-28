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
        /** @var Client $client */
        $client = $security->getUser();

        $data = json_decode(
            $request->getContent(),
            true
        );

        if (!$data) {
            return $this->json([
                'message' => 'Invalid JSON'
            ], 400);
        }

        $dto = new CreateUserDTO(
            $data['email'] ?? '',
            $data['firstname'] ?? '',
            $data['lastname'] ?? ''
        );

        /*
         * Validation
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
         * CQRS Command
         */

        $command = new CreateUserCommand(
            $dto->email,
            $dto->firstname,
            $dto->lastname,
            $client
        );

        $createdUser = $handler->handle($command);

        return $this->json([
            'id' => $createdUser->getId(),
            'email' => $createdUser->getEmail(),
            'firstname' => $createdUser->getFirstname(),
            'lastname' => $createdUser->getLastname(),
        ], 201);
    }
}
