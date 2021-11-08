<?php 

namespace App\Services;

interface CharacterServiceInterface
{
    /**
     * Creates the character.
     */
    public function create();

    /**
     * Gets all the character.
     */
    public function getAll();
}