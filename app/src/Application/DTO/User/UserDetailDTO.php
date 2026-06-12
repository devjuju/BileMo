<?php

namespace App\Application\DTO\User;

/**
 * DTO représentant le détail d'un utilisateur.
 *
 * Il est utilisé pour exposer les données
 * d'un utilisateur dans les réponses API
 * (GET /api/users/{id}).
 */
class UserDetailDTO
{
    /**
     * Constructeur du DTO utilisateur.
     *
     * Les propriétés sont readonly pour garantir
     * l'immuabilité des données.
     */
    public function __construct(

        // Identifiant unique de l'utilisateur
        public readonly int $id,

        // Email de l'utilisateur
        public readonly string $email,

        // Prénom de l'utilisateur
        public readonly string $firstname,

        // Nom de famille de l'utilisateur
        public readonly string $lastname
    ) {}
}
