<?php namespace Fuse;

class config {
    public static $version  = '0.0.1-dev';
    public static $dev      = true;
}

class __init__ {

    function __construct() {

        /**
         * Autoload the functions
         */
        $this->_functions();

        /**
         * Require each module independently of theme support
         */
        add_action('wp_loaded', function() {
            require_if_theme_supports('Fuse.WP_Query', __DIR__ .'/lib/WP_Query/WP_Query.php');
            require_if_theme_supports('Fuse.Template', __DIR__ .'/lib/Template/Template.php');
        });

    }
    
    private function _functions
    () {
        $scan = glob(__DIR__ . "/functions/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                $this->_functions();
            }
        }
    }
}

new __init__();