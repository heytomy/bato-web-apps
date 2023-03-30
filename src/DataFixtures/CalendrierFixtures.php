<?php

namespace App\DataFixtures;

use App\Entity\Calendrier;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CalendrierFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        for ($i=0; $i < 500; $i++) { 
            $booking = new Calendrier;
            $booking
                ->setTitre($faker->word())
                ->setDateDebut($faker->dateTimeInInterval('-2 months', '+2 months'))
                ;
            $manager->persist($booking);
        }
        

        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['group1', 'group2'];
    }
}
