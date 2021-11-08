<?php

namespace App\Services;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService implements CharacterServiceInterface
{
    private $characterRepository;
    private $em;

    public function __construct(
        CharacterRepository $characterRepository,
        EntityManagerInterface $em
    )
    {
        $this->characterRepository = $characterRepository;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $charactersFinal = [];
        $characters = $this->characterRepository->findAll();
        
        foreach ($characters as $character) {
            $charactersFinal[] = $character->toArray();
        }

        return $charactersFinal;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $character = new Character(); 
        $character
            ->setKind('Dame')
            ->setName('Eldalote')
            ->setSurname('Fleur elfique')
            ->setCaste('Elfe')
            ->setKnowledge('Arts')
            ->setIntelligence(120)
            ->setLife(12)
            ->setImage('/images/eldalote.jpg')
            ->setCreation(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        
        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }
}
