<?php

namespace App\Application\Handler\User;

use App\Application\Command\User\DeleteUserCommand;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteUserHandler
{
    public function __construct(
        private UserRepository $repository,
        private EntityManagerInterface $em
    ) {}

    public function handle(DeleteUserCommand $command): bool
    {
        $user = $this->repository->findOneBy([
            'id' => $command->id,
            'client' => $command->client
        ]);

        if (!$user) {
            return false;
        }

        $this->em->remove($user);
        $this->em->flush();

        return true;
    }
}
