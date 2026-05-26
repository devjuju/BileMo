<?php

namespace App\Application\DTO\User;

class UserListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstname,
        public readonly string $lastname
    ) {}
}
