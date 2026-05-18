<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    public const ORANGE_CLIENT = 'orange-client';
    public const SFR_CLIENT = 'sfr-client';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $orange = new Client();
        $orange->setCompany('Orange Business');
        $orange->setEmail('orange@bilemo.fr');
        $orange->setPassword(
            $this->passwordHasher->hashPassword(
                $orange,
                'password123'
            )
        );

        $manager->persist($orange);
        $this->addReference(self::ORANGE_CLIENT, $orange);

        $sfr = new Client();
        $sfr->setCompany('SFR Business');
        $sfr->setEmail('sfr@bilemo.fr');
        $sfr->setPassword(
            $this->passwordHasher->hashPassword(
                $sfr,
                'password123'
            )
        );

        $manager->persist($sfr);
        $this->addReference(self::SFR_CLIENT, $sfr);

        $manager->flush();
    }
}
