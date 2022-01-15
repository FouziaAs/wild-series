<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\Slugify;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        'La Casa de Papel',
        'Prison break',
        'Le jeu de la dame ',
        'Outlander',
        'Wheel time'
    ];

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $programTitle){
        $program = new Program();
        $program->setTitle($programTitle);
        $program->setSynopsis('Ceci est le synopsis de la série.');
        $program->setCategory($this->getReference('category_0'));
        $program->setYear('2010');
        $program->setCountry('USA');
        $program->setSlug($this->slugify->generate($program->getTitle()));
        $program->addActor($this->getReference('actor_0'));
        $this->addReference('program_' . $key, $program);
        $program->setOwner($this->getReference('contributor'));
        $manager->persist($program);
    }
        $manager->flush();
}

    public function getDependencies()
    {
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
          UserFixtures::class,
        ];
    }
}
