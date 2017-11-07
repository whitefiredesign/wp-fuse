<?php
namespace Fuse;

class Css {

    public $branding = array(
        'bcolour_1'     => '#e21011',
        'bcolour_2'     => '#262626',
        'bfont_1'       => 'Lato,sans-serif',
        'bfont_2'       => 'Lato,sans-serif'
    );

    public function __construct() {
        // Always include admin styles
        add_action('admin_enqueue_scripts', array($this, 'admin_branding'));

        // Register others
        add_action('admin_enqueue_scripts', array($this, 'register_admin'));
        add_action('wp_enqueue_scripts',    array($this, 'register_public'));
    }

    public function register_admin() {
        wp_register_style( 'Fuse.noty-css',  get_stylesheet_directory_uri() . config::$fusedir . '/assets/lib/noty/lib/noty.css', false, VERSION, 'all');
    }
    
    public function register_public() {
        
    }

    public function admin_branding() {
        wp_register_style( 'admin-branding-css',  get_stylesheet_directory_uri() . config::$fusedir . '/assets/css/admin/branding.min.css', false, VERSION, 'all');

        //echo __DIR__ . '/css/admin/branding.less';
        Util\Less::compile(__DIR__ . '/css/admin/branding.less', 'min', array(
            'bcolour_1' => '' . $this->branding['bcolour_1'] . '',
            'bcolour_2' => '' . $this->branding['bcolour_2'] . '',
            'bfont_1'   => '' . $this->branding['bfont_1'] . '',
            'bfont_2'   => '' . $this->branding['bfont_2'] . '',

        ), __DIR__ . '/css/admin', true, false);

        wp_enqueue_style('admin-branding-css');
    }
}

new Css();