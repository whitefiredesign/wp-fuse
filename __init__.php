<?php namespace Fuse;
// Autoload composer libs
require __DIR__ . '/vendor/autoload.php';

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
         * Autoload utilities
         */
        $this->_utils();

        /**
         * Require each module independently of theme support
         */
        add_action('wp_loaded', function() {
            require_if_theme_supports('Fuse.WP_Query', __DIR__ .'/lib/WP_Query/WP_Query.php');
            require_if_theme_supports('Fuse.Template', __DIR__ .'/lib/Template/Template.php');
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
}

new __init__();