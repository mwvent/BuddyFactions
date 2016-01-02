<?php

namespace BuddyFactions\Objects;
use BuddyFactions\BuddyFactions;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Utils\FactionsSettings;
use BuddyFactions\Objects\FactionsPlots;
use BuddyFactions\Objects\FactionsFactions;
use BuddyFactions\Objects\FactionsPlayers;

class FactionsLevel {
    /**
     * Reference to plugin
     * @var BuddyFactions
     */
    private $plugin;
    /**
     *
     * @var string
     */
    private $levelName;
    /**
     *
     * @var DatabaseSettings 
     */
    private $database_settings;
    /**
     *
     * @var FactionsSettings;
     */
    private $factionsSettings;
    /**
     *
     * @var FactionsDatabase 
     */
    private $database_ro;
    /**
     *
     * @var FactionsDatabase 
     */
    private $database_rw;
    
    /**
     * The level size limit (from 0 to -maxSize, +maxSize) in chunks
     * so a value of 20 will be 40x40 chunks
     * @var int
     */
    private $maxSize;
    
    /** 
     * The plots database & cache
     * @var FactionsPlots
     */
    private $plots;
    
    /**
     * The factions database & cache
     * @var FactionsFactions
     */
    private $factions;
    
    /**
     * The factions player database & cache
     * @var FactionsPlayers
     */
    private $players;
    
    /**
     * The root class for the factions level
     * @param BuddyFactions $plugin
     * @param FactionsSettings $factionsSettings
     * @param DatabaseSettings $database_settings
     */
    public function __construct(
            BuddyFactions $plugin, FactionsSettings $factionsSettings,  DatabaseSettings $database_settings) {
        $this->plugin = $plugin;
        $this->factionsSettings = $factionsSettings;
        $this->database_settings = clone($database_settings);
        
        $this->levelName = $factionsSettings->levelName;
        $this->database_rw = new FactionsDatabase("", $database_settings, false);
        $this->database_ro = new FactionsDatabase("", $database_settings, false);
        $this->plots = new FactionsPlots($this, $database_settings, $this->database_ro, $this->database_rw);
    }
    
    /**
     * Get the plugin
     * @return BuddyFactions
     */
    public function getPlugin() {
        return $this->plugin;
    }
    
    /**
     * Return the level global factions settings
     * @return FactionsSettings
     */
    public function getSettings() {
        return $this->factionsSettings;
    }
    
    /**
     * Get the name of the level as it should be known to pocketmine
     * @return string
     */
    public function getLevelName() {
        return $this->levelName;
    }
    
    /**
     * Copy of database connection settings
     * @return DatabaseSettings
     */
    public function getDataBaseSettings() {
        return $this->database_settings;
    }
    
    /**
     * Access plots database
     * @return FactionsPlots
     */
    public function getPlots() {
        return $this->plots;
    }
    
    /**
     * Access factions database
     * @return FactionsFactions
     */
    public function getFactions() {
        return $this->factions;
    }
    
    /**
     * Access player database
     * @return FactionsPlayers
     */
    public function getPlayers() {
        return $this->players;
    }
}