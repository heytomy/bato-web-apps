<?php

namespace App\DataFixtures;

use App\Entity\StatutChantier;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ChantierStatusFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $role = new StatutChantier;
        $role->setStatut("identity diffusion");
        $manager->persist($role);

        $role = new StatutChantier;
        $role->setStatut("identity foreclosure");
        $manager->persist($role);

        $role = new StatutChantier;
        $role->setStatut("moratorium");
        $manager->persist($role);

        $role = new StatutChantier;
        $role->setStatut("identity achievement");
        $manager->persist($role);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['statut'];
    }
}
