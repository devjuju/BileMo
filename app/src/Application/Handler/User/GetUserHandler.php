<?php

namespace App\Application\Handler\User;

use App\Application\Query\User\GetUserQuery;
use App\Api\Representation\UserDetailRepresentation;
use App\Repository\UserRepository;

final class GetUserHandler
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function handle(
        GetUserQuery $query
    ): ?array {
        $user = $this->repository->findOneBy([
            'id' => $query->id,
            'client' => $query->client
        ]);

        if (!$user) {
            return null;
        }

        return (
            new UserDetailRepresentation($user)
        )->toArray();
    }
}
