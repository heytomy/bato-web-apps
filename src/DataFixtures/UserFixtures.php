<?php

namespace App\DataFixtures;

use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as Faker;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $client = new DefAppsUtilisateur;
        $client
            ->setNom("Kebsi")
            ->setPrenom("Badr")
            ->setAdresse("Somewhere in the world")
            ->setCP("67000")
            ->setVille("Strasbourg")
            ->setTel1("0769553504")
            ->setMail("kebsibadr@gmail.com")
            ;
        $manager->persist($client);


        $user = new AppsUtilisateur;
        $user
            ->setIDUtilisateur($client)
            ->setNomUtilisateur('Kebsibadr')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ;
        // $product = new Product();
        $manager->persist($user);
        $manager->flush();
    }
}
