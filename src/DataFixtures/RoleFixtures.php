<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Roles;
        $role->setLibelle("ROLE_ADMIN");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_GESTION");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_TECH_SAV");
        $manager->persist($role);

        $role = new Roles;
        $role->setLibelle("ROLE_TECH_CHANTIER");
        $manager->persist($role);

        $manager->flush();
    }
}
