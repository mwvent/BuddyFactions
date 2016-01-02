<?php

namespace BuddyFactions\Database\Queries;

use mysqli;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Objects\Faction;
use BuddyFactions\Objects\FactionsLevel;

class GetFactionQuery extends FactionDatabase {
    public function __construct(DatabaseSettings $settings, mysqli $db_connection = null) {
        $SQL = "
            SELECT
                `". $settings->table_factions . "`.buddypress_gid,
                `". $settings->table_bpgroups . "`.`name`,
                (
                    SELECT sum(`power`) FROM `". $settings->table_users . "`
                    WHERE `". $settings->table_users . "`.`faction_id` = ?
                ) AS `currentPower`,
                (
                    SELECT count(`power`) FROM `". $settings->table_users . "`
                    WHERE `". $settings->table_users . "`.`faction_id` = ?
                ) AS `memberCount`,
                (
                    SELECT count(`owningFaction`) FROM `". $settings->table_plots . "`
                    WHERE `". $settings->table_plots . "`.`owningFaction` = ?
                ) AS `plotsCount`,
                allies,
                enemies
            FROM `". $settings->table_factions . "`
            
            INNER JOIN `". $settings->table_bpgroups . "`
                ON `". $settings->table_bpgroups . "`.`id` = 
                    `". $settings->table_factions . "`.`buddypress_gid`
            
            WHERE
                `". $settings->table_factions . "`.buddypress_gid = ?
            ;
        ";
        
        parent::__construct($SQL, $settings, true, $db_connection);
    }
    
    
    private function getFaction(FactionsLevel $level, int $id) {
        if( ! $this->db_statement->bind_param("iiii", $id, $id, $id, $id) ) {
            $this->lastError = "Could not bind to query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( ! $this->db_statement->execute() ) {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( ! $this->db_statement->bind_result($fid, $fname, $currentPower, $memberCount, $plotCount, $fallies, $fenemies))  {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new \Exception($this->lastError);
        }
        
        if( $this->db_statement->fetch() ) {
            $allies = explode(",", $fallies);
            $enemies = explode(",", $fenemies);
            $result = new Faction($level, $fid, $fname, $memberCount, $currentPower, $plotCount);
        } else {
            $result = null;
        }
        
        $this->db_statement->free_result();
        return $this->result_id;
    }
    
}
