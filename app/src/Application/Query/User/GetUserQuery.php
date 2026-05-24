<?php

namespace App\Application\Query\User;

use App\Entity\Client;

final class GetUserQuery
{
    public function __construct(
        public readonly int $id,
        public readonly Client $client
    ) {}
}
