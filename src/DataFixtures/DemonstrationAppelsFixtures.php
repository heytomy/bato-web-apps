<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Appels;
use App\Entity\Calendrier;
use Faker\Factory as Faker;
use App\Entity\CommentairesAppels;
use App\Entity\PhotosAppels;
use App\Entity\RepCommentairesAppels;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\AppsUtilisateurRepository;
use App\Repository\DefAppsUtilisateurRepository;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DemonstrationAppelsFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    protected $defAppsUtilisateurRepository;
    protected AppsUtilisateurRepository $appsUtilisateurRepository;

    public function __construct(DefAppsUtilisateurRepository $defAppsUtilisateurRepository, AppsUtilisateurRepository $appsUtilisateurRepository)
    {
        $this->defAppsUtilisateurRepository = $defAppsUtilisateurRepository;
        $this->appsUtilisateurRepository = $appsUtilisateurRepository;
    }


    // TODO: I need to add the proper properties for Appels and Calendrier
    public function load(ObjectManager $manager): void
    {
        $users = $this->appsUtilisateurRepository->findAll();
        $usersLength = count($users)-1;

        $faker = Faker::create('fr_FR');

        // Generate 100 Appel entities
        for ($i = 0; $i < 100; $i++) {
            $randomUserKey = rand(0, $usersLength);
            $user = $users[$randomUserKey];


            $dateDebut = $faker->dateTimeInInterval('-5 months', '+5 months');
            $dateFin = clone $dateDebut;
            $dateFin->modify('+1 hour');

            $appel = new Appels();
            $appel
                ->setIDUtilisateur($user)
                ->setNom($faker->name())
                ->setAdr($faker->address())
                ->setCp($faker->postcode())
                ->setVille($faker->city())
                ->setTel('123456789')
                ->setEmail($faker->email())
                ->setDescription($faker->sentences(3, false))
                ->setIsUrgent($faker->boolean(20))
                ->setDateDebut($dateDebut)
                ->setDateFin($dateFin)
                ;

            $rdv = new Calendrier();
            $rdv
                ->setTitre($faker->word())
                ->setDateDebut($dateDebut)
                ->setDateFin($dateFin)
                ->setAllDay($faker->boolean(10))
                ;
            $appel->setRdv($rdv);

            $manager->persist($appel);
            $manager->persist($rdv);

            for ($i=0; $i < 2; $i++) { 
                $comment = new CommentairesAppels;
                $comment
                    ->setNom($user->getIDUtilisateur()->getNom())
                    ->setContenu($faker->paragraphs(3, true))
                    ->setCodeAppels($appel)
                    ->setDateCom(new DateTime())
                    ->setOwner($user->getIDUtilisateur())
                ;
                $manager->persist($comment);

                $reply = new RepCommentairesAppels;
                $reply
                    ->setParent($comment)
                    ->setNom($user->getIDUtilisateur()->getNom())
                    ->setContenu($faker->paragraphs(3, true))
                    ->setDateCom(new DateTime())
                    ->setOwner($user->getIDUtilisateur())
                    ;
                $manager->persist($reply);

                $photo = new PhotosAppels;
                $photo
                    ->setURLPhoto('https://picsum.photos/100'+$i)
                    ->setDatePhoto(new DateTime())
                    ->setIdAppel($appel)
                    ;
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo_group'];
    }

    public function getDependencies(): array
    {
        return [
            DemonstrationFixtures::class,
            DemonstrationSAVFixtures::class,
        ];
    }
}
