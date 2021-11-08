<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Player;
use App\Entity\Character;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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
                ->setModification(new DateTime())
                ->setImage('/images/eldalote.jpg')
            ;

            $manager->persist($character);
        }

        for ($i=0; $i < 3; $i++) { 
            $player = new Player();
            $player
            ->setFirstname('Charlotte' . $i)
            ->setLastname('Der Baghdassarian')
            ->setEmail('c.derbaghdassarian@gmail.com')
            ->setMirian('10000000')
            ->setPseudo('chachou' . $i)
            ->setCreation(new \DateTime())
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
        ;

            $manager->persist($player);
        }

        $manager->flush();
    }
}
