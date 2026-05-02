<?php

namespace App\Controller\Api\User;

use App\Repository\CustomerUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class ListUserController extends AbstractController
{
    #[Route('/api/users', name: 'api_users_list', methods: ['GET'])]
    public function __invoke(
        CustomerUserRepository $repository,
        Security $security
    ): JsonResponse {
        // 🔐 récupérer le client connecté
        $client = $security->getUser();

        // 🧠 sécurité (optionnel mais propre)
        if (!$client) {
            return $this->json(['message' => 'Unauthorized'], 401);
        }

        // 📊 récupérer uniquement ses users
        $users = $repository->findBy([
            'customer' => $client
        ]);

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
            ];
        }

        return $this->json($data);
    }
}
