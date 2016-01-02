<?php

namespace BuddyFactions\Objects;
use BuddyFactions\Objects\Faction;

class FactionsFactions {
    /**
     * Manages the database of Factions
     * @param \BuddyFactions\Objects\FactionsLevel $factionsLevel
     * @param \BuddyFactions\Objects\DatabaseSettings $databaseSettings
     * @param \BuddyFactions\Objects\FactionsDatabase $database_ro
     * @param \BuddyFactions\Objects\FactionsDatabase $database_rw
     */
    public function __construct(
            FactionsLevel $factionsLevel,
            DatabaseSettings $databaseSettings, 
            FactionsDatabase $database_ro = null, 
            FactionsDatabase $database_rw = null) {
        $this->factionsLevel = $factionsLevel;
        $this->levelName = $factionsLevel->getLevelName();
        $this->databaseSettings = clone($databaseSettings);
        $this->database = $database;
        $this->database_ro = ( !is_null($database_ro) ) ? $database_ro->getConnection() : null;
        $this->database_rw = ( !is_null($database_rw) ) ? $database_rw->getConnection() : null;
    }
    
    /**
     * Get a faction object by its unique id number
     * @param type $id
     * @return Faction
     */
    public function getFactionById($id) {
        
    }
}