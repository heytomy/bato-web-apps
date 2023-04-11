<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory as Faker;
use App\Entity\CommentairesSAV;
use App\Entity\RepCommentairesSAV;
use App\Repository\ContratRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\AppsUtilisateurRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentairesFixtures extends Fixture implements DependentFixtureInterface
{
    protected $contratRepository;
    protected $appsUtilisateurRepository;
    public function __construct(ContratRepository $contratRepository, AppsUtilisateurRepository $appsUtilisateurRepository)
    {
        $this->contratRepository = $contratRepository;
        $this->appsUtilisateurRepository = $appsUtilisateurRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $contrats = $this->contratRepository->findAll();
        $user = $this->appsUtilisateurRepository->findOneBy(['Nom_utilisateur' => 'Kebsibadr']);

        foreach ($contrats as $contrat) {
            for ($i=0; $i < 2; $i++) { 
                $comment = new CommentairesSAV;
                $comment
                    ->setContenu($faker->paragraphs(3, true))
                    ->setCodeContrat($contrat)
                    ->setCodeClient($contrat->getCodeClient()->getId())
                    ->setNom($contrat->getCodeClient()->getNom())
                    ->setDateCom(new DateTime())
                    ->setOwner($user->getIDUtilisateur())
                ;
                $manager->persist($comment);

                $reply = new RepCommentairesSAV;
                $reply
                    ->setParent($comment)
                    ->setNom("Kebsi badr")
                    ->setDateCom(new DateTime())
                    ->setCodeClient($contrat->getCodeClient()->getId())
                    ->setOwner($user->getIDUtilisateur())
                    ->setContenu($faker->paragraphs(3, true))
                    ;
                $manager->persist($reply);
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
