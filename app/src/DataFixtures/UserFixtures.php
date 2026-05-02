<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const CLIENT_REFERENCE = 'client_user';

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $client = new User();
        $client->setEmail('client@test.com');
        $client->setRoles(['ROLE_CLIENT']);
        $client->setPassword(
            $this->hasher->hashPassword($client, 'password')
        );

        $manager->persist($client);
        $manager->flush();

        // 🔗 référence pour autres fixtures
        $this->addReference(self::CLIENT_REFERENCE, $client);
    }
}
