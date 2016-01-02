<?php

namespace BuddyFactions\Objects;
use BuddyFactions\Objects\FactionsLevel;
use BuddyFactions\Objects\Faction;

class FactionsPlot {
    /**
     * Plot X chunk co-ordinate
     * @var int
     */
    private $X;
    /**
     * Plot Y chunk co-ordinate
     * @var int
     */
    private $Z;
    
    /**
     *
     * @var FactionsLevel
     */
    private $factionsLevel;
    
    /**
     *
     * @var int
     */
    private $owningFactionId;
    
    /**
     *
     * @var string
     */
    private $levelName;
    
    /**
     *
     * @var boolean
     */
    private $adminOnly;
    
    public function __construct(FactionsLevel $level, $X, $Z, $owningFactionId = null, $adminOnly = null) {
        $this->X = $X;
        $this->Z = $Z;
        $this->levelName = $levelName;
        $this->owningFactionId = $owningFactionId;
        $this->adminOnly = $adminOnly;
    }
    
    /**
     * Return if plot is set for faction admins only
     * @return boolean
     */
    public function isAdminOnly() {
        return $this->adminOnly;
    }
    
    /**
     * Get chunk X co-ordinate
     * @return int
     */
    public function getX() {
        return $this->X;
    }
    
    /**
     * Get chunk Z co-ordinate
     * @return int
     */
    public function getZ() {
        return $this->Z;
    }
    
    /**
     * Get owning faction
     * @return Faction|null
     */
    public function getFaction() {
        if( !is_null($this->owningFactionId) ) {
            return $this->factionsLevel->getFactions()->getFactionById($this->owningFactionId);
        } else {
            return null;
        }
    }
    
    /**
     * Determine if a plot is exposed - game rules state that a plot cannot be
     * claimed from another faction if that plot is surrounded (on 4 sides not 8)
     * by other plots owned by the same faction
     * @todo Should allies count?
     * @return boolean
     */
    public function isExposed() {
        // if this plot is unclaimed then just return exposed
        if(is_null($this->owningFactionId)) {
            return true;
        }
        
        // get references to adjoining plots
        $plotstocheck = [];
        $plotstocheck[] = $this->factionsLevel->getPlots()->getPlotAtChunkCoords($this->X, $this->Z + 1);
        $plotstocheck[] = $this->factionsLevel->getPlots()->getPlotAtChunkCoords($this->X, $this->Z - 1);
        $plotstocheck[] = $this->factionsLevel->getPlots()->getPlotAtChunkCoords($this->X - 1, $this->Z);
        $plotstocheck[] = $this->factionsLevel->getPlots()->getPlotAtChunkCoords($this->X + 1, $this->Z);
        
        $thisFactionID = $this->getFaction()->getUID();
        foreach($plotstocheck as $adjacentPlot) {
            // if any of the four plots are unclaimed this is exposed
            if(is_null($adjacentPlot)) {
                return true;
            }
            // if any of the four plots do not have same owner then this is exposed
            if($adjacentPlot->getFaction()->getUID() !== $thisFactionID) {
                if( ! $this->factionsLevel->getSettings()->alliedPlotsPreventExposure) {
                    return true;
                }
                // unless the other faction is a mutual ally
                $alliedTo = $this->getFaction()->isAlliedWith($adjacentPlot->getFaction());
                $alliedFrom  = $adjacentPlot->getFaction()->isAlliedWith($this->getFaction());
                if( ! ( $alliedTo  && $alliedFrom ) ) {
                    return true;
                }
            }
        }
        
        // looks like its not exposed
        return false;
    }
}