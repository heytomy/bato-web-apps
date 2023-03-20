<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory as Faker;
use App\Entity\CommentairesSAV;
use App\Repository\ContratRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentairesFixtures extends Fixture
{
    protected $contratRepository;
    public function __construct(ContratRepository $contratRepository)
    {
        $this->contratRepository = $contratRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $contrats = $this->contratRepository->findAll();

        foreach ($contrats as $contrat) {
            for ($i=0; $i < 5; $i++) { 
                $comment = new CommentairesSAV;
                $comment
                    ->setCommentaireSAV($faker->paragraphs(3, true))
                    ->setCodeContrat($contrat)
                    ->setCodeClient($contrat->getCodeClient()->getId())
                    ->setNom($contrat->getCodeClient()->getNom())
                    ->setDateCom(new DateTime())
                ;
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
}
