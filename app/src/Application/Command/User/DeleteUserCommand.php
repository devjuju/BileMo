<?php

namespace App\Application\Command\User;

use App\Entity\Client;

/**
 * Command CQRS utilisé pour supprimer
 * un utilisateur de l'application.
 *
 * Cette classe transporte uniquement
 * les données nécessaires à la suppression.
 */
final class DeleteUserCommand
{
    /**
     * Constructeur du command.
     *
     * Les propriétés readonly permettent
     * de garantir l'immuabilité des données.
     */
    public function __construct(

        /**
         * Identifiant de l'utilisateur à supprimer.
         */
        public readonly int $id,

        /**
         * Client authentifié réalisant la suppression.
         *
         * Permet de vérifier que l'utilisateur
         * appartient bien au client connecté.
         */
        public readonly Client $client
    ) {}
}
