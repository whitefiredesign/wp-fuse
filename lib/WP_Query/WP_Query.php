<?php
namespace Fuse;


class WP_Query {

    public function __construct() {

        // If require Fuse.WP_Query support in admin
        $support = get_theme_support( 'Fuse.WP_Query' );
        if($support[0]) {
            add_action( 'admin_enqueue_scripts',    array($this, 'assets'));
        }

        add_action( 'wp_ajax_nopriv_WP_Query',  array($this, 'query') );
        add_action( 'wp_ajax_WP_Query',         array($this, 'query') );
        add_action( 'wp_enqueue_scripts',       array($this, 'assets'));

    }

    public function assets() {
        wp_register_script('Fuse.WP_Query', get_file_abspath(__FILE__) . '/WP_Query.js', array('jquery'), \Fuse\config::$version, true);
        wp_enqueue_script('Fuse.WP_Query');
    }

    public function query($args = false, $template = false) {
        $ajax = false;

        if(isset($_REQUEST['WP_Query_ajax'])) {
            $ajax           = true;

            if(isset($_REQUEST['args'])) {
                $args           = $_REQUEST['args'];
            }
            if(isset($_REQUEST['template'])) {
                $template       = $_REQUEST['template'];
            }
        }

        if(!$args) {
            $args = array();
        }

        $query = new \WP_Query($args);

        $html = false;
        if($template) {
            if($query->have_posts()) {
                ob_start();
                while($query->have_posts()) {
                    $query->the_post();
                    include(locate_template($template[0] . '-' . $template[1] . '.php'));
                }
                $html = ob_get_contents();
                ob_end_clean();
            }
        }

        $query->html        = $html;
        $query->template    = $template;

        if($ajax) {
            echo wp_json_encode($query);
            die();
        }

        return $query;
    }
}

new WP_Query();