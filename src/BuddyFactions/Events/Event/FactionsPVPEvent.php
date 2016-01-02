<?php

namespace BuddyFactions\Events\Event;
use BuddyFactions\Events\FactionsEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class FactionsPVPEvent extends FactionsEvent {
    /**
     * Prevent players from attacking same faction & allies
     * @param EntityDamageEvent $event
     */
    public function onEntityDamageByEntity(EntityDamageEvent $event){
        // determine if this is a pvp event
        $isDamageEvent = ($event instanceof EntityDamageByEntityEvent);
        if( ! $isDamageEvent ) {
            return;
        }
        $victim = $event->getEntity();
        $attacker = $event->getDamager();
        $isPVP = ( $victim instanceof Player && $attacker instanceof Player );
        if( ! $isPVP ) {
            return;
        }
        $eventLevel = strtolower($attacker->getLevel()->getName());
        
        // has this event happened in the factions world?
        $isInFactionsLevel = ($eventLevel == $this->factionsLevel->getLevelName() );
        if ( ! $isInFactionsLevel ) {
            return;
        }
        
        // get reference to players faction information
        $victimsFaction = $this->factionsLevel->getPlayers()->getPlayer($victim)->getFaction();
        $attackersFaction = $this->factionsLevel->getPlayers()->getPlayer($attacker)->getFaction();
        
        // if either player is not in a faction there is no need for intervention
        if( is_null($attackersFaction) || is_null($victimsFaction) ) {
            return;
        }
        
        // if players are in the same faction cancel PvP
        if( $attackersFaction->getUID() == $victimsFaction->getUID()) {
            $event->setCancelled(true);
            $msg = "You cannot attack " . $victim->getName();
            $msg .=  " because they are in your faction.";
            $attacker->sendMessage($msg);
            return;
        }
        
        // if players are mutually allied cancel PvP
        $victimIsAlly = $victimsFaction->isAlliedWith($attackersFaction);
        $attackerIsAlly = $attackersFaction->isAlliedWith($victimsFaction);
        $mutallyAllied = ( $victimIsAlly && $attackerIsAlly );
        if( $mutallyAllied ) {
            $event->setCancelled(true);
            $msg = "You cannot attack " . $victim->getName();
            $msg .= " because they are in your factions ally faction ";
            $msg .= $factionsVictim->getFaction()->getName();
            $attacker->sendMessage($msg);
            return;
        }
    }
}