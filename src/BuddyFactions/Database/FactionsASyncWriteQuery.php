<?php

namespace BuddyFactions\Database;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\FactionsWriteQuery;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Objects\FactionsLevel;
use pocketmine\scheduler\AsyncTask;
use mysqli;

abstract class FactionsASyncWriteQuery extends AsyncTask {
    private $SQL;
    private $databaseSettingsSerialized;
    
    public function __construct(string $SQL, DatabaseSettings $settings, FactionsLevel $factionsLevel) {
        $this->SQL = $SQL;
        $this->databaseSettings = serialize($settings);
    }
    
    public function onRun() {
       $settings = unserialize($this->databaseSettingsSerialized);
       $query = new FactionsWriteQuery($this->SQL, $settings);
       $this->run($query);
       unset($database);
    }
    
    abstract public function run(\BuddyFactions\Database\FactionsWriteQuery $query);
    
    public function onCompletion(Server $server) {
        $chunks = $this->getResult();
        $plugin = $server->getPluginManager()->getPlugin("BuddyFactions");
        $factionsLevel = $plugin->getFactionsLevel();
        $this->complete($factionsLevel);
    }
    
    abstract public function complete(\BuddyFactions\Objects\FactionsLevel $factionsLevel);
}