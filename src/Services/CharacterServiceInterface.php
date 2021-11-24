<?php

namespace App\Services;

use App\Entity\Character;

interface CharacterServiceInterface
{
    /**
     * Creates the character.
     */
    public function create(string $data);

    /**
    * Checks if the entity has been well filled
    */
    public function isEntityFilled(Character $character);

    /**
     * Submits the data to hydrate object
     */
    public function submit(Character $character, $form, $data);

    /**
     * Gets all the character.
     */
    public function getAll();

    /**
     * Modifies the character.
     */
    public function modify(Character $character, string $data);

    /**
     * Deletes the character.
     */
    public function delete(Character $character);

    /**
     * Return random character images.
     */
    public function getImages(int $number);

    /**
     * Serialize data to JSON
     */
    public function serializeJson(mixed $data);

    /**
     * Create a Character from HTML
     */
    public function createFromHtml(Character $character);

     /**
     * Modify a Character from HTML
     */
    public function modifyFromHtml(Character $character);

    /**
     * Get all Characters by their intelligence level
     */
    public function getAllByIntelligenceLevel(int $level);
}
