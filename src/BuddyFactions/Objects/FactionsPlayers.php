<?php

namespace BuddyFactions\Objects;
use BuddyFactions\Objects\FactionsPlayer;
use BuddyFactions\Database\Queries\GetPlayerQuery;
use pocketmine\Player;

class FactionsPlayers {
    /**
     *
     * @var FactionsLevel
     */
    private $factionsLevel;
    /**
     * Players oragnised by Name
     * @var FactionsPlayer[]
     */
    private $cache_byName;
    /**
     * @var DatabaseSettings
     */
    private $databaseSettings;
    /**
     * @var FactionsDatabase
     */
    private $database_ro;
    /**
     *
     * @var FactionsDatabase
     */
    private $database_rw;
    /**
     * @var GetPlayerQuery
     */
    private $getPlayerQuery;
    
    public function __construct(
            FactionsLevel $factionsLevel,
            DatabaseSettings $databaseSettings, 
            FactionsDatabase $database_ro = null, 
            FactionsDatabase $database_rw = null) {
        $this->factionsLevel = $factionsLevel;
        $this->databaseSettings = $databaseSettings;
        $this->database_ro = $database_ro;
        $this->database_rw = $database_rw;
        $this->getPlayerQuery = new GetPlayerQuery($databaseSettings, $database_ro);
    }
    
    /**
     * Get details for a player
     * @param Player|string $player
     */
    public function getPlayer($player) {
        if($player instanceof Player) {
            $playerName = strtolower($player->getName());
        } else {
            $playerName = strtolower($player);
        }
        if( !array_key_exists($playerName, $this->cache_byName) ) {
            $playerObj = $this->getPlayerQuery($playerName);
            $this->cachePlayer($playerObj);
            return $playerObj;
        }
        return $this->cache_byName[$playerName];
    }
    
    /**
     * Handle player left - remove from cache
     * @param Player|string $player
     */
    public function playerLeft($player) {
        if($player instanceof Player) {
            $playerName = strtolower($player->getName());
        } else {
            $playerName = strtolower($player);
        }
        if( array_key_exists($playerName, $this->cache_byName) ) {
            unset($this->cache_byName[$playerName]);
        }
    }
    
    /**
     * Store factionsPlayer in cache
     * @param FactionsPlayer $factionsPlayer
     */
    private function cachePlayer(FactionsPlayer $factionsPlayer) {
        $name = $factionsPlayer->getName();
        $this->cache_byName[$name] = $factionsPlayer;
    }
}