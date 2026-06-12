<?php

namespace App\Application\Command\User;

use App\Entity\Client;

/**
 * Command CQRS permettant de transporter
 * les données nécessaires à la création
 * d'un utilisateur.
 *
 * Cette classe est utilisée par le Use Case
 * ou le Command Handler CreateUserHandler.
 */
final class CreateUserCommand
{
    /**
     * Constructeur du command.
     *
     * Les propriétés sont readonly afin de garantir
     * l'immuabilité des données pendant le traitement.
     */
    public function __construct(

        // Adresse email du nouvel utilisateur
        public readonly string $email,

        // Prénom de l'utilisateur
        public readonly string $firstname,

        // Nom de famille de l'utilisateur
        public readonly string $lastname,

        /**
         * Client propriétaire de l'utilisateur.
         *
         * Permet d'associer l'utilisateur
         * au client authentifié.
         */
        public readonly Client $client
    ) {}
}
