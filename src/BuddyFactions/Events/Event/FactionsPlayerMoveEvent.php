<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use BuddyFactions\Objects\Coordinates;
use pocketmine\Player;
use pocketmine\event\player\PlayerMoveEvent;

class FactionsPlayerMoveEvent extends FactionsEvent {
    /**
     * var string $factionsLevelName
     */
    private $factionsLevelName;
    
    /**
     * World border co-ordinates
     * @var int
     */
    private $worldLimit_minX, $worldLimit_maxX, $worldLimit_minZ, $worldLimit_maxZ;
    
    /**
     * @param BuddyFactions $plugin
     */
    public function __construct(BuddyFactions $plugin) {
        parent::_construct($plugin);
        
        // optimisation, store copy of level name and pre-calculate world borders
        $this->factionsLevelName = $this->factionsLevel->getLevelName();
        $upperCorner = new Coordinates();
        $upperCorner->setFromChunkCoords(
                $this->factionsLevel->getSettings()->levelSize,
                $this->factionsLevel->getSettings()->levelSize);
        $lowerCorner = new Coordinates();
        $lowerCorner->setFromChunkCoords(
                0 - $this->factionsLevel->getSettings()->levelSize,
                0 - $this->factionsLevel->getSettings()->levelSize);
        $this->worldLimit_maxX = floor($upperCorner->getRawX());
        $this->worldLimit_minX = floor($lowerCorner->getRawX());
        $this->worldLimit_maxZ = floor($upperCorner->getRawZ());
        $this->worldLimit_minZ = floor($lowerCorner->getRawZ());
    }
    
    /**
     * Stops players moving outside level boundary
     * @param PlayerMoveEvent $event
     */
    public function onPlayerMove(PlayerMoveEvent $event) {
        // deal only with factions level
        $levelName = $player->getLevel()->getName();
        if($levelName != $this->factionsLevelName) {
            return;
        }
        
        // determine if in boundary
        $positionX = $player->getPosition()->getFloorX();
        $positionZ = $player->getPosition()->getFloorZ();
        $underMaxX = ( $positionX <= $this->worldLimit_maxX );
        $overMinX = ( $positionX >= $this->worldLimit_minX );
        $underMaxZ = ( $positionZ <= $this->worldLimit_maxZ );
        $overMinZ = ( $positionZ >= $this->worldLimit_minZ );
        $inBoundary = $overMinX && $overMinZ && $underMaxX && $underMaxZ;
        if($inBoundary) {
            return;
        }
        
        // outside boundary so cancel movement and notify
        $player = $event->getPlayer();
	$event->setCancelled(true);
        $msg = "Sorry this is the edge of the world - you cannot go further";
        $player->sendMessage($msg);
        
    }
}

 
