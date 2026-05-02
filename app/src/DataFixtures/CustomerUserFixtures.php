<?php

namespace App\DataFixtures;

use App\Entity\CustomerUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\Entity\User;

class CustomerUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $client = $this->getReference(
            UserFixtures::CLIENT_REFERENCE,
            User::class
        );

        for ($i = 1; $i <= 5; $i++) {
            $customerUser = new CustomerUser();
            $customerUser->setEmail("user$i@test.com");
            $customerUser->setFirstname("Firstname$i");
            $customerUser->setLastname("Lastname$i");
            $customerUser->setCustomer($client);

            $manager->persist($customerUser);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
