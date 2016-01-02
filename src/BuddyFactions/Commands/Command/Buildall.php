<?php

namespace BuddyFactions\Commands\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;

class Buildon extends Command implements CommandExecutor {
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        $this->execute($sender, $cmd, $args);
    }

    public function execute(\pocketmine\command\CommandSender $sender, $commandLabel, array $args) {
        
    }
}