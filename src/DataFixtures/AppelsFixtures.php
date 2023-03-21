<?php

namespace App\DataFixtures;

use App\Entity\Appels;
use App\Entity\AppelsSav;
use App\Entity\TicketUrgents;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory as Faker;

class AppelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        // Generate 100 Appel entities
        for ($i = 0; $i < 100; $i++) {
            $appel = new Appels();
            $appel->setNom($faker->name);
            $appel->setAdr($faker->address);
            $appel->setCp($faker->postcode);
            $appel->setVille($faker->city);
            $appel->setTel($faker->phoneNumber);
            $appel->setEmail($faker->email);
            $appel->setDescription($faker->paragraph);
            $appel->setRdvDate($faker->dateTimeThisMonth);
            $appel->setRdvHeure($faker->dateTimeThisMonth);
            $appel->setIsUrgent($faker->boolean);

            $manager->persist($appel);
        }
        $manager->flush();
    }
}