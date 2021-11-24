<?php

namespace App\Listener;

use App\Entity\Character;
use App\Event\CharacterEvent;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CharacterListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            CharacterEvent::CHARACTER_CREATED => "characterCreated",
            CharacterEvent::CHARACTER_LIFE_20 => "characterLife20"
        );
    }

    public function characterCreated($event) 
    {
        $character = $event->getCharacter();
        $character->setIntelligence(250);
    }

    public function characterLife20($event)
    {
        $character = $event->getCharacter();
        $date = new DateTime();
        $minDate = new DateTime("11/22/2021");
        $maxDate = new DateTime("11/30/2021");

        if($date > $minDate && $date < $maxDate) {
            $character->setLife(20);
        }
    }   
}