<?php namespace Fuse;
// Autoload composer libs
require __DIR__ . '/vendor/autoload.php';

class config {
    public static $version  = '0.0.1-dev';
    public static $dev      = true;
    public static $slug     = 'wp-fuse';
    public static $viewspath= __DIR__ . '/views/';
}

class __init__ {

    function __construct() {

        /**
         * Autoload the functions
         */
        $this->_functions();

        /**
         * Autoload utilities
         */
        $this->_utils();

        /**
         * Ready admin Fuse
         */
        $this->dashboard();

        /**
         * Require each module independently of theme support
         */

        /**
         * Need to load immediately
         */


        add_action('init', function() {
            /**
             * Need to load after application loaded
             */
            require_if_theme_supports('Fuse.WP_Query',  __DIR__ .'/lib/WP_Query/WP_Query.php');
            require_if_theme_supports('Fuse.Template',  __DIR__ .'/lib/Template/Template.php');
            require_if_theme_supports('Fuse.Stripe',    __DIR__ .'/lib/Stripe/Stripe.php');
            require_if_theme_supports('Fuse.Auth0',     __DIR__ .'/lib/Auth0/Auth0.php');

        });

    }
    
    private function _functions() {
        $this->_load('functions');
    }

    private function _utils() {
        $this->_load('util');
    }

    private function _load($dir) {
        $scan = glob(__DIR__ . "/".$dir."/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                $this->_functions();
            }
        }
    }

    /**
     * Default Fuse admin dashboard
     */
    public function dashboard() {
        add_action( 'admin_menu', function() {
            add_menu_page( 'Fuse', 'Fuse', 'manage_options', config::$slug, function() {
                include_once(config::$viewspath . 'admin/fuse-dashboard.php');
            });   
        });
    }
}

new __init__();