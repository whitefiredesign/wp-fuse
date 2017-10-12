<?php
namespace Fuse;

class Js {

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'admin_branding'));
    }

    public function admin_branding() {
        wp_register_script( 'admin-branding-js',  get_stylesheet_directory_uri() . config::$fusedir . '/assets/js/admin/branding.min.js', array('jquery'), VERSION);

        Util\Uglify::compile_single(__DIR__ . '/js/admin/branding.js', 'min');

        wp_enqueue_script('admin-branding-js');
    }
}

new Js();