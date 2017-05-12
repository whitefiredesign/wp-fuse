<?php
namespace Fuse;

/**
 * Functions for PopularPosts
 */
include __DIR__ . '/functions/get_view_count.php';

/**
 * Class PopularPosts
 * @package Fuse
 */

class PopularPosts extends \WP_Query {

    public static $slug = 'fuse-popular-posts';
    public static $meta_key    = '_fuse_post_views_count';
    public $post_id     = false;
    public $options     = false;
    public $ajax        = false;

    public function __construct($args = array()) {
        parent::__construct();

        $this->options = get_option('_popularposts_options');
        if(is_array($this->options)) {

            // If using Ajax to count
            if ($this->options['ajax'] == 'on') {
                $this->ajax = true;

            }
        }

        // Initiate dashboard
        self::dashboard();

        //To keep the count accurate, lets get rid of prefetching
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

        if($this->ajax) {
            add_action( 'wp_ajax_count_post_visit',         array($this, 'setup') );
            add_action( 'wp_ajax_nopriv_count_post_visit',  array($this, 'setup') );
            add_action( 'wp_enqueue_scripts', array($this, 'assets'));
        } else {
            add_action('wp', function() {
                if(is_single() || is_singular()) {
                    global $post;
                    if($post) {
                        $this->post_id = $post->ID;
                    }

                    $this->setup($this->post_id);
                }
            });
        }
        
    }

    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Popular Posts', 'Popular Posts', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-popular-posts.php');
            });
        });
    }

    public function setup($post_id) {

        if(isset($_REQUEST['ajax-popular-posts'])) {
            $post_id = $_REQUEST['post_id'];
        }

        $count = get_post_meta($post_id, self::$meta_key, true);
        if($count==''){
            $count = 0;
            delete_post_meta($post_id, self::$meta_key);
        } else {
            $count++;
        }

        update_post_meta($post_id, self::$meta_key, $count);


        if(isset($_REQUEST['ajax-popular-posts'])) {
            echo $post_id;
            die();
        }
    }

    public static function posts_join($sql) {
        global $wpdb;

        return $sql . " INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
    }

    public static function posts_groupby($sql) {
        global $wpdb;
        $table  = $wpdb->prefix . 'postmeta';

        return $sql . " $table.meta_value ";
    }

    public static function posts_where($sql) {
        global $wpdb;
        $table  = $wpdb->prefix . 'postmeta';
        $sql   .= $wpdb->prepare(" AND $table.meta_key = %s AND $table.meta_value>0", self::$meta_key);

        return $sql;
    }

    public static function posts_order($sql) {
        global $wpdb;
        $table  = $wpdb->prefix . 'postmeta';
        $sql    = " $table.meta_value+0 DESC ";

        return $sql;
    }

    public function assets() {

        global $post;
        if($post) {
            $this->post_id = $post->ID;
        }

        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/PopularPosts.js', 'min');
        }

        wp_register_script('Fuse.PopularPosts', get_file_abspath(__FILE__) . '/PopularPosts.min.js', array('jquery'), config::$version, true);
        wp_localize_script('Fuse.PopularPosts', 'PopularPostsData', array(
            'post_id' => $this->post_id
        ));
        wp_enqueue_script('Fuse.PopularPosts');
    }

    public static function save_options($options) {
        update_option('_popularposts_options', $options);
        return get_option('_popularposts_options');
    }

    public static function get_options() {
        $options    = get_option('_popularposts_options', true);

        if($options && is_array($options)) {
            foreach ($options as $k => $v) {
                if ($v == '') {
                    unset($options[$k]);
                }
            }

            return $options;
        }

        return false;
    }
    
    /**
     * Functions to be used in app
     */
    public static function query_head() {
        add_filter( 'posts_join',       '\Fuse\PopularPosts::posts_join' );
        add_filter( 'posts_groupby',       '\Fuse\PopularPosts::posts_groupby' );
        add_filter( 'posts_where',      '\Fuse\PopularPosts::posts_where' );
        add_filter( 'posts_orderby',    '\Fuse\PopularPosts::posts_order' );
    }
    
    public static function query_foot() {
        remove_filter( 'posts_join',       '\Fuse\PopularPosts::posts_join' );
        remove_filter( 'posts_groupby',       '\Fuse\PopularPosts::posts_groupby' );
        remove_filter( 'posts_where',      '\Fuse\PopularPosts::posts_where' );
        remove_filter( 'posts_orderby',    '\Fuse\PopularPosts::posts_order' );
    }
    
    public static function WP_Query($args) {
        self::query_head();
        $query = new \WP_Query($args);
        self::query_foot();

        return $query;
    }
}

class_alias('Fuse\PopularPosts', 'WP_Query_PopularPosts');
new PopularPosts();