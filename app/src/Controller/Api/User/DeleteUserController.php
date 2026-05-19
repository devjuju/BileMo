<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DeleteUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function __invoke(
        int $id,
        UserRepository $userRepository,
        Security $security
    ): Response {
        /** @var \App\Entity\Client $client */
        $client = $security->getUser();

        $user = $userRepository->findOneForClient($id, $client);

        if (!$user) {
            return $this->json([
                'status' => 404,
                'message' => 'User not found',
                'error_code' => 'NOT_FOUND'
            ], 404);
        }

        $userRepository->remove($user, true);

        return new Response(null, 204);
    }
}
