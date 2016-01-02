<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;

class FactionsPlayerInteractEvent extends FactionsEvent {
    /**
     * Stop players from interacting with blocks inside another factions land
     * or on their own factions land if they do not have the permission
     * @param BlockPlaceEvent $event
     * @return type
     */
    public function onPlayerInteract(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        $plot = $this->factionsLevel->getPlots()->getPlotAtLevelCoords($block->x, $block->y);
        $player = $event->getPlayer();
        $factionsPlayer = $this->factionsLevel->getPlayers()->getPlayer($player);
        
        // ignore events regarding this particular block
        if($block->x==0 && $block->y == 0 && $block->z == 0 ) {
            return;
        }
        
        // if plot is unclaimed return - interacting with things on unclaimed land is OK
        if( is_null($plot->getFaction()) ) { 
            return;
        }
        
        // if plot belongs to another faction cancel the event and notify
        if( $plot->getFaction()->getUID() !== $factionsPlayer->getFaction()->getUID() ) {
            $event->setCancelled(true);
            $otherFactionName = $plot->getFaction()->getName();
            $msg = "You cannot do that here - this land belongs to ";
            $msg .= $otherFactionName;
            $player->sendMessage($msg);
            return;
        }
        
        // belongs to same faction - but check player has permission
        if( ! $factionsPlayer->canBuild() ) {
            $event->setCancelled(true);
            $msg = "This land belongs to your faction but a faction admin ";
            $msg .= "needs to give you the build permission to do this.";
            $player->sendMessage($msg);
            return;
        }
    }
}