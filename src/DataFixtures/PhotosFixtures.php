<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\PhotosSAV;
use Faker\Factory as Faker;
use App\Entity\PhotosAppels;
use App\Repository\AppelsRepository;
use App\Repository\ContratRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class PhotosFixtures extends Fixture implements FixtureGroupInterface
{
    protected $appelsRepository;
    protected $contratRepository;
    public function __construct(AppelsRepository $appelsRepository, ContratRepository $contratRepository)
    {
        $this->appelsRepository = $appelsRepository;
        $this->contratRepository = $contratRepository;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $appels = $this->appelsRepository->findAll();
        $contrats = $this->contratRepository->findAll();

        foreach ($appels as $appel) {
            for ($i=0; $i < 5; $i++) { 
                $photoAppel = new PhotosAppels;
                $photoAppel
                    ->setURLPhoto("https://picsum.photos/600")
                    ->setDatePhoto(new DateTime())
                    ->setIdAppel($appel)
                    ;
                dd($photoAppel);
                $manager->persist($photoAppel);
            }
        }

        // foreach ($contrats as $contrat) {
        //     for ($i=0; $i < 5; $i++) { 
        //         $photoSAV = new PhotosSAV;
        //         $photoSAV
        //             ->setId((string)$faker->unique()->randomNumber(8))
        //             ->setURLPhoto("https://picsum.photos/600")
        //             ->setDatePhoto(new DateTime())
        //             ->setNom("KebsiBadr")
        //             ->setCode($contrat)
        //             ->setCodeClient($contrat->getCodeClient()->getId())
        //             ;
        //         $manager->persist($photoSAV);
        //     }
        // }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group5'];
    }
}
