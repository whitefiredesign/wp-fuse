<?php
namespace Fuse;

class LogUsers {
    private $charset_collate;
    private $wpdb;

    public static $slug = 'fuse-logusers';

    /**
     * BlockUsers constructor.
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb             = $wpdb;
        $this->charset_collate  = $wpdb->get_charset_collate();

        $this->user_login_table();

        add_action('wp_login', array($this, 'set_last_login_date'), 10, 2);
        add_action('wp_login', array($this, 'log_login'), 10, 2);
    }

    private function user_login_table() {

        $table_name = $this->wpdb->prefix . 'userlogins';

        $sql = "CREATE TABLE {$table_name} (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          user_id mediumint(9) NOT NULL,
          data text NOT NULL,
          PRIMARY KEY  (id)
        ) $this->charset_collate;";

        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_table($sql);
        }
    }

    public function set_last_login_date($user_login, $user) {
        update_user_meta($user->ID, '_last_login_date', time());
    }

    public function log_login($user_login, $user) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'userlogins';

        $wpdb->insert($table_name, array(
            'user_id'   => $user->ID,
            'data'      => maybe_serialize(array(
                'date'  => time()
            ))
        ), array('%d', '%s'));

        return false;
    }

    public static function get_last_login_date($user_id, $format = 'j-m-Y H:m:s') {
        return date($format, get_user_meta($user_id, '_last_login_date', true));
    }

    public static function get_login_data($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'userlogins';

        $data = $wpdb->get_results("SELECT user_id, data FROM $table_name WHERE user_id = $user_id", ARRAY_A);
        $i = 0;
        foreach($data as $d) {
            $data[$i]['data'] = unserialize($d['data']);
            $i++;
        }

        return $data;
    }
    
    public static function get_login_count($user_id) {
        $rows = self::get_login_data($user_id);
        
        return count($rows);
    }

    private function create_table($sql) {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

// If require Fuse.Gmap support in admin
//$support = get_theme_support( 'Fuse.LogUsers' );
//if($support) {

    new LogUsers();
//}