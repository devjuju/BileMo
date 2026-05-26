<?php

namespace App\Application\DTO\User;

class CreateUserDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname
    ) {}
}
