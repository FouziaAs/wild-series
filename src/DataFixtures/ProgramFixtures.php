<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $program = new Program();
        $program->setTitle('Walking dead');
        $program->setSynopsis('Des zombies envahissent la terre');
        $program->setCategory($this->getReference('category_0'));
        $program->setYear('2010');
        $program->setCountry('USA');
        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
        for ($i=0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $manager->persist($program);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }


}