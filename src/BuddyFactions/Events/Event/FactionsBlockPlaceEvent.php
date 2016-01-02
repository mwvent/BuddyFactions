<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use BuddyFactions\Events\Event\FactionsBuildEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class FactionsBlockPlaceEvent extends FactionsBuildEvent {
    /**
     * Prevent players from building outside of land belonging to their faction
     * or on their own factions land if they do not have the permission
     * @param BlockPlaceEvent $event
     * @return type
     */
    public function onBlockPlace(BlockPlaceEvent $event) {
        $this->onBuild($event);
    }
}