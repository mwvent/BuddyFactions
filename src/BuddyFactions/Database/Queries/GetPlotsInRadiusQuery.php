<?php

namespace BuddyFactions\Database\Queries;

use mysqli;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\FactionsReadQuery;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Objects\FactionsPlot;
use BuddyFactions\Objects\FactionsLevel;

class GetPlotsInRadiusQuery extends FactionsReadQuery {
    /**
     * 
     * @param DatabaseSettings $settings
     * @param mysqli|FactionsDatabase $db_connection
     */
    public function __construct(DatabaseSettings $settings, mysqli $db_connection = null) {
        $SQL = "SELECT X, Z, AdminOnly, owningFaction
                FROM `" . $settings->table_plots . "`
                WHERE ((X >= ? AND X<= ?) AND (Z >= ? AND Z <= ?)) AND level=?";
        
        parent::__construct($SQL, $settings, $db_connection);
    }
    
    /**
     * 
     * @param FactionsLevel $level
     * @param int $X
     * @param int $Z
     * @param int $radius
     * @return FactionsPlot[]
     * @throws Exception
     */
    public function getPlotsInRadius(FactionsLevel $level, $X, $Z, $radius) {
        $levelName = $level->getLevelName();
        $startX = $X - $radius;
        $endX = $X + $radius;
        $startZ = $Z - $radius;
        $endZ = $Z + $radius;
        
        // setup initial array with all blank plots indexed by level,X,Z
        $plotsToReturn = [];
        for($currentX = $startX; $currentX <= $endX; $currentX++) {
            for($currentZ = $startZ; $currentZ <= $endZ; $currentZ++ ) {
                $plotsToReturn[$currentX . "," . $currentZ] = 
                        new FactionsPlot($level, $currentX, $currentZ);
            }
        }
        
        // open statement
        if( ! $this->db_statement->bind_param("iiiis", $startX, $endX, $startZ, $endZ, $levelName) ) {
            $this->lastError = "Could not bind to query in " . get_class($this) . ": " . $this->db->error;
            throw new Exception($this->lastError);
            return false;
        }
        
        if( ! $this->db_statement->execute() ) {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new Exception($this->lastError);
            return false;
        }
        
        if( ! $this->db_statement->bind_result($plotX, $plotZ, $adminOnly, $owningFactionID))  {
            $this->lastError = "Could not execute query in " . get_class($this) . ": " . $this->db->error;
            throw new Exception($this->lastError);
            return false;
        }
        
        // replace items in array with any found plots
        while( $this->db_statement->fetch() ) {
            $plotsToReturn[$plotX . "," . $plotZ] = 
                        new FactionsPlot($level, $plotZ, $plotZ, $owningFactionID, $adminOnly);
        }
        
        @$this->db_statement->free_result();
        return $plotsToReturn;
    }
}
