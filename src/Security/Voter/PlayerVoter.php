<?php

namespace App\Security\Voter;

use LogicException;
use App\Entity\Player;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PlayerVoter extends Voter
{
    public const PLAYER_DISPLAY = 'playerDisplay';
    public const PLAYER_CREATE = 'playerCreate';
    public const PLAYER_INDEX = 'playerIndex';
    public const PLAYER_MODIFY = 'playerModify';
    public const PLAYER_DELETE = 'playerDelete';

    private const ATTRIBUTES =  array(
        self::PLAYER_DISPLAY,
        self::PLAYER_CREATE,
        self::PLAYER_INDEX,
        self::PLAYER_MODIFY,
        self::PLAYER_DELETE,
    );

    protected function supports(string $attribute, $subject): bool
    {
        if(null !== $subject) return $subject instanceof Player && in_array($attribute, self::ATTRIBUTES);

        return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case self::PLAYER_DISPLAY: 
                return $this->canDisplay();
                break;
            
            case self::PLAYER_CREATE: 
                return $this->canCreate();
                break;

            case self::PLAYER_INDEX: 
                return $this->canIndex();
                break;

            case self::PLAYER_MODIFY: 
                return $this->canModify();
                break;
                
            case self::PLAYER_DELETE: 
                return $this->canDelete();
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

    /**
     * Checks if is allowed to modify
     *
     * @return boolean
     */
    private function  canModify()
    {
        return true;
    }

    /**
     * Checks if is allowed to delete
     *
     * @return boolean
     */
    private function  canDelete()
    {
        return true;
    }
}
