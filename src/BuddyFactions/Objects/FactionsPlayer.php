<?php

namespace BuddyFactions\Objects;

use \BuddyFactions\Objects\FactionsLevel;

use pocketmine\Player;

class FactionsPlayer {
    /**
     * Player Name Lower Case
     * @var string
     */
    private $name;
    /**
     * Current power level
     * @var float
     */
    private $power;
    /**
     * Faction user belongs to
     * @var Faction
     */
    private $faction = null;
    
    /**
     * Id of faction user belongs to
     * @var int
     */
    private $factionid = null;
    
    private $factionJoinDate;
    
    private $money;
    
    private $perm_canUnclaim;
    
    private $perm_canClaim;
    
    private $perm_isAdmin;
    
    private $perm_canBuild;
    
    private $rankName;
    
    private $preference_buildOnUnclaimed;
    
    private $databaseUpToDate = true;
    
    /**
     * The factions level this instance is associated with
     * @var FactionsLevel
     */
    private $factionsLevel;
    
    function __construct(
                FactionsLevel $factionsLevel,
                string $name,
                $fid, 
                $fjoinDate,
                $currentPower,
                $currentMoney,
                $perm_canUnclaim, 
                $perm_canClaim,
                $perm_isAdmin,
                $perm_canBuild,
                $rankname
            ) {
        $this->factionsLevel = $factionsLevel;
        $this->name = $name;
        $this->factionid = $fid;
        $this->factionJoinDate = $fjoinDate;
        $this->power = $currentPower;
        $this->money = $currentMoney;
        $this->perm_canUnclaim = $perm_canUnclaim;
        $this->perm_canClaim = $perm_canClaim;
        $this->perm_isAdmin = $perm_isAdmin;
        $this->perm_canBuild = $perm_canBuild;
        $this->rankName = $rankname;
        $this->preference_buildOnUnclaimed = false;
        $this->databaseUpToDate = true;
    }
    
    /**
     * Player is a member of a faction
     * @return boolean
     */
    function hasFaction() {
        return is_null($this->factionid);
    }
    
    /**
     * Get the faction the player belongs to
     * @return Faction|null
     */
    function getFaction() {
        if( is_null($this->factionid)) {
            return null;
        }
        if( is_null($this->faction)) {
            $this->faction = $this->factionsLevel->getFactions()->getFactionById($this->factionid);
        }
        return $this->faction;
    }
    
    /**
     * Get the players current power level
     * @return float
     */
    function getPower() {
        return $this->power;
    }
    
    /**
     * Adjust the players power by the value specified
     * @param float $adjustmentValue
     */
    function adjustPower(float $adjustmentValue) {
        $this->power += $adjustmentValue;
        $this->databaseUpToDate = false;
    }
    
    /**
     * Get the players name (lowercase)
     * @return string
     */
    function getName() {
        return $this->name; 
    }
    
    /**
     * Get the players money balance
     * @return int
     */
    function getMoney() {
        return $this->money;
    }
    
    /**
     * Has the player got permission to claim land?
     * @return boolean
     */
    function canClaim() {
        return ($this->isAdmin()) ? true : $this->perm_canClaim;
    }
    
    /**
     * Has the player got permission to unclaim land?
     * @return boolean
     */
    function canUnclaim() {
        return ($this->isAdmin()) ? true : $this->perm_canUnclaim;
    }
    
    /**
     * Is the player the faction owner or admin
     * @return boolean
     */
    function isAdmin() {
        return $this->perm_isAdmin;
    }
    
    /**
     * Can the player build on their faction land and use chests doors etc
     * @return boolean
     */
    function canBuild() {
        return ($this->isAdmin()) ? true : $this->perm_canBuild;
    }

    /**
     * Does the player wish to be allowed to build on unclaimed land?
     * @return boolean
     */
    function getPreferenceBuildOnUnclaimed() {
        return $this->preference_buildOnUnclaimed;
    }
    
    /**
     * Set the players preference to build on unclaimed land
     * @param boolean $newValue
     */
    function setPreferenceBuildOnUnclaimed(boolean $newValue) {
        $this->preference_buildOnUnclaimed = $newValue;
    }
}
