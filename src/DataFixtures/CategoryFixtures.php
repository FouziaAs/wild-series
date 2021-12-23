<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Comédie',
        'Fantastique',
        'Historique',
        'Horreur',
        'Thriller',
        'Drame',
        'Comédie dramatique'
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName) {        
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $category->setSeason($this->getReference('categorie_0'));

        }
        $manager->flush();
    }
}
