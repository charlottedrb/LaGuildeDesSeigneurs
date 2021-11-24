<?php

namespace App\Listener;

use App\Entity\Player;
use App\Event\PlayerEvent;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PlayerListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            PlayerEvent::PLAYER_MODIFIED => "playerModified"
        );
    }

    public function playerModified($event) 
    {
        $player = $event->getPlayer();
        $mirian = $player->getMirian() - 10;
        $player->setMirian($mirian);
    }
}