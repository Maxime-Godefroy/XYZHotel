<?php

namespace App\DataFixtures;

use App\Entity\Chambres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $chambre1 = new Chambres();
        $chambre1->setCategorie('Standard');
        $chambre1->setPrixNuit('50.00');
        $chambre1->setCapacite(1);
        $chambre1->setCaracteristiques('Lit 1 place, Wifi, TV');
        $manager->persist($chambre1);

        $chambre2 = new Chambres();
        $chambre2->setCategorie('Supérieure');
        $chambre2->setPrixNuit('100.00');
        $chambre2->setCapacite(2);
        $chambre2->setCaracteristiques('Lit 2 places, Wifi, TV écran plat, Minibar, Climatiseur');
        $manager->persist($chambre2);

        $chambre3 = new Chambres();
        $chambre3->setCategorie('Suite');
        $chambre3->setPrixNuit('200.00');
        $chambre3->setCapacite(2);
        $chambre3->setCaracteristiques('Lit 2 places, Wifi, TV écran plat, Minibar, Climatiseur, Baignoire, Terrasse');
        $manager->persist($chambre3);

        $manager->flush();
    }
}
