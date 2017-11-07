<?php
namespace Fuse\Commerce;

class Db {
    private $charset_collate;
    private $wpdb;

    function __construct() {
        global $wpdb;
        $this->wpdb             = $wpdb;
        $this->charset_collate  = $wpdb->get_charset_collate();

        $this->coupons_table();
        $this->plans_table();

    }

    private function coupons_table() {
        global $wpdb;

        $table_name = $this->wpdb->prefix . 'commerce_coupon';

        // Parent table
        $sql = "CREATE TABLE {$table_name}s (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          coupon_id tinytext NOT NULL,
          vendor tinytext NOT NULL,
          status tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        // Meta table
        $sql = "CREATE TABLE {$table_name}meta (
          meta_id bigint(20) NOT NULL AUTO_INCREMENT,
          coupon_id tinytext NOT NULL,
          meta_key varchar(255) DEFAULT NULL,
          meta_value longtext,
          PRIMARY KEY  (meta_id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        $wpdb->couponmeta = $wpdb->prefix . 'commerce_couponmeta';

    }

    private function plans_table() {
        global $wpdb;

        $table_name = $this->wpdb->prefix . 'commerce_plan';

        // Parent table
        $sql = "CREATE TABLE {$table_name}s (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          plan_id tinytext NOT NULL,
          vendor tinytext NOT NULL,
          status tinytext NOT NULL,
          PRIMARY KEY  (id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        // Meta table
        $sql = "CREATE TABLE {$table_name}meta (
          meta_id bigint(20) NOT NULL AUTO_INCREMENT,
          plan_id tinytext NOT NULL,
          meta_key varchar(255) DEFAULT NULL,
          meta_value longtext,
          PRIMARY KEY  (meta_id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }

        $wpdb->planmeta = $wpdb->prefix . 'commerce_planmeta';
    }

    private function create_table($sql) {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

new Db();