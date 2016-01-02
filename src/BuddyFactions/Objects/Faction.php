<?php

namespace BuddyFactions\Objects;
use BuddyFactions\Objects\FactionsLevel;

class Faction {
    /**
     * Unique ID
     * @var int
     */
    private $uid;
    /**
     * Faction name
     * @var string
     */
    private $name;
    /**
     * Faction power level
     * @var int
     */
    private $power = null;
    /**
     * A FactionsPlayers database object with faction filter set
     * @var FactionsPlayers
     */
    private $players;
    /**
     * @var FactionsLevel
     */
    private $factionsLevel;
    /**
     * @var DatabaseSettings
     */
    private $databaseSettings;
    /**
     * @var FactionsDatabase
     */
    private $database_ro;
    /**
     * @var FactionsDatabase
     */
    private $database_rw;
    /**
     * @var float
     */
    private $cached_member_count;
    /**
     * @var float
     */
    private $cached_current_power;
    /**
     * @var int
     */
    private $cached_claimed_plots_count;
    /**
     * @var int[]
     */
    private $cached_allies_ids;
    /**
     * @var int[]
     */
    private $cached_enemies_ids;
    
    public function __construct(
            FactionsLevel $factionsLevel,
            int $uid,
            string $name,
            int $memberCount,
            float $currentPower,
            int $claimedPlots,
            $allies,
            $enemies) {
        $this->factionsLevel = $factionsLevel;
        $this->uid = $uid;
        $this->name = $name;
        $this->cached_current_power = $currentPower;
        $this->cached_member_count = $memberCount;
        $this->cached_claimed_plots_count = $claimedPlots;
        $this->cached_allies_ids = $allies;
        $this->cached_enemies_ids = $enemies;
    }
    
    /**
     * Get the UID of the faction
     * @return int
     */
    public function getUID() {
        return $this->uid;
    }
    
    /**
     * Get the faction name
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Read the factions current power level
     * @return int
     */
    public function getPower() {
        // TODO
        return 1;
    }
    
    /**
     * Get Maximum Faction Power
     * @return float
     */
    public function getMaxPower() {
        return $this->getMemberCount() * $this->factionsLevel->getSettings()->maxPowerPerUser;
    }
    
    /**
     * Get Used Faction Power
     * @return float
     */
    public function getUsedPower() {
        return $this->cached_claimed_plots_count * $this->factionsLevel->getSettings()->powerPerPlot;
    }
    
    /**
     * Get Remaining Faction Power
     * @return float
     */
    public function getSparePower() {
        return $this->getMaxPower() - $this->getUsedPower();
    }
    
    /**
     * Does faction have enougth power to claim land?
     * @return float
     */
    public function canClaim() {
        return $this->getSparePower() >= $this->factionsLevel->getSettings()->powerPerPlot;
    }
    
    /**
     * Get count of members
     * @return int
     */
    public function getMemberCount() {
        if(is_null($this->cached_member_count )) {
            $this->reloadMemberCount();
        }
        return $this->cached_member_count;
    }
    
    /**
     * allow events handler to update value
     * @param int $newValue
     */
    public function setMemberCount($newValue) {
        $this->cached_member_count = $newValue;
    }
    
    /**
     * allow events handler to update value
     * @param int $newValue
     */
    public function setCurrentPower($newValue) {
        $this->cached_current_power = $newValue;
    }

    /**
     * Check if another faction is allied
     * Any caller should also check the alligence is mutual
     * @param \BuddyFactions\Objects\Faction $otherFaction
     * @return boolean
     */
    public function isAlliedWith(Faction $otherFaction) {
        return in_array($otherFaction->getUID(), $this->cached_allies_ids);
    }
    
    /**
     * Check if another faction is enemy
     * @param \BuddyFactions\Objects\Faction $otherFaction
     * @return boolean
     */
    public function isEnemiesWith(Faction $otherFaction) {
        return in_array($otherFaction->getUID(), $this->cached_enemies_ids);
    }
    
    /**
     * Check if another faction is neutural
     * @param \BuddyFactions\Objects\Faction $otherFaction
     * @return boolean
     */
    public function isNeutural(Faction $otherFaction) {
        return ( ! $this->isAlliedWith($otherFaction) && ! $this->isEnemiesWith($otherFaction) );
    }
    
    
}
