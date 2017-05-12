<?php
/**
 * 
 * Checks if the table name exists
 */
if(!function_exists('db_table_exists')) {
    function db_table_exists($table_name, $prefix=false) {
        global $wpdb;
        
        if($prefix) {
            $table_name = $wpdb->prefix.$table_name;
        }

        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return false;
        }

        return true;
    }
}
