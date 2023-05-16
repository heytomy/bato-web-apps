<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Contrat;
use App\Entity\ClientDef;
use Faker\Factory as Faker;
use App\Entity\CommentairesSAV;
use App\Entity\PhotosSAV;
use App\Entity\RepCommentairesSAV;
use App\Repository\AppsUtilisateurRepository;
use App\Repository\ClientDefRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DemonstrationSAVFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    protected ClientDefRepository $clientDefRepository;
    protected AppsUtilisateurRepository $appsUtilisateurRepository;

    public function __construct(ClientDefRepository $clientDefRepository, AppsUtilisateurRepository $appsUtilisateurRepository)
    {
        $this->clientDefRepository = $clientDefRepository;
        $this->appsUtilisateurRepository = $appsUtilisateurRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        for ($i=0; $i < 500; $i++) { 
            $client = new ClientDef();
            $client
                ->setNom($faker->lastName() + $faker->firstName())
                ->setAdr($faker->address())
                ->setCP($faker->postcode())
                ->setVille($faker->city())
                ->setTel($faker->phoneNumber())
                ->setEMail($faker->email())
            ;

            $manager->persist($client);
        }
        $users = $this->appsUtilisateurRepository->findAll();
        $usersLength = count($users)-1;

        $clients = $this->clientDefRepository->findAll();
        $clientsLength = count($clients)-1;

        $labels = ['Gaz condensation', 'Fioul condensation', 'Fioul', 'Système solaire', 'Granulés', 'Gaz sol', 'Gaz murale 3x + gaz cond 1x', 'PAC'];
        for ($i=0; $i < 100; $i++) { 

            $randomKey = rand(0, $clientsLength);
            $client = $clients[$randomKey];

            $sav = new Contrat();
            $sav
                ->setNote($faker->sentence())
                ->setCodeClient($client)
                ->setLibelle($faker->randomElement($labels))
                ;

            $manager->persist($sav);

            for ($i=0; $i < 2; $i++) { 

                $randomUserKey = rand(0, $usersLength);
                $user = $users[$randomUserKey];

                $comment = new CommentairesSAV;
                $comment
                    ->setContenu($faker->paragraphs(3, true))
                    ->setCodeContrat($sav)
                    ->setCodeClient($sav->getCodeClient()->getId())
                    ->setNom($sav->getCodeClient()->getNom())
                    ->setDateCom(new DateTime())
                    ->setOwner($user->getIDUtilisateur())
                ;
                $manager->persist($comment);

                $reply = new RepCommentairesSAV;
                $reply
                    ->setParent($comment)
                    ->setNom("Kebsi badr")
                    ->setDateCom(new DateTime())
                    ->setCodeClient($sav->getCodeClient()->getId())
                    ->setOwner($user->getIDUtilisateur())
                    ->setContenu($faker->paragraphs(3, true))
                    ;
                $manager->persist($reply);

                $photo = new PhotosSAV;
                $photo
                    ->setURLPhoto('https://picsum.photos/100'+$i)
                    ->setDatePhoto(new DateTime())
                    ->setCodeClient($client->getId())
                    ->setNom('photo'+$i)
                    ->setCode($sav)
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
        ];
    }

}
