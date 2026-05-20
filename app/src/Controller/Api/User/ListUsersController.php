<?php

namespace App\Controller\Api\User;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class ListUsersController extends AbstractController
{
    #[Route('/api/users', name: 'api_users_list', methods: ['GET'])]
    public function list(
        UserRepository $repository,
        Security $security,
        Request $request
    ): JsonResponse {
        /** @var Client $client */
        $client = $security->getUser();

        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $users = $repository->findBy(
            ['client' => $client],
            null,
            $limit,
            $offset
        );

        $total = $repository->count([
            'client' => $client
        ]);

        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $total,
            'total_pages' => ceil($total / $limit),
            'data' => $users
        ], 200, [], [
            'groups' => 'user:list'
        ]);

        /*
         * CACHE HTTP
         */

        // cache privé car dépend du client connecté
        $response->setPrivate();

        // durée du cache : 30 sec
        $response->setMaxAge(30);

        // ETag unique
        $etag = md5(
            $client->getId()
                . $page
                . implode(',', array_map(
                    fn(User $u) => $u->getId(),
                    $users
                ))
        );

        $response->setEtag($etag);

        // retourne 304 si pas modifié
        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
