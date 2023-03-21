<?php

namespace App\DataFixtures;

use Faker\Factory as Faker;

use App\Entity\Appels;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) 
        {
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
    }
}