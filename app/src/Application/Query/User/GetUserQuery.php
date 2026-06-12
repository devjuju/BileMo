<?php

namespace App\Application\Query\User;

use App\Entity\Client;

/**
 * Query CQRS utilisée pour récupérer
 * le détail d'un utilisateur.
 *
 * Elle transporte l'identifiant de l'utilisateur
 * ainsi que le client authentifié.
 */
final class GetUserQuery
{
    public function __construct(
        public readonly int $id,
        public readonly Client $client
    ) {}
}
