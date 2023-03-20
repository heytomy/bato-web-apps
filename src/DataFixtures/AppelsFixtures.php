<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Appels;
use App\Entity\AppelsSav;
use App\Entity\TicketUrgents;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

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

        // Generate 100 AppelSav entities
        for ($i = 0; $i < 100; $i++) {
            $appelSav = new AppelsSav();
            $appelSav->setDescription($faker->paragraph);
            $appelSav->setRdvDate($faker->dateTimeThisMonth);
            $appelSav->setRdvHeure($faker->dateTime('H:i:s'));
            // $appelSav->setContrats($faker->regexify('[A-Z]{2}[0-9]{6}'));
            // $appelSav->setClient($faker->regexify('[A-Z]{2}[0-9]{6}'));
            $manager->persist($appelSav);
        }

        // Generate 100 TicketUrgent entities
        for ($i = 0; $i < 100; $i++) {
            $ticketUrgent = new TicketUrgents();
            // $ticketUrgent->setAppelsUrgents($faker->numberBetween(1, 100));
            // $ticketUrgent->setAppelsSAV($faker->numberBetween(1, 100));
            $ticketUrgent->setStatus($faker->numberBetween(1, 3));
            $manager->persist($ticketUrgent);
        }

        $manager->flush();
    }
}
