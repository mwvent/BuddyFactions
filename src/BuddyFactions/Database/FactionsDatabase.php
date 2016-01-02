<?php

namespace BuddyFactions\Database;
use BuddyFactions\Database\DatabaseSettings;
use mysqli;

class FactionsDatabase {
    /**
     *
     * @var DatabaseSettings
     */
    private $settings;
    /**
     *
     * @var mysqli
     */
    private $db = null;
    
    /**
     *
     * @var string
     */
    public $lastError ="";
    
    /**
     * Start a new mysql connection
     * Can take another instance of FactionsDatabase if reusing a connection 
     * otherwise will start a fresh connection - this is designed to allow ASync tasks
     * to open fresh connections as they cannot use existing ones.
     * readOnlyDB specifies whever to use the read-only settings from config or read-write
     * settings. This is designed to allow doing faster reads from a local replication slave
     * while pushing writes upstream to the master. 
     * @param type $SQL
     * @param DatabaseSettings $settings
     * @param type $readOnlyDB
     * @param FactionsDatabase|mysqli $db_connection
     * @return boolean
     */
    public function __construct(DatabaseSettings $settings, $readOnlyDB = false, $db_connection = null) {
        $this->settings = clone($settings);
        
        if( $db_connection instanceof FactionsDatabase) {
            $this->db = $db_connection->getConnection();
        }
        
        if( $db_connection instanceof mysqli) {
            $this->db = $db_connection;
        }
        
        if(! $this->openDataBaseConnection($readOnlyDB)) {
            return false;
        }
    }
    
    public function __destruct() {
        unset($this->db_statement);
        unset($this->db);
    }
    
    /**
     * 
     * @return mysqli
     */
    public function getConnection() {
        return $this->db;
    }
    
    /**
     * Opens a mysqli connection - the readonly flag specifies which one to open
     * @param boolean $readOnlyDB
     * @return boolean
     * @throws Exception
     */
    public function openDataBaseConnection($readOnlyDB) {
        if( !is_null($this->db)) {
            return true;
        }
        
        $this->db = new \mysqli ( 
                ($readOnlyDB) ? $this->settings->conHost_ro : $this->settings->conHost_rw, 
                $this->settings->conUser, 
                $this->settings->conPass, 
                $this->settings->conUser
        );

        if ($this->db === false) {
            $this->lastError = 
                    "Database open error " .get_class($this) . ": " . $this->db->error;
            throw new Exception($this->lastError);
        }
        return true;
    }
    
   
}