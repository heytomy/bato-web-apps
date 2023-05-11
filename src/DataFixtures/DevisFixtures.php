<?php

namespace App\DataFixtures;

use App\Entity\DevisARealiser;
use App\Repository\StatutChantierRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker\Factory as Faker;

class DevisFixtures extends Fixture implements FixtureGroupInterface
{
    private $statutChantierRepository;
    public function __construct(StatutChantierRepository $statutChantierRepository)
    {
        $this->statutChantierRepository = $statutChantierRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $enCoursStatut = $this->statutChantierRepository->findOneBy(['statut' => 'EN_COURS']);

        $faker = Faker::create('fr_FR');
        
        for ($i=0; $i < 50; $i++) { 
            $devis = new DevisARealiser();
            $devis
                ->setAdr($faker->address())
                ->setNom($faker->name())
                ->setDate(new \DateTime())
                ->setMail($faker->email())
                ->setTel('0123456789')
                ->setStatut($enCoursStatut)
                ->setDescription($faker->sentences(2, true))
                ;
            $manager->persist($devis);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['devisGroup'];
    }
}
