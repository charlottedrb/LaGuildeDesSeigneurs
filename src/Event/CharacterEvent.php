<?php 

namespace App\Event;

use App\Entity\Character;
use Symfony\Contracts\EventDispatcher\Event;

class CharacterEvent extends Event 
{
    public const CHARACTER_CREATED = 'app.character.created';
    public const CHARACTER_LIFE_20 = 'app.character.life20';

    public function __construct(protected Character $character)
    { }

    public function getCharacter()
    {
        return $this->character;
    }
}