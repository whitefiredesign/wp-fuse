<?php
namespace Fuse;

class Stripe {

    public static $slug = 'fuse-stripe';
    
    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Stripe', 'Stripe', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-stripe.php');
            });
        });
    }
    
    
    public static function save_options($options) {
        update_option('_stripe_options', $options);
        return get_option('_stripe_options');
    }

    public static function get_options() {
        $options    = get_option('_stripe_options');
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
            throw new \Exception('Stripe::get_keys() : $env needs to be set to test or live');
        }

        /**
         * Get all Stripe options
         */
        $options    = self::get_options();
        $filtered   = array();

        foreach($options as $k => $v) {

            $k = explode("-", $k);

            /**
             * First find the environment (test or live)
             */
            if($k[0]==$env && array_key_exists($k[1], $keys)) {
                $filtered[implode('-', $k)] = $v;
            }

        }



        if(empty($filtered)) {
            throw new \Exception('Stripe::get_keys() : Results seem to be empty.');
        }

        return $filtered;

    }
}

// If require Fuse.Stripe support in admin
$support = get_theme_support( 'Fuse.Stripe' );
if($support) {
    Stripe::dashboard();
}