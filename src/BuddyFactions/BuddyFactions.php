<?php
namespace BuddyFactions;

use BuddyFactions\Main;
use BuddyFactions\Database\DatabaseSettings;
use BuddyFactions\Utils\ReadDBSettings;
use BuddyFactions\Utils\FactionsSettings;
use BuddyFactions\Objects\FactionsLevel;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin;

class BuddyFactions extends \pocketmine\plugin {
        /**
         * @var DatabaseSettings
         */
        private $database_settings;
        private $factionsSettings;
        /**
         * @var FactionsLevel $factionsLevel
         */
        private $factionsLevel;

        private $cfg;
	
	function __construct() {
            $this->database_settings = ReadDBSettings::read($this);
            $this->factionsSettings = new FactionsSettings();
            $this->factionsLevel = new FactionsLevel($this, $this->factionsSettings, $this->database_settings);
	}
        
        /**
         * 
         * @return FactionsLevel
         */
        function getFactionsLevel() {
            return $this->factionsLevel;
        }
        
        /**
         * Get value from config
         * If defaultvalue is not specified and value cannot be found in config then an error
         * is raised
         * @param string $key
         * @param mixed $defaultvalue
         * @return mixed
         */
        public function read_cfg($key, $defaultvalue = null) {
            // if not loaded config load and continue
            if( ! isset($this->cfg) ) {
                $this->cfg = $this->getConfig()->getAll();
            }
            // if key not in config but a default value is allowed return default
            if( ( ! isset($this->cfg[$key]) ) && ( ! is_null( $defaultvalue ) ) ) {
                return $defaultvalue;
            }
            // if key not in config but is required
            if( ( ! isset($this->cfg[$key]) ) && ( ! is_null( $defaultvalue ) ) ) {
                $sendmsg = "Cannot load " . Main::PREFIX . " required config key " . $key . " not found in config file";
                Server::getInstance()->getLogger()->critical($this->translateColors("&", Main::PREFIX . $sendmsg));
                die();
            }
            // otherwise return config file value
            return $this->cfg[$key];
        }
}