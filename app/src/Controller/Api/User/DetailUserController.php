<?php

namespace App\Controller\Api\User;

use App\Application\Handler\User\GetUserHandler;
use App\Application\Query\User\GetUserQuery;
use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class DetailUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_detail', methods: ['GET'])]
    public function __invoke(
        int $id,
        GetUserHandler $handler,
        Security $security,
        Request $request
    ): JsonResponse {
        /** @var Client $client */
        $client = $security->getUser();

        $data = $handler->handle(
            new GetUserQuery($id, $client)
        );

        if (!$data) {
            return $this->json([
                'error' => 'Utilisateur non trouvé'
            ], 404);
        }

        $response = $this->json($data);

        /*
         * CACHE HTTP
         */

        $response->setPrivate();
        $response->setMaxAge(60);

        $etag = md5(
            $client->getId()
                . json_encode($data)
        );

        $response->setEtag($etag);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
