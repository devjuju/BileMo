<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            ['John', 'Doe', 'john@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Sarah', 'Martin', 'sarah@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Paul', 'Durand', 'paul@sfr.fr', ClientFixtures::SFR_CLIENT],
            ['Emma', 'Petit', 'emma@sfr.fr', ClientFixtures::SFR_CLIENT],
        ];

        foreach ($users as [$firstname, $lastname, $email, $clientRef]) {
            $user = new User();

            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);

            $user->setClient(
                $this->getReference($clientRef, \App\Entity\Client::class)
            );

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class
        ];
    }
}
