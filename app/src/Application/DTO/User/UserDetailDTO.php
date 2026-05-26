<?php

namespace App\Application\DTO\User;

class UserDetailDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname
    ) {}
}
