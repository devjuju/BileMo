<?php

namespace App\Application\Handler\User;

use App\Application\Command\User\DeleteUserCommand;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handler CQRS chargé de la suppression
 * d'un utilisateur.
 *
 * Il vérifie d'abord que l'utilisateur
 * appartient bien au client authentifié
 * avant de le supprimer.
 */
class DeleteUserHandler
{
    /**
     * Injection du repository User et de Doctrine.
     */
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Traitement du DeleteUserCommand.
     *
     * @return bool Retourne true si suppression réussie,
     *              false si utilisateur introuvable.
     */
    public function handle(DeleteUserCommand $command): bool
    {
        // Recherche de l'utilisateur lié au client connecté
        $user = $this->repository->findOneBy([
            'id' => $command->id,
            'client' => $command->client
        ]);

        /**
         * Sécurité : si l'utilisateur n'existe pas
         * ou n'appartient pas au client → on stoppe.
         */
        if (!$user) {
            return false;
        }

        // Suppression de l'entité
        $this->em->remove($user);
        $this->em->flush();

        return true;
    }
}
