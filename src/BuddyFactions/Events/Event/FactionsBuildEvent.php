<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\Player;

class FactionsBuildEvent extends FactionsEvent {
    /**
     * Prevent players from building outside of land belonging to their faction
     * or on their own factions land if they do not have the permission
     * @param BlockBreakEvent $event
     * @return type
     */
    public function onBuild(BlockEvent $event) {
        $block = $event->getBlock();
        // ignore events for block 0,0,0
        if($block->x==0 && $block->y == 0 && $block->z == 0 ) {
            // ignore events regarding this particular block
            return;
        }
        
        // gather info
        $actionType = ($event instanceof BlockBreakEvent) ? "break" : "place";
        $plot = $this->factionsLevel->getPlots()->getPlotAtLevelCoords($block->x, $block->y);
        $player = $event->getPlayer();
        $factionsPlayer = $this->factionsLevel->getPlayers()->getPlayer($player);
        $onSameFactionsLand = ($plot->getFaction()->getUID() !== $factionsPlayer->getFaction()->getUID() );
        
        // cannot build on unclaimed land unless buildon all preference is set
        $allowBuildOnUnclaimed = $factionsPlayer->getPreferenceBuildOnUnclaimed();
        if( is_null($plot->getFaction()) && ( ! $allowBuildOnUnclaimed ) ) { 
            $event->setCancelled(true);
            $msg = TextFormat::DARK_RED . "This is unclaimed land!";
            $msg .= PHP_EOL . TextFormat::RESET;
            $msg .= "You may build here but first you must set your preference ";
            $msg .= "to build on unprotected land with the command - ";
            $msg .= TextFormat::DARK_GREEN . "/f buildall";
            $player->sendMessage($msg);
            return;
        }

        // plot belongs to another faction
        if( ! $onSameFactionsLand ) {
            // belongs to another faction
            $event->setCancelled(true);
            $otherFactionName = $plot->getFaction()->getName();
            $msg = "You cannot $actionType blocks here - this land belongs to " . $otherFactionName;
            $player->sendMessage($msg);
            return;
        }
        
        // belongs to same faction - but player does not have permission
        if( $onSameFactionsLand && !$factionsPlayer->canBuild() ) {
            $event->setCancelled(true);
            $msg = "This land belongs to your faction but a faction admin ";
            $msg .= "needs to give you the build permission to $actionType here.";
            $player->sendMessage($msg);
            return;
        }
    }
}