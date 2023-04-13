<?php

namespace App\DataFixtures;

use Faker\Factory as Faker;
use App\Entity\ChantierApps;
use App\Repository\ClientDefRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\StatutChantierRepository;
use Symfony\Component\Validator\Constraints\Date;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ChantierFixtures extends Fixture implements FixtureGroupInterface
{
    protected $clientDefRepository;
    protected $statutChantierRepository;

    public function __construct(ClientDefRepository $clientDefRepository, StatutChantierRepository $statutChantierRepository)
    {
        $this->clientDefRepository = $clientDefRepository;
        $this->statutChantierRepository = $statutChantierRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $clients = $this->clientDefRepository->findAll();
        $statuts = $this->statutChantierRepository->findAll();

        foreach ($clients as $client) {
            $chantier = new ChantierApps;
            $dateDebut = \DateTime::createFromFormat('d/m/Y', $faker->date('d/m/Y'));
            $dateFin = \DateTime::createFromFormat('d/m/Y', $faker->date('d/m/Y'));

            $chantier
                ->setLibelle($faker->word())
                ->setDescription($faker->sentences(3, true))
                ->setDateDebut($dateDebut)
                ->setDateFin($dateFin)
                ->setAdresse($faker->address())
                ->setCp($faker->postcode())
                ->setVille($faker->city())
                ->setStatut($faker->randomElement($statuts))
                ->setCodeClient($client)
                ;
            $manager->persist($chantier);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['chantier'];
    }
}
