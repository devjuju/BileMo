<?php

namespace App\Controller\Api\User;

use App\Application\Command\User\CreateUserCommand;
use App\Application\Handler\User\CreateUserHandler;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserController extends AbstractController
{
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

        /*
         * Validation
         */

        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setFirstname($data['firstname'] ?? '');
        $user->setLastname($data['lastname'] ?? '');
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
            $data['email'],
            $data['firstname'],
            $data['lastname'],
            $client
        );

        $createdUser = $handler->handle(
            $command
        );

        return $this->json([
            'id' => $createdUser->getId(),
            'email' => $createdUser->getEmail(),
            'firstname' => $createdUser->getFirstname(),
            'lastname' => $createdUser->getLastname(),
        ], 201);
    }
}
