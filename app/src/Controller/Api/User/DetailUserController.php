<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DetailUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_detail', methods: ['GET'])]
    public function detail(
        int $id,
        UserRepository $userRepository,
        Security $security
    ): JsonResponse {
        /** @var \App\Entity\Client $client */
        $client = $security->getUser();

        $user = $userRepository->findOneForClient($id, $client);

        if (!$user) {
            return $this->json([
                'error' => 'Utilisateur non trouvé'
            ], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
        ]);
    }
}
