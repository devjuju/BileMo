<?php

namespace App\Application\Command\User;

use App\Entity\Client;

final class CreateUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly Client $client
    ) {}
}
