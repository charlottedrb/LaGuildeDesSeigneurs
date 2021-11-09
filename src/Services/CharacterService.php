<?php

namespace App\Services;

use App\Entity\Character;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Finder;
use App\Repository\CharacterRepository;
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
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        
        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Character $character)
    {
        $character
            ->setKind('Seigneur')
            ->setName('Gorthol')
            ->setSurname('Haume de terreur')
            ->setCaste('Chevalier')
            ->setKnowledge('Diplomatie')
            ->setIntelligence(110)
            ->setLife(13)
            ->setModification(new \DateTime())
            ->setImage('/images/gorthol.jpg')
        ;
        
        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Character $character)
    {        
        $this->em->remove($character);  

        return $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getImages(int $number)
    {
        $folder = __DIR__ . '/../../public/images';

        $finder = new Finder();
        $finder 
            ->files()
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName()
        ;

        $images = [];
        foreach ($finder as $file) {
            $images[] = '/images/' . $file->getRelativePathname();
        }
        shuffle($images);

        return array_slice($images, 0, $number, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getImagesByKind(string $kind, int $number)
    {
        $folder = __DIR__ . '/../../public/images' . $kind;

        $finder = new Finder();
        $finder 
            ->files()
            ->in($folder)
            ->notPath('/cartes/')
            ->sortByName()
        ;

        $images = [];
        foreach ($finder as $file) {
            $images[] = '/images/' . $kind . '/' . $file->getRelativePathname();
        }
        shuffle($images);

        return array_slice($images, 0, $number, true);
    }
        
}
