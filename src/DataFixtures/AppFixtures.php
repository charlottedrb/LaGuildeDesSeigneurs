<?php

namespace App\DataFixtures;

use App\Entity\Character;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $character = new Character();
            $character
                ->setKind(rand(0 ,1) ? 'Dame' : 'Seigneur')
                ->setName('Eldalote' . $i)
                ->setSurname('Fleur elfique')
                ->setCaste('Elfe')
                ->setKnowledge('Ars')
                ->setIntelligence(rand(100, 200))
                ->setLife(rand(10, 20))
                ->setIdentifier(hash('sha1', uniqid()))
                ->setCreation(new DateTime())
                ->setImage('/images/eldalote.jpg')
            ;

            $manager->persist($character);
        }

        $manager->flush();
    }
}
