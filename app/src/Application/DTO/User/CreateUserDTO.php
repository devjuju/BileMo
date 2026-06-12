<?php

namespace App\Application\DTO\User;

/**
 * DTO utilisé pour la création d'un utilisateur.
 *
 * Il transporte les données saisies par le client API
 * avant transformation en Command CQRS.
 */
class CreateUserDTO
{
    /**
     * Constructeur du DTO de création utilisateur.
     *
     * Les propriétés readonly garantissent que
     * les données ne peuvent pas être modifiées
     * après instanciation.
     */
    public function __construct(

        // Adresse email de l'utilisateur
        public readonly string $email,

        // Prénom de l'utilisateur
        public readonly string $firstname,

        // Nom de famille de l'utilisateur
        public readonly string $lastname
    ) {}
}
