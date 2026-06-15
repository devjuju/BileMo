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
                'name' => 'iPhone 15 Pro',
                'brand' => 'Apple',
                'description' => 'Version Pro de l’iPhone 15',
                'price' => '1399.00',
                'stock' => 15,
                'color' => 'Titane',
                'storage' => '512 Go',
                'screenSize' => 6.1
            ],
            [
                'name' => 'iPhone 15 Pro Max',
                'brand' => 'Apple',
                'description' => 'Version Pro Max de l’iPhone 15',
                'price' => '1599.00',
                'stock' => 10,
                'color' => 'Titane Noir',
                'storage' => '1 To',
                'screenSize' => 6.7
            ],
            [
                'name' => 'Galaxy S25 Ultra',
                'brand' => 'Samsung',
                'description' => 'Smartphone Samsung ultra premium',
                'price' => '1499.00',
                'stock' => 12,
                'color' => 'Noir',
                'storage' => '512 Go',
                'screenSize' => 6.9
            ],
            [
                'name' => 'Galaxy Z Fold 7',
                'brand' => 'Samsung',
                'description' => 'Smartphone pliable Samsung',
                'price' => '1899.00',
                'stock' => 8,
                'color' => 'Gris',
                'storage' => '512 Go',
                'screenSize' => 7.6
            ],
            [
                'name' => 'Pixel 10 Pro',
                'brand' => 'Google',
                'description' => 'Version Pro du Pixel',
                'price' => '1099.00',
                'stock' => 14,
                'color' => 'Noir',
                'storage' => '256 Go',
                'screenSize' => 6.7
            ],
            [
                'name' => 'Pixel Fold 2',
                'brand' => 'Google',
                'description' => 'Smartphone pliable Google',
                'price' => '1699.00',
                'stock' => 5,
                'color' => 'Blanc',
                'storage' => '512 Go',
                'screenSize' => 7.4
            ],
            [
                'name' => 'Xiaomi 16 Ultra',
                'brand' => 'Xiaomi',
                'description' => 'Flagship Xiaomi',
                'price' => '1199.00',
                'stock' => 20,
                'color' => 'Noir',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'OnePlus 14 Pro',
                'brand' => 'OnePlus',
                'description' => 'Version Pro OnePlus',
                'price' => '999.00',
                'stock' => 18,
                'color' => 'Vert',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'Honor Magic 7 Pro',
                'brand' => 'Honor',
                'description' => 'Smartphone haut de gamme Honor',
                'price' => '1099.00',
                'stock' => 16,
                'color' => 'Noir',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'Huawei Mate 70 Pro',
                'brand' => 'Huawei',
                'description' => 'Smartphone premium Huawei',
                'price' => '1199.00',
                'stock' => 10,
                'color' => 'Argent',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'Sony Xperia 1 VII',
                'brand' => 'Sony',
                'description' => 'Smartphone multimédia Sony',
                'price' => '1299.00',
                'stock' => 9,
                'color' => 'Noir',
                'storage' => '256 Go',
                'screenSize' => 6.5
            ],
            [
                'name' => 'Motorola Edge 60 Ultra',
                'brand' => 'Motorola',
                'description' => 'Smartphone premium Motorola',
                'price' => '899.00',
                'stock' => 17,
                'color' => 'Bleu',
                'storage' => '256 Go',
                'screenSize' => 6.7
            ],
            [
                'name' => 'Nothing Phone 4',
                'brand' => 'Nothing',
                'description' => 'Smartphone design Nothing',
                'price' => '799.00',
                'stock' => 22,
                'color' => 'Blanc',
                'storage' => '256 Go',
                'screenSize' => 6.7
            ],
            [
                'name' => 'Realme GT 8 Pro',
                'brand' => 'Realme',
                'description' => 'Smartphone performant Realme',
                'price' => '749.00',
                'stock' => 24,
                'color' => 'Noir',
                'storage' => '256 Go',
                'screenSize' => 6.8
            ],
            [
                'name' => 'Asus ROG Phone 10',
                'brand' => 'Asus',
                'description' => 'Smartphone gaming',
                'price' => '1399.00',
                'stock' => 7,
                'color' => 'Noir',
                'storage' => '512 Go',
                'screenSize' => 6.8
            ]
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
