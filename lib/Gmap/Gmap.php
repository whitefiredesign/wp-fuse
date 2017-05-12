<?php
namespace Fuse;

class Gmap {

    public static $slug = 'fuse-gmap';

    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Gmap', 'Gmap', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-gmap.php');
            });
        });
    }

    public static function save_options($options) {
        update_option('_gmap_options', $options);
        return get_option('_gmap_options');
    }

    public function __construct() {
        add_action( 'wp_head', function() {
            $options = get_option('_gmap_options');
            echo '<script src="https://maps.googleapis.com/maps/api/js?key='.$options['api-key'].'"></script>' . "\n";
        });

        add_action( 'wp_enqueue_scripts',       array($this, 'assets'));
    }

    public function assets() {

        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/Gmap.js', 'min');
        }

        wp_register_script('Fuse.Gmap', get_file_abspath(__FILE__) . '/Gmap.min.js', array('jquery'), config::$version, true);
        wp_enqueue_script('Fuse.Gmap');
    }
}

// If require Fuse.Gmap support in admin
$support = get_theme_support( 'Fuse.Gmap' );
if($support) {
    Gmap::dashboard();

    new Gmap();
}