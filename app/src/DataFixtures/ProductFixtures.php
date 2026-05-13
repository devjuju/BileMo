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
            [
                'name' => 'iPhone 15',
                'brand' => 'Apple',
                'description' => 'Smartphone haut de gamme Apple',
                'price' => '1200.00',
                'stock' => 25,
                'color' => 'Noir',
                'storage' => '256 Go',
                'screenSize' => 6.1
            ],
            [
                'name' => 'Galaxy S25',
                'brand' => 'Samsung',
                'description' => 'Smartphone premium Samsung',
                'price' => '1099.00',
                'stock' => 18,
                'color' => 'Blanc',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'Pixel 10',
                'brand' => 'Google',
                'description' => 'Smartphone Google nouvelle génération',
                'price' => '899.00',
                'stock' => 12,
                'color' => 'Bleu',
                'storage' => '128 Go',
                'screenSize' => 6.3
            ],
            [
                'name' => 'Xiaomi 16 Pro',
                'brand' => 'Xiaomi',
                'description' => 'Smartphone Android premium',
                'price' => '799.00',
                'stock' => 30,
                'color' => 'Gris',
                'storage' => '256 Go',
                'screenSize' => 6.7
            ],
            [
                'name' => 'OnePlus 14',
                'brand' => 'OnePlus',
                'description' => 'Smartphone performant',
                'price' => '749.00',
                'stock' => 20,
                'color' => 'Vert',
                'storage' => '256 Go',
                'screenSize' => 6.6
            ],
        ];

        foreach ($products as $data) {
            $product = new Product();

            $product->setName($data['name']);
            $product->setBrand($data['brand']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setStock($data['stock']);
            $product->setColor($data['color']);
            $product->setStorage($data['storage']);
            $product->setScreenSize($data['screenSize']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
