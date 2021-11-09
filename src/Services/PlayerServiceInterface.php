<?php 

namespace App\Services;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates the player.
     */
    public function create(string $data);


    public function isEntityFilled(Player $player);

    public function submit(Player $player, $formName, $data);


    /**
     * Gets all the player.
     */
    public function getAll();

    /**
     * Modifies the player.
     */
    public function modify(Player $player, string $data);

    /**
     * Deletes the player.
     */
    public function delete(Player $player);
}