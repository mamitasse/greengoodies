<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData = [
            [
                'name' => 'Kit d’hygiène recyclable',
                'shortDescription' => 'Pour une salle de bain éco-friendly.',
                'fullDescription' => 'Un kit complet pour une routine d’hygiène plus durable, avec des accessoires réutilisables et responsables.',
                'price' => 24.90,
                'picture' => 'kit-hygiene.jpg',
            ],
            [
                'name' => 'Shot Tropical',
                'shortDescription' => 'Fruit frais, vitalité à boire.',
                'fullDescription' => 'Une boisson tonique inspirée des saveurs tropicales, idéale pour une pause fraîche et naturelle.',
                'price' => 4.50,
                'picture' => 'shot-tropical.jpg',
            ],
            [
                'name' => 'Gourde en bois',
                'shortDescription' => 'Solide, utile et nature.',
                'fullDescription' => 'Une gourde élégante et pratique, pensée pour réduire l’usage du plastique au quotidien.',
                'price' => 18.90,
                'picture' => 'gourde.jpg',
            ],
            [
                'name' => 'Disques démaquillants x3',
                'shortDescription' => 'Solution efficace, sans compromis en douceur.',
                'fullDescription' => 'Des disques lavables et réutilisables pour remplacer les cotons jetables dans votre routine beauté.',
                'price' => 7.90,
                'picture' => 'disques.jpg',
            ],
            [
                'name' => 'Bougie Lavande & Patchouli',
                'shortDescription' => 'Une chaleur naturelle.',
                'fullDescription' => 'Une bougie aux notes apaisantes, fabriquée avec soin pour créer une ambiance douce et responsable.',
                'price' => 12.00,
                'picture' => 'bougie.jpg',
            ],
            [
                'name' => 'Brosse à dent',
                'shortDescription' => 'Bois de hêtre rouge de type épicé naturellement.',
                'fullDescription' => 'Une brosse à dents écologique conçue à partir de matériaux plus respectueux de l’environnement.',
                'price' => 5.40,
                'picture' => 'brosse-dent.jpg',
            ],
            [
                'name' => 'Kit couvert en bois',
                'shortDescription' => 'Fourchette, kit de table en bois à emporter.',
                'fullDescription' => 'Un kit pratique pour vos repas à l’extérieur, afin d’éviter les couverts jetables.',
                'price' => 12.90,
                'picture' => 'kit-couvert.jpg',
            ],
            [
                'name' => 'Nécessaire, déodorant Bio',
                'shortDescription' => 'Soin déodorant à l’extrait Bio.',
                'fullDescription' => 'Un déodorant naturel au format pratique, pensé pour le confort quotidien et une composition plus propre.',
                'price' => 8.90,
                'picture' => 'necessaire.jpg',
            ],
            [
                'name' => 'Savon Bio',
                'shortDescription' => 'THé Orange & Oudrée.',
                'fullDescription' => 'Un savon doux aux senteurs délicates, formulé pour respecter la peau et l’environnement.',
                'price' => 10.90,
                'picture' => 'savon-bio.jpg',
            ],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setShortDescription($data['shortDescription']);
            $product->setFullDescription($data['fullDescription']);
            $product->setPrice($data['price']);
            $product->setPicture($data['picture']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}