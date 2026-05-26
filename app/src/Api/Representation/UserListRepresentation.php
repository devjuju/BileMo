<?php

namespace App\Api\Representation;

use App\Application\DTO\User\UserListDTO;

class UserListRepresentation
{
    public function __construct(
        private UserListDTO $user
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->user->id,
            'firstname' => $this->user->firstname,
            'lastname' => $this->user->lastname,

            '_links' => [
                'self' => [
                    'href' => '/api/users/' . $this->user->id,
                    'method' => 'GET'
                ],
                'delete' => [
                    'href' => '/api/users/' . $this->user->id,
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
