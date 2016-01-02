<?php

namespace BuddyFactions\Database\ASyncQueries;
use BuddyFactions\Database\FactionsASyncWriteQuery;
use BuddyFactions\Database\FactionsWriteQuery;
use BuddyFactions\Database\DatabaseSettings;
use \BuddyFactions\Objects\FactionsLevel;

class UpdateUserData extends FactionsASyncWriteQuery {
    private $userdataSerialized;

    public function __construct(DatabaseSettings $settings, FactionsLevel $factionsLevel, $userdata) {
        $this->userdataSerialized = serialize($userdata);
        $SQL = "";
        parent::__construct($SQL, $settings, $factionsLevel);
    }
    
    public function run(FactionsWriteQuery $query) {
        $userdata = unserialize($this->userdataSerialized);
        $query->getConnection()->query("START TRANSACTION;");
        foreach($userdata as $currentuser) {
            
        }
        $query->getConnection()->query("COMMIT;");
    }


    public function complete(FactionsLevel $factionsLevel) {
        // TODO log
    }
}