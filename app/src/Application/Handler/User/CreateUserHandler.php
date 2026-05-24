<?php

namespace App\Application\Handler\User;

use App\Application\Command\User\CreateUserCommand;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserHandler
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function handle(CreateUserCommand $command): User
    {
        $user = new User();

        $user->setEmail($command->email);
        $user->setFirstname($command->firstname);
        $user->setLastname($command->lastname);
        $user->setClient($command->client);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
