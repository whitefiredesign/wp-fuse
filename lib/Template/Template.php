<?php
namespace Fuse;


class Template {

    public function __construct() {

        // If require Fuse.Template support in admin
        $support = get_theme_support( 'Fuse.Template' );
        if($support[0]) {
            add_action( 'admin_enqueue_scripts',    array($this, 'assets'));
        }

        add_action( 'wp_ajax_nopriv_Template',  array($this, 'part') );
        add_action( 'wp_ajax_Template',         array($this, 'part') );
        add_action( 'wp_enqueue_scripts',       array($this, 'assets'));

    }

    public function assets() {
        wp_register_script('Fuse.Template', get_file_abspath(__FILE__) . '/Template.js', array('jquery'), \Fuse\config::$version, true);
        wp_enqueue_script('Fuse.Template');
    }

    public static function part($template=array(), $data=array()) {
        $ajax = false;

        if(isset($_REQUEST['Template_ajax'])) {
            $ajax       = true;
            $template   = $_REQUEST['template'];
            $data       = $_REQUEST['data'];
        }

        if(empty($template)) {
            $output = array(
                'response'  => 0,
                'message'   => 'Template not found'
            );

            if($ajax) {
                echo wp_json_encode($output);
                die();
            }

            return $output;
        }

        ob_start();
        include(locate_template($template[0] . '-' . $template[1] . '.php'));
        $contents = ob_get_contents();
        ob_end_clean();

        $output = array(
            'response'  => 1,
            'html'      => $contents
        );

        if($ajax) {
            echo wp_json_encode($output);
            die();
        }

        return $output;

    }
}

new Template();