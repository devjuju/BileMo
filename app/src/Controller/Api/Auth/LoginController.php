<?php

namespace App\Controller\Api\Auth;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[OA\Post(
        path: '/api/login_check',
        summary: 'Authentification JWT',
        description: 'Retourne un token JWT pour accéder aux endpoints sécurisés.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        example: 'client@test.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token JWT généré',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                            example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Identifiants invalides'
            )
        ]
    )]
    #[Route('/api/login_check', name: 'api_login_check_doc', methods: ['POST'])]
    public function __invoke(): Response
    {
        return new Response(status: 404);
    }
}
