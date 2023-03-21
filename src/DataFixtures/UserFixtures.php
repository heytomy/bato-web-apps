<?php

namespace App\DataFixtures;

use Faker\Factory as Faker;
use App\Entity\AppsUtilisateur;
use App\Entity\DefAppsUtilisateur;
use App\Repository\RolesRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    protected $rolesRepository;

    public function __construct(UserPasswordHasherInterface $encoder, RolesRepository $rolesRepository)
    {
        $this->encoder = $encoder;
        $this->rolesRepository = $rolesRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // $client = new DefAppsUtilisateur;
        // $client
        //     ->setNom("Kebsi")
        //     ->setPrenom("Badr")
        //     ->setAdresse("Somewhere in the world")
        //     ->setCP("67000")
        //     ->setVille("Strasbourg")
        //     ->setTel1("0769553504")
        //     ->setMail("kebsibadr@gmail.com")
        //     ;
        // $manager->persist($client);
 
        $roles = $this->rolesRepository->findAll();

        // $user = new AppsUtilisateur;
        // $user
        //     ->setIDUtilisateur($client)
        //     ->setNomUtilisateur('Kebsibadr')
        //     ->setPassword($this->encoder->hashPassword($user, 'admin'))
        //     ->addRole($roles[0])
        //     ->setIsVerified(false)
        //     ;
        // // $product = new Product();
        // $manager->persist($user);

        
        $password = $this->encoder->hashPassword(new AppsUtilisateur, 'password');
        /**
         * Cette boucle génère 50 fausses utilisateurs grâce au package: Faker
         */
        for($i=0; $i<10; $i++) {
            $client = new DefAppsUtilisateur;
            $client
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setAdresse($faker->address())
                ->setCP($faker->postcode())
                ->setVille($faker->city())
                ->setTel1("011223344")
                ->setMail($faker->email())
                ;
            $manager->persist($client);

            $user = new AppsUtilisateur;
            $user
                ->setIDUtilisateur($client)
                ->setNomUtilisateur($faker->userName())
                ->setPassword($password)
                ->addRole($faker->randomElement($roles))
                ->setIsVerified(false)
                
                ;
            $manager->persist($user);
            
            
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            RoleFixtures::class
        ];
    }
}
