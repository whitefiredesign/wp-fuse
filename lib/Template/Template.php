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

        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/Template.js', 'min');
        }

        wp_register_script('Fuse.Template', get_file_abspath(__FILE__) . '/Template.min.js', array('jquery'), config::$version, true);

        wp_enqueue_script('Fuse.Template');
    }

    public static function part($template=array(), $data=array(), $ext = 'php') {
        $ajax = false;

        if(isset($_REQUEST['Template_ajax'])) {
            $ajax       = true;
            if(isset($_REQUEST['template'])) {
                $template   = $_REQUEST['template'];
            }
            if(isset($_REQUEST['data'])) {
                $data       = $_REQUEST['data'];
            }
            if(isset($_REQUEST['ext'])) {
                $ext        = $_REQUEST['ext'];
            }
        }

        $template = $template[0] . '/' . $template[1] . '.'.$ext;

        if(empty($template) || !file_exists(get_template_directory() . '/' . $template)) {
            $output = array(
                'success'   => 0,
                'message'   => 'Template not found',
                'template'  => $template
            );

            if($ajax) {
                echo wp_json_encode($output);
                die();
            }

            return $output;
        }

        ob_start();
        include(locate_template($template));
        $contents = ob_get_contents();
        ob_end_clean();

        $output = array(
            'success'   => 1,
            'html'      => $contents,
            'template'  => $template
        );

        if($ajax) {
            echo wp_json_encode($output);
            die();
        }

        return $output;

    }
}

new Template();