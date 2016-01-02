<?php

namespace BuddyFactions\Objects;
use pocketmine\Player;
use pocketmine\block\Block;

class Coordinates {
    /**
     * Raw World Co-Ordinates
     * @var float 
     */
    private $X, $Z;
    
    /**
     * Set co-ordinates using raw world co-ordinates
     * @param float $X
     * @param float $Z
     */
    public function setFromRawCoords(float $X, float $Z) {
        $this->X = $X;
        $this->Z = $Z;
    }
    
    /**
     * Set co-ordinates using chunk co-ordinates
     * @param int $X
     * @param int $Z
     */
    public function setFromChunkCoords(int $X, int $Z) {
        $this->X = $X << 4;
        $this->Z = $Z << 4;
    }
    
    /**
     * Set co-ordinates using a player object
     * @param Player $player
     */
    public function setFromPlayer(Player $player) {
        $this->X = $player->getX();
        $this->Z = $player->getZ();
    }
    
    /**
     * Set co-ordinates using a block object
     * @param Block $block
     */
    public function setFromBlock(Block $block) {
        $this->X = $block->x;
        $this->Z = $block->z;
    }
    
    /**
     * Get the chunk for the co-ordinates
     * @return int
     */
    public function getChunkX() {
        return floor($this->X) >> 4;
    }
    
    /**
     * Get the chunk for the co-ordinates
     * @return int
     */
    public function getChunkZ() {
        return floor($this->Z) >> 4;
    }
    
    /**
     * Get the raw/world x co-ordinate
     * @return float
     */
    public function getRawX() {
        return $this->X;
    }
    
    /**
     * Get the raw/world z co-ordinate
     * @return float
     */
    public function getRawZ() {
        return $this->Z;
    }
}