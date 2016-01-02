<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use BuddyFactions\Objects\FactionsLevel;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDeathEvent;

use pocketmine\Player;

class FactionsDeathEvent extends FactionsEvent {
    /**
     * Take away power from dying players
     * @param BlockBreakEvent $event
     */
    public function onPlayerDeath(PlayerDeathEvent $event){
        $player = $event->getEntity();
        // ignore non-player entities
        if( ! $player instanceof Player) {
            return;
        }
        
        // gather info
        $factionsPlayer = $this->factionsLevel->getPlayers()->getPlayer($player);
        $cause = $player->getLastDamageCause();
        
        // get power loss
        if($cause instanceof EntityDamageByEntityEvent) {
            /* TODO - this would not take into account mob damage
               need to check cause is player but take into account
               that cause may be the firer of a projectile
            */
            $powerLoss = $this->factionsLevel->getSettings()->powerLostForPvpDeath;
        } else {
            $powerLoss = $this->factionsLevel->getSettings()->powerLostForOtherDeath;
        }
        
        // update player
        
    }
}