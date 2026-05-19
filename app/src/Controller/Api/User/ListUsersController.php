<?php

namespace App\Controller\Api\User;

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

        $total = $repository->count(['client' => $client]);

        return $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $total,
            'total_pages' => ceil($total / $limit),
            'data' => $users
        ], 200, [], [
            'groups' => 'user:list'
        ]);
    }
}
