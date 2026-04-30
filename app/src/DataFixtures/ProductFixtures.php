<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            ['iPhone 15', 'Apple', 1200],
            ['Galaxy S23', 'Samsung', 1000],
            ['Pixel 8', 'Google', 900],
            ['Xiaomi 13', 'Xiaomi', 800],
            ['OnePlus 11', 'OnePlus', 850],
        ];

        foreach ($products as $data) {
            $product = new Product();
            $product->setName($data[0]);
            $product->setBrand($data[1]);
            $product->setPrice($data[2]);
            $product->setDescription('Smartphone haut de gamme');
            $product->setStock(10);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
