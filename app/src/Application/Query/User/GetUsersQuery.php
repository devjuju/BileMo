<?php

namespace App\Application\Query\User;

use App\Entity\Client;

final class GetUsersQuery
{
    public function __construct(
        public readonly Client $client,
        public readonly int $page,
        public readonly int $limit
    ) {}
}
