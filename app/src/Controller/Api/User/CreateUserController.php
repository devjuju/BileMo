<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserController extends AbstractController
{
    #[Route('/api/users', name: 'api_users_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        ValidatorInterface $validator
    ): JsonResponse {
        /** @var \App\Entity\Client $client */
        $client = $security->getUser();

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'Invalid JSON'
            ], 400);
        }

        $user = new User();
        $user->setEmail($data['email'] ?? '');
        $user->setFirstname($data['firstname'] ?? '');
        $user->setLastname($data['lastname'] ?? '');
        $user->setClient($client);

        $violations = $validator->validate($user);

        if (count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json([
                'status' => 400,
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 400);
        }

        $em->persist($user);
        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
        ], 201);
    }
}
