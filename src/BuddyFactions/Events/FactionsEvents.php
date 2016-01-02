<?php

namespace BuddyFactions\Events;

use BuddyFactions\BuddyFactions;
use BuddyFactions\Events\FactionsEvent;

use BuddyFactions\Objects\FactionsLevel;

class FactionsEvents {
    /**
     * @var FactionsEvent[]
     */
    private $plugin;
    
    /**
     * @var FactionsLevel
     */
    private $factionsLevel;
    
    /**
     *
     * @var FactionsEvent
     */
    private $events = [];
    
    public function __construct(BuddyFactions $plugin) {
        $this->plugin = $plugin;
        $this->factionsLevel = $plugin->getFactionsLevel();
        $this->events[] = new \BuddyFactions\Events\Event\FactionsBlockBreakEvent($plugin);
        $this->events[] = new \BuddyFactions\Events\Event\FactionsBlockPlaceEvent($plugin);
        $this->events[] = new \BuddyFactions\Events\Event\FactionsPlayerInteractEvent($plugin);
    }
}