<?php

namespace App\Security\Voter;

use App\Entity\Character;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterVoter extends Voter
{
    public const CHARACTER_DISPLAY = 'characterDisplay';
    public const CHARACTER_CREATE = 'characterCreate';
    public const CHARACTER_INDEX = 'characterIndex';

    private const ATTRIBUTES =  array(
        self::CHARACTER_DISPLAY,
        self::CHARACTER_CREATE,
        self::CHARACTER_INDEX,
    );

    protected function supports(string $attribute, $subject): bool
    {
        if(null !== $subject) return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case self::CHARACTER_DISPLAY: 
                return $this->canDisplay();
                break;
            
            case self::CHARACTER_CREATE: 
                return $this->canCreate();
                break;

            case self::CHARACTER_INDEX: 
                return $this->canIndex();
                break;
        }

        throw new LogicException('Invalid attribute: ' . $attribute);

        return false;
    }

    /**
     * Checks if is allowed to display
     *
     * @return boolean
     */
    private function canDisplay()
    {
        return true;
    }

    /**
     * Checks if is allowed to create
     *
     * @return boolean
     */
    private function  canCreate()
    {
        return true;
    }

    /**
     * Checks if is allowed to index
     *
     * @return boolean
     */
    private function  canIndex()
    {
        return true;
    }
}
