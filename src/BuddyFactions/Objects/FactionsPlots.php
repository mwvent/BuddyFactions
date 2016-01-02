<?php

namespace BuddyFactions\Objects;

use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Database\Queries\GetPlotQuery;
use BuddyFactions\Database\Queries\GetPlotsInRadiusQuery;
use BuddyFactions\Objects\FactionsLevel;
use BuddyFactions\Objects\FactionsPlot;
use BuddyFactions\Objects\Coordinates;

class FactionsPlots {
    private $cache;
    private $cacheSize = 1000;
    private $radiusReadSize = 30;
    private $levelName;
    private $factionsLevel;
    private $database_ro = null;
    private $database_rw = null;
    private $databaseSettings;
    
    private $getPlotsInRadiusQuery;
    
    public function __construct(
            FactionsLevel $factionsLevel,
            DatabaseSettings $databaseSettings, 
            FactionsDatabase $database_ro = null, 
            FactionsDatabase $database_rw = null) {
        $this->factionsLevel = $factionsLevel;
        $this->levelName = $factionsLevel->getLevelName();
        $this->databaseSettings = clone($databaseSettings);
        $this->database_ro = ( !is_null($database_ro) ) ? $database_ro->getConnection() : null;
        $this->database_rw = ( !is_null($database_rw) ) ? $database_rw->getConnection() : null;
        $this->getPlotsInRadiusQuery = new GetPlotsInRadiusQuery($databaseSettings, $database_ro);
    }
    
    /**
     * Get a plot from raw level co-ordinates
     * @param float $X
     * @param float $Z
     * @return FactionsPlot
     */
    public function getPlotAtLevelCoords($X, $Z) {
        $coords = new Coordinates();
        $coords->setFromRawCoords($X, $Z);
        return $this->getPlotAtChunkCoords($coords->getChunkX(), $coords->getChunkZ());
    }
    
    /**
     * Get a plot from chunk co-ordinates
     * @param int $X
     * @param int $Z
     * @return FactionsPlot
     * @throws Exception
     */
    public function getPlotAtChunkCoords($X, $Z) {
        // try read from cache
        if(array_key_exists($X . "," . $Z, $this->cache)) {
            return $this->cache[$X . "," . $Z];
        }
        // try pull from db
        $this->readPlotsFromDatabaseAt($X, $Z);
        // should be in cache now
        if(array_key_exists($X . "," . $Z, $this->cache)) {
            return $this->cache[$X . "," . $Z];
        }
        // if this line is reached there is a massive problem
        throw new Exception("Failed to cache plots when trying to read from $X, $Z in $this->levelName");
    }
    
    /**
     * Read plots from database at the point specified and in the range of the radius setting
     * @param float $X
     * @param float $Z
     */
    private function readPlotsFromDatabaseAt($X, $Z) {
        $readPlots = $this->getPlotsInRadiusQuery->getPlotsInRadius(
                $this->factionsLevel, $X, $Z, $this->radiusReadSize) ;
        foreach($readPlots as $currentPlot) {
            $this->cache[$currentPlot->X . "," . $currentPlot->Z] = $currentPlot;
            
        }
        // TODO push older plots from array if over max cache size
    }
}

