<?php namespace Fuse;

/**
 * If Fuse installed by Composer dependencies already installed
 */
if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    // Autoload composer libs
    require __DIR__ . '/vendor/autoload.php';
}

class config {
    public static $version  = '0.0.5-dev';
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
         * Autoload fixes
         */
        $this->_fix();

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
            require_if_theme_supports('Fuse.WP_Query',      __DIR__ .'/lib/WP_Query/WP_Query.php');
            require_if_theme_supports('Fuse.Template',      __DIR__ .'/lib/Template/Template.php');
            require_if_theme_supports('Fuse.Stripe',        __DIR__ .'/lib/Stripe/Stripe.php');
            require_if_theme_supports('Fuse.Auth0',         __DIR__ .'/lib/Auth0/Auth0.php');
            require_if_theme_supports('Fuse.MailChimp',     __DIR__ .'/lib/MailChimp/MailChimp.php');
            require_if_theme_supports('Fuse.Gmap',          __DIR__ .'/lib/Gmap/Gmap.php');
            require_if_theme_supports('Fuse.PopularPosts',  __DIR__ .'/lib/PopularPosts/PopularPosts.php');
            require_if_theme_supports('Fuse.Form',          __DIR__ .'/lib/Form/Form.php');


            do_action('fuse-loaded');
        });

    }
    
    private function _functions() {
        $this->_load('functions');
    }

    private function _utils() {
        $this->_load('util');
    }

    private function _fix() {
        $this->_load('fix');
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


        /**
         * Fuse dashboard widgets
         */
        add_action('wp_dashboard_setup', function() {
            \add_meta_box(
                'dash_contact',
                'Developer Contact Details',
                array($this, 'dash_contact' ),
                'dashboard', 'side', 'high'
            );
        });

        /**
         * Hijack the Welcome Panel
         */
        \remove_action( 'welcome_panel', 'wp_welcome_panel' );
        \add_action('welcome_panel', function() {
            include_once(config::$viewspath . 'admin/fuse-dashboard-meta-welcome.php');
        });

    }

    /**
     * Save dashboard settings
     */
    public static function save_options($options) {
        update_option('_settings_options', $options);
        return get_option('_settings_options');
    }


    /**
     * Dashboard widget functions
     */
    public function dash_contact() {
        include_once(config::$viewspath . 'admin/fuse-dashboard-contact-widget.php');
    }
}

new __init__();

class_alias('\Fuse\__init__', '\Fuse\Dashboard');