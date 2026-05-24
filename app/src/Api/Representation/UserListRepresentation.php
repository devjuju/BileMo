<?php

namespace App\Api\Representation;

use App\Entity\User;

class UserListRepresentation
{
    public function __construct(
        private User $user
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->user->getId(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),

            '_links' => [
                'self' => [
                    'href' => '/api/users/' . $this->user->getId(),
                    'method' => 'GET'
                ],
                'delete' => [
                    'href' => '/api/users/' . $this->user->getId(),
                    'method' => 'DELETE'
                ],
                'collection' => [
                    'href' => '/api/users',
                    'method' => 'GET'
                ],
                'create' => [
                    'href' => '/api/users',
                    'method' => 'POST'
                ]
            ]
        ];
    }
}
