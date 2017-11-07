<?php
namespace Fuse;

class Js {

    public function __construct() {
        // Always include admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_branding'));

        // Register others
        add_action('admin_enqueue_scripts', array($this, 'register_admin'));
        add_action('wp_enqueue_scripts',    array($this, 'register_public'));

    }

    public function register_admin() {
        wp_register_script( 'Fuse.jq-serialize-object', get_stylesheet_directory_uri() . config::$fusedir . '/assets/lib/jquery-serialize-object/dist/jquery.serialize-object.min.js', array('jquery'), VERSION);
        wp_register_script( 'Fuse.noty-js',             get_stylesheet_directory_uri() . config::$fusedir . '/assets/lib/noty/lib/noty.min.js', array('jquery'), VERSION);
    }

    public function register_public() {
        wp_register_script( 'Fuse.jq-serialize-object', get_stylesheet_directory_uri() . config::$fusedir . '/assets/lib/jquery-serialize-object/dist/jquery.serialize-object.min.js', array('jquery'), VERSION, true);
    }

    public function admin_branding() {
        wp_register_script( 'admin-branding-js',  get_stylesheet_directory_uri() . config::$fusedir . '/assets/js/admin/branding.min.js', array('jquery'), VERSION);

        Util\Uglify::compile_single(__DIR__ . '/js/admin/branding.js', 'min');

        wp_enqueue_script('admin-branding-js');
    }
}

new Js();