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
        
        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/WP_Query.js', 'min');
        }

        wp_register_script('Fuse.WP_Query', get_file_abspath(__FILE__) . '/WP_Query.min.js', array('jquery'), config::$version, true);

        wp_enqueue_script('Fuse.WP_Query');
    }

    public function query($args = false, $template = false) {
        $ajax   = false;
        $output = array(
            'message' => ''
        );

        /**
         * Validate the request
         */
        if(isset($_REQUEST['WP_Query_ajax'])) {
            $ajax           = true;

            if(isset($_REQUEST['args'])) {
                $args           = $_REQUEST['args'];
            }
            if(isset($_REQUEST['template'])) {
                $template       = $_REQUEST['template'];
            }
        }

        /**
         * Post status always set to publish
         */
        $args['post_status'] = 'publish';

        /**
         * Check if post type supported
         */
        $post_types = $args['post_type'];
        if(!is_array($post_types)) {
            $post_types = array($post_types);
        }
        $pt_filter = filter_post_type_supports($post_types, 'Fuse.WP_Query');


        /**
         * If no post types allowed
         */
        if(empty($pt_filter['post_types'])) {
            $output['success']     = 0;
            $output['message']     = 'No authorised post types set.';

            if($ajax) {
                echo wp_json_encode($output);
                die();
            }

            return wp_json_encode($output);
        }

        /**
         * If some post types removed
         */
        if(!empty($pt_filter['removed'])) {
            foreach($pt_filter['removed'] as $post_type) {
                $output['message'] .= 'Post type - '. $post_type . ' not authorised.' . "\n\n";
            }
        }

        /**
         * Reset post types
         */
        $args['post_type'] = $post_types;

        /**
         * If no arguments set
         */
        if(!$args) {
            $output['success']     = 0;
            $output['message']     = 'No arguments set for WP_Query.';

            if($ajax) {
                echo wp_json_encode($output);
                die();
            }

            return wp_json_encode($output);
        }

        /**
         * Run the query and the loop
         */
        $query      = new \WP_Query($args);
        $post_count = $query->post_count;
        $html       = false;
        if($template) {
            if($query->have_posts()) {
                ob_start();
                while($query->have_posts()) {
                    $query->the_post();
                    include(locate_template($template[0] . '-' . $template[1] . '.php'));
                }
                $html = ob_get_contents();
                ob_end_clean();
            } else {
                $output['message']     = 'No posts were found.';
            }
        }

        /**
         * Success
         */
        $output['success']      = 1;
        $output['count']        = $post_count;
        $output['html']         = $html;
        $output['template']     = $template[0] . '-' . $template[1] . '.php';
        $output['args']         = $args;

        if($ajax) {
            echo wp_json_encode($output);
            die();
        }

        return $output;
    }
}

new WP_Query();