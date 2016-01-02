<?php

namespace BuddyFactions\Database\Queries;

use mysqli;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\FactionsReadQuery;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Objects\FactionsPlayer;
use BuddyFactions\Objects\FactionsLevel;

class GetPlayerQuery extends FactionsReadQuery {
    /**
     * 
     * @param DatabaseSettings $settings
     * @param mysqli|FactionsDatabase|null $db_connection
     */
    public function __construct(DatabaseSettings $settings, $db_connection = null) {
        $SQL = "
            SELECT 
                `faction_id`, 
                `faction_joindate`,
		`power`,
                `money`,
                `perm_canUnclaim`,
                `perm_canClaim`,
                `perm_isAdmin`,
                `perm_canBuild`,
		`rankname`
            FROM `". $settings->table_users . "`
            WHERE `username` = ?
        ";
        
        parent::__construct($SQL, $settings, $db_connection);
    }
    
    /**
     * 
     * @param FactionsLevel $level
     * @param string $playerName
     * @return FactionsPlayer
     * @throws \Exception
     */
    private function getPlayer(FactionsLevel $level, string $playerName) {
        $playerName = strtolower($playerName);
        if( ! $this->db_statement->bind_param("iiii", $id, $id, $id, $id) ) {
            $this->lastError = "Could not bind to query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( ! $this->db_statement->execute() ) {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( ! $this->db_statement->bind_result(
                $fid, 
                $fjoinDate,
                $currentPower,
                $currentMoney,
                $perm_canUnclaim, 
                $perm_canClaim,
                $perm_isAdmin,
                $perm_canBuild,
                $rankname))  {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( $this->db_statement->fetch() ) {
            $result = new FactionsPlayer(
                $level,
                $playerName,
                $fid, 
                $fjoinDate,
                $currentPower,
                $currentMoney,
                $perm_canUnclaim, 
                $perm_canClaim,
                $perm_isAdmin,
                $perm_canBuild,
                $rankname   
            );
        } else {
            $result = null;
        }
        
        $this->db_statement->free_result();
        return $result;
    }
    
}
