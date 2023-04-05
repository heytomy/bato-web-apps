<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory as Faker;
use App\Entity\CommentairesAppels;
use App\Entity\RepCommentairesAppels;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\AppsUtilisateurRepository;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CommentairesAppelsFixtures extends Fixture implements FixtureGroupInterface
{
    protected $appsUtilisateurRepository;
    public function __construct(AppsUtilisateurRepository $appsUtilisateurRepository)
    {
        $this->appsUtilisateurRepository = $appsUtilisateurRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $user = $this->appsUtilisateurRepository->findOneBy(['Nom_utilisateur' => 'Kebsibadr']);
        
        for ($i=0; $i < 2; $i++) { 
            $comment = new CommentairesAppels;
            $comment
                ->setContenu($faker->paragraphs(3, true))
                ->setCodeAppels($faker->randomNumber(3, false))
                ->setDateCom(new DateTime())
                ->setOwner($user->getIDUtilisateur())
            ;
            $manager->persist($comment);

            $reply = new RepCommentairesAppels;
            $reply
                ->setParent($comment)
                ->setNom("Kebsi badr")
                ->setContenu($faker->paragraphs(3, true))
                ->setDateCom(new DateTime())
                ->setOwner($user->getIDUtilisateur())
                ;
            $manager->persist($reply);
        }
        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['group2'];
    }
}
