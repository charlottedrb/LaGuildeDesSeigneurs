<?php

namespace App\Services;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayerService implements PlayerServiceInterface
{
    private $playerRepository;
    private $em;

    public function __construct(
        PlayerRepository $playerRepository,
        EntityManagerInterface $em
    )
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $playersFinal = [];
        $players = $this->playerRepository->findAll();
        
        foreach ($players as $player) {
            $playersFinal[] = $player->toArray();
        }

        return $playersFinal;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $player = new Player(); 
        $player
            ->setFirstname('Mathilde')
            ->setLastname('Dutrieux')
            ->setEmail('mathilde.dutrieux@gmail.com')
            ->setMirian('10000000')
            ->setPseudo('mathou')
            ->setCreation(new \DateTime())
            ->setModification(new \DateTime())
            ->setIdentifier(hash('sha1', uniqid()))
        ;
        
        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Player $player)
    {
        $player
            ->setFirstname('Charlotte')
            ->setLastname('Der Baghdassarian')
            ->setEmail('c.derbaghdassarian@gmail.com')
            ->setMirian('10000000')
            ->setPseudo('charlottedrb')
            ->setModification(new \DateTime())
        ;
        
        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Player $player)
    {        
        $this->em->remove($player);

        return $this->em->flush();
    }
}
