<?php

namespace App\Application\Query\User;

use App\Entity\Client;

/**
 * Query CQRS utilisée pour récupérer
 * la liste des utilisateurs d’un client.
 *
 * Elle centralise les paramètres de pagination
 * et les filtres de recherche.
 */
final class GetUsersQuery
{
    /**
     * Constructeur de la Query.
     *
     * Les propriétés readonly garantissent
     * l’immuabilité des paramètres.
     */
    public function __construct(
        // Client authentifié (sécurité B2B)
        public readonly Client $client,

        // Numéro de page (pagination)
        public readonly int $page,

        // Nombre d’éléments par page
        public readonly int $limit,

        // Filtre optionnel par email
        public readonly ?string $email = null,

        // Filtre optionnel par nom/prénom
        public readonly ?string $name = null,
    ) {}
}
