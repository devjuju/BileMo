<?php

namespace App\Application\Command\User;

use App\Entity\Client;

final class DeleteUserCommand
{
    public function __construct(
        public readonly int $id,
        public readonly Client $client
    ) {}
}
