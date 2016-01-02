<?php

namespace BuddyFactions\Events;
use BuddyFactions\BuddyFactions;
use BuddyFactions\Objects\FactionsLevel;

class FactionsEvent implements Listener {
    
    /**
     * @var BuddyFactions $plugin
     */
    private $plugin;
    
    /**
     * @param FactionsLevel $factionsLevel
     */
    private $factionsLevel;
    
    /**
     * @param BuddyFactions $plugin
     */
    public function __construct(BuddyFactions $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        $this->factionsLevel = $plugin->getFactionsLevel();
    }
}