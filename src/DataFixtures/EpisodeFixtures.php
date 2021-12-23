<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        '1',
        '2',
        '3',
        '4',
        '5',
    ];
    
    private Slugify $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $episodeNumber) {        
            $episode = new Episode();
            $episode->setNumber($episodeNumber);
            $episode->setTitle('Un super épisode');
            $episode->setSlug($this->slugify->generate($episode->getTitle()));
            $episode->setSynopsis("Ceci est le descriptif de $episodeNumber");
            $manager->persist($episode);
            $episode->setSeason($this->getReference('season_0'));
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
