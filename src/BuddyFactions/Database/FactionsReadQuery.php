<?php

namespace BuddyFactions\Database;
use BuddyFactions\Database\FactionsDatabase;
use BuddyFactions\Database\DatabaseSettings;
use mysqli;

class FactionsReadQuery extends FactionsDatabase {
    /**
     *
     * @var mysqli_stmt
     */
    private $db_statement;
    
    /**
     * 
     * @param string $SQL
     * @param DatabaseSettings $settings
     * @param type $readOnlyDB
     * @param type $db_connection
     */
    public function __construct(string $SQL, DatabaseSettings $settings, $db_connection = null) {
        parent::__construct($settings, true, $db_connection);
        $this->prepareStatement($SQL);
    }
    
     /**
     * Open the mysqli prepared statement
     * @param string $SQL
     * @return boolean
     * @throws Exception
     */
    public function prepareStatement(string $SQL) {
        $this->db_statement = $this->db->prepare ($SQL);
        if ($this->db_statement === false) {
            $this->lastError = 
                    "Database error preparing query for " . get_class($this) . ": " . $this->db->error;
            throw new Exception($this->lastError);
        }
        return true;
    }
}