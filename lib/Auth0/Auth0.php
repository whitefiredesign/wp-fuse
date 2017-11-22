<?php
namespace Fuse;

include_once(__DIR__ . '/class/Helper.php');

class Auth0 extends Auth0_Helper {

    public static $slug = 'fuse-auth0';
    public static $auth0;
    
    public function __construct() {

        // Initiate dashboard
        self::dashboard();

        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/Auth0.js', 'min');
        }

        // Add JS
        $options = self::get_options();
        if(is_array($options)) {
            $options['callback-url'] = get_site_url() . self::get_callback_url();
            wp_register_script('Fuse.Auth0', get_file_abspath(__FILE__) . '/Auth0.min.js', array('auth0'), config::$version, true);
            wp_localize_script('Fuse.Auth0', 'Auth0_config', $options);
            add_action('wp_enqueue_scripts', function () {
                wp_enqueue_script('auth0', 'https://cdn.auth0.com/w2/auth0-7.4.min.js', false, config::$version, false);
                wp_enqueue_script('Fuse.Auth0');
            });
        }

    }

    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Auth0', 'Auth0', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-auth0.php');
            });
        });
    }


    public static function save_options($options) {
        update_option('_auth0_options', $options);
        return get_option('_auth0_options');
    }

    public static function get_options($secret = false) {
        $options    = get_option('_auth0_options');
        if(!$secret) {
            unset($options['client-secret']);
        }
        if(empty($options)) {
            $options = false;
        }
        return $options;
    }
}

// If require Fuse.Auth0 support in admin
$support = get_theme_support( 'Fuse.Auth0' );
if($support) {
    new Auth0();

    include_once(__DIR__ . '/class/Process.php');
    include_once(__DIR__ . '/class/LoginOption.php');
}