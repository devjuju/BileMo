<?php

namespace App\Application\Handler\User;

use App\Application\DTO\User\UserDetailDTO;
use App\Application\Query\User\GetUserQuery;
use App\Repository\UserRepository;

final class GetUserHandler
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function handle(
        GetUserQuery $query
    ): ?UserDetailDTO {
        $user = $this->repository->findOneBy([
            'id' => $query->id,
            'client' => $query->client
        ]);

        if (!$user) {
            return null;
        }

        return new UserDetailDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstname(),
            $user->getLastname()
        );
    }
}
