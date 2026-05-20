<?php

namespace App\Controller\Api\User;

use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DetailUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_detail', methods: ['GET'])]
    public function detail(
        int $id,
        UserRepository $userRepository,
        Security $security,
        Request $request
    ): JsonResponse {
        /** @var Client $client */
        $client = $security->getUser();

        $user = $userRepository->findOneForClient($id, $client);

        if (!$user) {
            return $this->json([
                'error' => 'Utilisateur non trouvé'
            ], 404);
        }

        $response = $this->json(
            $user,
            200,
            [],
            ['groups' => 'user:detail']
        );

        /*
         * CACHE HTTP
         */

        // cache privé (lié au client)
        $response->setPrivate();

        // 60 sec
        $response->setMaxAge(60);

        // ETag : client + user
        $etag = md5(
            $client->getId()
                . $user->getId()
                . $user->getEmail()
        );

        $response->setEtag($etag);

        // Last Modified via Timestampable
        if ($user->getUpdatedAt()) {
            $response->setLastModified($user->getUpdatedAt());
        }

        // 304 automatique
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
