<?php

namespace App\Controller\Api\User;

use App\Api\Representation\UserListRepresentation;
use App\Application\Handler\User\GetUsersHandler;
use App\Application\Query\User\GetUsersQuery;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class ListUsersController extends AbstractController
{
    #[Route('/api/users', name: 'api_users_list', methods: ['GET'])]
    public function __invoke(
        GetUsersHandler $handler,
        Security $security,
        Request $request
    ): JsonResponse {
        /** @var Client $client */
        $client = $security->getUser();

        $page = $request->query->getInt('page', 1);
        $limit = 5;

        $result = $handler->handle(
            new GetUsersQuery(
                $client,
                $page,
                $limit
            )
        );

        $data = array_map(
            fn($dto) => (new UserListRepresentation($dto))->toArray(),
            $result['data']
        );

        $response = $this->json([
            'page' => $page,
            'limit' => $limit,
            'total_items' => $result['total'],
            'total_pages' => ceil(
                $result['total'] / $limit
            ),
            'data' => $data
        ]);

        /*
         * CACHE HTTP
         */

        $response->setPrivate();
        $response->setMaxAge(30);

        $etag = md5(
            $client->getId()
                . $page
                . json_encode($result['data'])
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
