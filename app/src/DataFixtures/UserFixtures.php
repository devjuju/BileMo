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
        $users = $users = [

            // ORANGE

            ['John', 'Doe', 'john@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Sarah', 'Martin', 'sarah@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Lucas', 'Bernard', 'lucas@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Julie', 'Robert', 'julie@orange.fr', ClientFixtures::ORANGE_CLIENT],
            ['Thomas', 'Moreau', 'thomas@orange.fr', ClientFixtures::ORANGE_CLIENT],

            // SFR

            ['Paul', 'Durand', 'paul@sfr.fr', ClientFixtures::SFR_CLIENT],
            ['Emma', 'Petit', 'emma@sfr.fr', ClientFixtures::SFR_CLIENT],
            ['Nicolas', 'Roux', 'nicolas@sfr.fr', ClientFixtures::SFR_CLIENT],
            ['Camille', 'Simon', 'camille@sfr.fr', ClientFixtures::SFR_CLIENT],
            ['Hugo', 'Laurent', 'hugo@sfr.fr', ClientFixtures::SFR_CLIENT],
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
