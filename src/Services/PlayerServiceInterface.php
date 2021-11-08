<?php 

namespace App\Services;

use App\Entity\Player;

interface PlayerServiceInterface
{
    /**
     * Creates the player.
     */
    public function create();

    /**
     * Gets all the player.
     */
    public function getAll();

    /**
     * Modifies the player.
     */
    public function modify(Player $player);

    /**
     * Deletes the player.
     */
    public function delete(Player $player);
}