<?php

namespace BuddyFactions\Database;

class DatabaseSettings implements \Serializable {
    public $dbName;
    public $conHost_rw;
    public $conPort_rw;
    public $conHost_ro;
    public $conPort_ro;
    public $conUser;
    public $conPass;
    
    public $table_bpgroupmembers;
    public $table_bpgroups;
    public $table_wpusers;
    public $table_users;
    public $table_plots;
    public $table_factions;
    public $table_usergroups;
    public $table_relationships;
    public $table_events;
}