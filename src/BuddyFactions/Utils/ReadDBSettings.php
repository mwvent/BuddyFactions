<?php

namespace BuddyFactions\Utils;
use BuddyFactions\BuddyFactions;
use BuddyFactions\Database\DatabaseSettings;

/**
 * Reads the database settings from config and return a populated DatabaseSettings
 * @return DatabaseSettings
 */
class ReadDBSettings {
    public static function read(BuddyFactions $plugin) {
        $settings = new DatabaseSettings();
        $settings->dbName = $plugin->read_cfg("mysql-database-name");
        $settings->conHost_rw = $plugin->read_cfg("mysql-host-rw");
        $settings->conHost_ro = $plugin->read_cfg("mysql-host-ro");
        $settings->conPort_rw = $plugin->read_cfg("mysql-port-rw");
        $settings->conPort_ro = $plugin->read_cfg("mysql-port-ro");
        $settings->conUser = $plugin->read_cfg("mysql-user");
        $settings->conPass = $plugin->read_cfg("mysql-pass");
        
        $settings->table_bpgroupmembers = $plugin->read_cfg ( "buddypress-bp_groups_members-tablename" );
        $settings->table_bpgroups = $plugin->read_cfg ( "buddypress-bp_groups-tablename" );
        $settings->table_wpusers = $plugin->read_cfg ( "wordpress-users-tablename" );
        
        $table_prefix = $plugin->read_cfg("buddyfactions-db-table-prefix");
        $settings->table_users = $table_prefix . "_users";
        $settings->table_plots = $table_prefix . "_plots";
        $settings->table_factions = $table_prefix . "_factions";
        $settings->table_usergroups = $table_prefix . "_usergroups";
        $settings->table_relationships = $table_prefix . "_relationships";
        $settings->table_events = $table_prefix . "_events";
        
        return $settings;
    }
}