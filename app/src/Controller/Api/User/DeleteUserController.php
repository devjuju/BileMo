<?php

namespace App\Controller\Api\User;

use App\Application\Command\User\DeleteUserCommand;
use App\Application\Handler\User\DeleteUserHandler;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DeleteUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        DeleteUserHandler $handler,
        Security $security
    ): Response {
        /** @var Client $client */
        $client = $security->getUser();

        $deleted = $handler->handle(
            new DeleteUserCommand($id, $client)
        );

        if (!$deleted) {
            return $this->json([
                'status' => 404,
                'message' => 'User not found',
                'error_code' => 'NOT_FOUND'
            ], 404);
        }

        return new Response(null, 204);
    }
}
