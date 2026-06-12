<?php

namespace App\Application\Handler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Handler CQRS chargé de la création
 * d'un utilisateur.
 *
 * Il transforme un Command en entité
 * persistée en base de données.
 */
class CreateUserHandler
{
    /**
     * Injection de l'EntityManager Doctrine.
     *
     * Permet de gérer la persistance
     * des entités.
     */
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Traitement du CreateUserCommand.
     *
     * @param CreateUserCommand $command
     * @return User
     */
    public function handle(CreateUserCommand $command): User
    {
        // Création de l'entité User
        $user = new User();

        // Hydratation depuis le command CQRS
        $user->setEmail($command->email);
        $user->setFirstname($command->firstname);
        $user->setLastname($command->lastname);
        $user->setClient($command->client);

        /**
         * Persistance en base de données
         */
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
