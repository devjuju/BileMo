<?php

namespace App\Application\DTO\User;

/**
 * DTO représentant un utilisateur dans une liste.
 *
 * Il est utilisé pour les endpoints de type collection
 * afin d'afficher uniquement les informations essentielles.
 */
class UserListDTO
{
    /**
     * Constructeur du DTO utilisateur (liste).
     *
     * Les propriétés readonly garantissent
     * l'immuabilité des données.
     */
    public function __construct(

        // Identifiant unique de l'utilisateur
        public readonly int $id,

        // Prénom de l'utilisateur
        public readonly string $firstname,

        // Nom de famille de l'utilisateur
        public readonly string $lastname
    ) {}
}
