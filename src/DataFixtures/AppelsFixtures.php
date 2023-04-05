<?php

namespace App\DataFixtures;

use App\Entity\Appels;
use App\Entity\Calendrier;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\DefAppsUtilisateurRepository;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppelsFixtures extends Fixture implements FixtureGroupInterface
{
    protected $defAppsUtilisateurRepository;
    public function __construct(DefAppsUtilisateurRepository $defAppsUtilisateurRepository)
    {
        $this->defAppsUtilisateurRepository = $defAppsUtilisateurRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $user = $this->defAppsUtilisateurRepository->findOneBy(['Nom' => 'Kebsi']);

        // Generate 100 Appel entities
        for ($i = 0; $i < 50; $i++) {
            $appel = new Appels();
            $appel
                ->setIDUtilisateur($user)
                ->setNom('badr')
                ->setAdr('somewhere')
                ->setCp('67000')
                ->setVille('strasbourg')
                ->setTel('123456789')
                ->setEmail('kjieam@gmail.com')
                ->setDescription('This is a description')
                ->setIsUrgent($faker->boolean)
                ;
            
            $dateDebut = $faker->dateTimeInInterval('-2 months', '+2 months');
            $dateFin = clone $dateDebut;
            $dateFin->modify('+1 hour');

            $rdv = new Calendrier();
            $rdv
                ->setTitre($faker->word())
                ->setDateDebut($dateDebut)
                ->setDateFin($dateFin)
                ->setAllDay($faker->boolean(50))
                ;
            $appel->setRdv($rdv);

            $manager->persist($appel);
            $manager->persist($rdv);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group3'];
    }
}