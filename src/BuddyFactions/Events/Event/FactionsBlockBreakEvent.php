<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use pocketmine\event\block\BlockBreakEvent;
use BuddyFactions\Events\Event\FactionsBuildEvent;
use pocketmine\Player;

class FactionsBlockBreakEvent extends FactionsBuildEvent {
    /**
     * Prevent players from building outside of land belonging to their faction
     * or on their own factions land if they do not have the permission
     * @param BlockBreakEvent $event
     * @return type
     */
    public function onBlockBreak(BlockBreakEvent $event) {
        $this->onBuild($event);
    }
}