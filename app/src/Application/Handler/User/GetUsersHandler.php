<?php

namespace App\Application\Handler\User;

use App\Api\Representation\UserListRepresentation;
use App\Application\Query\User\GetUsersQuery;
use App\Repository\UserRepository;

class GetUsersHandler
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function handle(GetUsersQuery $query): array
    {
        $users = $this->repository->findBy(
            ['client' => $query->client],
            null,
            $query->limit,
            ($query->page - 1) * $query->limit
        );

        $total = $this->repository->count([
            'client' => $query->client
        ]);

        return [
            'data' => array_map(
                fn($user) => (new UserListRepresentation($user))->toArray(),
                $users
            ),
            'total' => $total
        ];
    }
}
