<?php
namespace Fuse;

include_once(__DIR__ . '/class/Helper.php');
include_once(__DIR__ . '/class/Hook.php');


class Stripe extends Stripe_Helper {

    public static $slug = 'fuse-stripe';

    public function __construct() {
        add_action('wp_head', array($this, 'print_publishable_key'));
        add_action('wp_head', function() {
            echo '<script type="text/javascript" src="https://js.stripe.com/v1/?1"></script>';
        });

        // Set the publishable key
        $this->set_key();
    }

    private function set_key() {

        try {
            $key = Stripe::get_keys(array(
                'secret'   => true
            ), self::get_mode());

            if($key) {
                foreach($key as $k => $v) {
                    $key = $v;
                    break;
                }

            }

            \Stripe\Stripe::setApiKey($key);
        } catch(\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function print_publishable_key() {
        $opts = $this->get_options();

        if(isset($opts['mode']) && $opts['mode']!=='') {
            if ($opts['mode'] == 'test') {
                if(isset($opts['test-publishable-key']) && $opts['test-publishable-key']!=='') {
                    echo '<script>stripe_publishable_key="' . $opts['test-publishable-key'] . '"</script>';
                }
            }

            if ($opts['mode'] == 'live') {
                if(isset($opts['live-publishable-key']) && $opts['test-publishable-key']!=='') {
                    echo '<script>stripe_publishable_key="' . $opts['live-publishable-key'] . '"</script>';
                }
            }
        }
    }

    public static function get_secret_key() {
        $opts = self::get_options();

        if(isset($opts['mode']) && $opts['mode']!=='') {
            if ($opts['mode'] == 'test') {
                if(isset($opts['test-secret-key']) && $opts['test-secret-key']!=='') {
                    return $opts['test-secret-key'];
                }
            }

            if ($opts['mode'] == 'live') {
                if(isset($opts['live-secret-key']) && $opts['live-secret-key']!=='') {
                    return $opts['live-secret-key'];
                }
            }
        }
    }
    
    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Stripe', 'Stripe', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-stripe.php');
            });
        });

        if(is_admin()) {
            if (isset($_GET['page']) && $_GET['page']=='fuse-stripe') {

                add_action('admin_enqueue_scripts', function() {
                    // jQuery UI
                    wp_enqueue_script('jquery-ui-core');
                    wp_enqueue_script('jquery-ui-tabs');
                    wp_enqueue_script('jquery-ui-dialog');
                    wp_enqueue_script('jquery-ui-datepicker');
                    wp_enqueue_style('jquery-ui-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');
                    wp_enqueue_style('jquery-ui-base', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/theme.min.css');

                    // Datatables
                    wp_enqueue_script('jquery-datatables', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), config::$version, false);
                    wp_enqueue_script('jquery-datatables-jq-ui', 'https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js', array('jquery'), config::$version, false);
                    wp_enqueue_style('jquery-datatables-theme', 'https://cdn.datatables.net/1.10.15/css/dataTables.jqueryui.min.css');
                });
            }
        }
    }

    /**
     * Public JS & CSS
     */
    public static function assets() {


    }

    /**
     * Admin JS & CSS
     */
    public static function assets_admin() {

        if(is_admin()) {
            if (config::$dev) {
                Util\Uglify::compile_single(__DIR__ . '/scripts/admin-coupon.js',   'min');
                Util\Uglify::compile_single(__DIR__ . '/scripts/admin-plan.js',     'min');
                Util\Uglify::compile_single(__DIR__ . '/scripts/Stripe_Admin.js',   'min');
            }

            wp_register_script('Fuse.Stripe_admin-coupon',  get_file_abspath(__FILE__) . '/scripts/admin-coupon.min.js',    array('jquery'), config::$version, true);
            wp_register_script('Fuse.Stripe_admin-plan',    get_file_abspath(__FILE__) . '/scripts/admin-plan.min.js',      array('jquery'), config::$version, true);
            wp_register_script('Fuse.Stripe_Admin',         get_file_abspath(__FILE__) . '/scripts/Stripe_Admin.min.js',    array('jquery'), config::$version, true);
            $translation_array = array(
                'admin_url' => admin_url(),
            );
            wp_localize_script( 'Fuse.Stripe_Admin', 'WP', $translation_array );

            wp_enqueue_script('Fuse.jq-serialize-object');
            wp_enqueue_script('Fuse.noty-js');
            wp_enqueue_style('Fuse.noty-css');
            wp_enqueue_script('Fuse.Stripe_admin-coupon');
            wp_enqueue_script('Fuse.Stripe_admin-plan');
            wp_enqueue_script('Fuse.Stripe_Admin');
        }
    }

    public static function save_options($options) {
        update_option('_stripe_options', $options);
        return get_option('_stripe_options');
    }

    public static function get_options() {
        $options    = get_option('_stripe_options');
        if(!$options) {
            return false;
        }

        foreach($options as $k=>$v) {
            if($v=='') {
                unset($options[$k]);
            }
        }

        return $options;
    }

    public static function get_keys(
        $keys = array(
            'publishable'   => false,
            'secret'        => false
        ),
        $env = 'test') {


        if(isset($keys['publishable']) && !$keys['publishable']) {
            unset($keys['publishable']);
        }
        if(isset($keys['secret']) && !$keys['secret']) {
            unset($keys['secret']);
        }

        if(!isset($keys['publishable']) && !isset($keys['secret'])) {
            throw new \Exception('Stripe::get_keys() : $keys either publishable or secret (or both) keys need to be set to true');
        }

        /**
         * If $env not test or live
         */

        if(($env!='test') && ($env!='live')) {
           // throw new \Exception('Stripe::get_keys() : $env needs to be set to test or live');
        }

        /**
         * Get all Stripe options
         */
        $options    = self::get_options();
        $filtered   = array();

        if($options) {
            foreach ($options as $k => $v) {

                $k = explode("-", $k);

                /**
                 * First find the environment (test or live)
                 */
                if ($k[0] == $env && array_key_exists($k[1], $keys)) {
                    $filtered[implode('-', $k)] = $v;
                }

            }
        }



        if(empty($filtered)) {
            //throw new \Exception('Stripe::get_keys() : Results seem to be empty.');
        }

        return $filtered;

    }
}

// If require Fuse.Stripe support in admin
$support = get_theme_support( 'Fuse.Stripe' );
if($support) {
    Stripe::dashboard();

    add_action( 'admin_enqueue_scripts', '\Fuse\Stripe::assets_admin');
    
    new Stripe();
    include_once(__DIR__ . '/class/Ajax.php');

}