<?php namespace Fuse;

/**
 * Class Ajax
 * Ajaxify helper functions
 * @package Fuse\Stripe
 */
class Stripe_Ajax {

    public function __construct() {


        /**
         * Tables
         */
        // get_available_plans
        add_action( 'wp_ajax_get_available_plans',
            array($this, 'get_available_plans'));

        // get_subscription_list_admin_table
        add_action( 'wp_ajax_get_subscription_list_admin_table',
            array($this, 'get_subscription_list_admin_table'));
        
        // get_available_plans_admin_table
        add_action( 'wp_ajax_get_available_plans_admin_table',
            array($this, 'get_available_plans_admin_table'));

        // get_available_coupons_admin_table
        add_action( 'wp_ajax_get_available_coupons_admin_table',
            array($this, 'get_available_coupons_admin_table'));

        // get_customer_list_admin_table
        add_action( 'wp_ajax_get_customer_list_admin_table',
            array($this, 'get_customer_list_admin_table'));


        /**
         * Add / Update / Delete / Enable methods
         */
        // add coupon
        add_action( 'wp_ajax_stripe_add_coupon',
            array($this, 'add_coupon'));

        // delete coupon
        add_action( 'wp_ajax_stripe_delete_coupon',
            array($this, 'delete_coupon'));

        // enable coupon
        add_action( 'wp_ajax_stripe_enable_coupon',
            array($this, 'enable_coupon'));
        
        // disable coupon
        add_action( 'wp_ajax_stripe_disable_coupon',
            array($this, 'disable_coupon'));
        
        // add plan
        add_action( 'wp_ajax_stripe_add_plan',
            array($this, 'add_plan'));

        // delete plan
        add_action( 'wp_ajax_stripe_delete_plan',
            array($this, 'delete_plan'));

        // enable plan
        add_action( 'wp_ajax_stripe_enable_plan',
            array($this, 'enable_plan'));

        // disable plan
        add_action( 'wp_ajax_stripe_disable_plan',
            array($this, 'disable_plan'));

        // Save token details
        add_action( 'wp_ajax_stripe_save_token',
            array($this, 'save_token'));
        
        // Save card details
        add_action( 'wp_ajax_stripe_save_card',
            array($this, 'save_card'));

        // Create customer
        add_action( 'wp_ajax_stripe_create_customer',
            array($this, 'create_customer'));

        // Create subscription
        add_action( 'wp_ajax_stripe_create_subscription',
            array($this, 'create_subscription'));
    }

    /**
     * Ajax hook for get_available_plans()
     */
    public static function get_available_plans() {
        echo Stripe::get_available_plans();
        wp_die();
    }

    /**
     * Ajax hook for getting the available plans table
     */
    public static function get_available_plans_admin_table() {
        echo Stripe::get_available_plans_admin_table();
        wp_die();
    }

    /**
     * Ajax hook for getting the available coupons table
     */
    public static function get_available_coupons_admin_table() {
        echo Stripe::get_available_coupons_admin_table();
        wp_die();
    }

    /**
     * Ajax hook for getting the subscription list table
     */
    public static function get_subscription_list_admin_table() {
        echo Stripe::get_subscription_list_admin_table();
        wp_die();
    }

    /**
     * Ajax hook for getting the subscription list table
     */
    public static function get_customer_list_admin_table() {
        echo Stripe::get_customer_list_admin_table();
        wp_die();
    }


    /**
     * Ajax hook for adding a coupon
     */
    public static function add_coupon() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully added Coupon'
        );

        if(isset($_REQUEST['fuse-ajax']) && isset($_REQUEST['data'])) {

            $data = $_REQUEST['data']['newcoupon'];
            
            if ( !wp_verify_nonce( $data['nonce'], "fuse_submitting_form")) {
                $response['error']  = true;
                $response['message'] = 'Could not add Coupon';
            } else {

                $data = Stripe::prepare_request($data);
                $response['data'] = $data;

                $coupon = Stripe::create_coupon($data);
                if($coupon) {
                    if(isset($coupon['error'])) {
                        $response['error']          = true;
                        $response['message']        = $coupon['error'];
                    }
                }
                
                $response['stripe_response']    = $coupon;
            }

            echo json_encode($response);
        }

        wp_die();
    }


    /**
     * Ajax hook for deleting a coupon
     */
    public static function delete_coupon() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully deleted Coupon'
        );

        if(isset($_REQUEST['fuse-ajax']) && isset($_REQUEST['data'])) {

            $data = $_REQUEST['data']['delcoupon'];

            if ( !wp_verify_nonce( $data['nonce'], "fuse_submitting_form")) {
                $response['error']  = true;
                $response['message'] = 'Could not delete Coupon';
            } else {

                $data = Stripe::prepare_request($data);
                $response['data'] = $data;

                $id = $data['id'];
                
                $coupon = Stripe::delete_coupon($id);
                if($coupon) {
                    if(isset($coupon['error'])) {
                        $response['error']          = true;
                        $response['message']        = $coupon['error'];
                    }
                }

                $response['stripe_response']    = $coupon;
                
            }

            echo json_encode($response);
        }

        wp_die();
    }

    /**
     * Ajax hook for enabling coupon
     */
    public static function enable_coupon() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully enabled Coupon ' . $_REQUEST['coupon_id']
        );

        if(isset($_REQUEST['coupon_id'])) {

            $coupon_id = $_REQUEST['coupon_id'];

            if(!Commerce\enable_coupon($coupon_id, true)) {
                $response['error']      = true;
                $response['message']    = 'Coupon does not exist';
            }

            echo json_encode($response);
        }

        wp_die();
    }

    /**
     * Ajax hook for disabling coupon
     */
    public static function disable_coupon() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully disabled Coupon ' . $_REQUEST['coupon_id']
        );

        if(isset($_REQUEST['coupon_id'])) {

            $coupon_id = $_REQUEST['coupon_id'];

            if(!Commerce\disable_coupon($coupon_id, true)) {
                $response['error']      = true;
                $response['message']    = 'Coupon does not exist';
            }

            echo json_encode($response);
        }

        wp_die();
    }

    /**
     * Ajax hook for adding a plan
     */
    public static function add_plan() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully added Plan'
        );

        if(isset($_REQUEST['fuse-ajax']) && isset($_REQUEST['data'])) {

            $data = $_REQUEST['data']['newplan'];

            if ( !wp_verify_nonce( $data['nonce'], "fuse_submitting_form")) {
                $response['error']  = true;
                $response['message'] = 'Could not add Plan';
            } else {

                $data = Stripe::prepare_request($data);
                $response['data'] = $data;

                $coupon = Stripe::create_plan($data);
                if($coupon) {
                    if(isset($coupon['error'])) {
                        $response['error']          = true;
                        $response['message']        = $coupon['error'];
                    }
                }

                $response['stripe_response']    = $coupon;
            }

            echo json_encode($response);
        }

        wp_die();
    }


    /**
     * Ajax hook for deleting a plan
     */
    public static function delete_plan() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully deleted Plan'
        );

        if(isset($_REQUEST['fuse-ajax']) && isset($_REQUEST['data'])) {

            $data = $_REQUEST['data']['delplan'];

            if ( !wp_verify_nonce( $data['nonce'], "fuse_submitting_form")) {
                $response['error']  = true;
                $response['message'] = 'Could not delete Plan';
            } else {

                $data = Stripe::prepare_request($data);
                $response['data'] = $data;

                $id = $data['id'];

                $coupon = Stripe::delete_plan($id);
                if($coupon) {
                    if(isset($coupon['error'])) {
                        $response['error']          = true;
                        $response['message']        = $coupon['error'];
                    }
                }

                $response['stripe_response']    = $coupon;

            }

            echo json_encode($response);
        }

        wp_die();
    }

    /**
     * Ajax hook for enable the plan
     */
    public static function enable_plan() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully enabled Plan ' . $_REQUEST['plan_id']
        );

        if(isset($_REQUEST['plan_id'])) {

            $plan_id = $_REQUEST['plan_id'];
            if(function_exists('\\Fuse\\Commerce\\update_plan_meta')) {
                $stripe_plan = Stripe::get_one_plan($plan_id);
                \Fuse\Commerce\update_plan_meta($plan_id, 'price',              $stripe_plan->amount);
                \Fuse\Commerce\update_plan_meta($plan_id, 'currency',           $stripe_plan->currency);
                \Fuse\Commerce\update_plan_meta($plan_id, 'interval',           $stripe_plan->interval);
                \Fuse\Commerce\update_plan_meta($plan_id, 'interval_count',     $stripe_plan->interval_count);
                \Fuse\Commerce\update_plan_meta($plan_id, 'trial_period_days',  $stripe_plan->trial_period_days);
            }
            
            if(!Commerce\enable_plan($plan_id, true)) {
                $response['error']      = true;
                $response['message']    = 'Plan does not exist';
            }
            
            echo json_encode($response);
        }
        
        wp_die();
    }

    /**
     * Ajax hook for disabling the plan
     */
    public static function disable_plan() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully disabled Plan ' . $_REQUEST['plan_id']
        );

        if(isset($_REQUEST['plan_id'])) {

            $plan_id = $_REQUEST['plan_id'];

            if(!Commerce\disable_plan($plan_id, true)) {
                $response['error']      = true;
                $response['message']    = 'Plan does not exist';
            }

            echo json_encode($response);
        }

        wp_die();
    }
    
    /**
     * Ajax hook for saving card number to user ID
     */
    public static function save_card() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully saved card'
        );
        
        if(isset($_REQUEST['stripe_card_id']) && isset($_REQUEST['stripe_card_id'])) {
            Stripe::save_card($_REQUEST['stripe_card_id'], $_REQUEST['user_id']);              
        } else {
            $response = array(
                'error'     => true,
                'message'   => 'User ID or Card ID not set'
            );
        }

        echo json_encode($response);
        
        wp_die();
    }

    /**
     * Ajax hook for saving token number to user ID
     */
    public static function save_token() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully saved token'
        );

        if(isset($_REQUEST['stripe_token_id']) && isset($_REQUEST['stripe_token_id'])) {
            Stripe::save_token($_REQUEST['stripe_token_id'], $_REQUEST['user_id']);
        } else {
            $response = array(
                'error'     => true,
                'message'   => 'User ID or Token ID not set'
            );
        }

        echo json_encode($response);

        wp_die();
    }

    /**
     * Ajax hook for creating customer from token
     */
    public static function create_customer() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully created customer'
        );

        if(isset($_REQUEST['stripe_token_id']) && isset($_REQUEST['stripe_token_id'])) {
            $customer_id = Stripe::create_customer($_REQUEST['stripe_token_id'], $_REQUEST['user_id']);
            $response['customer_id'] = $customer_id;
        } else {
            $response = array(
                'error'     => true,
                'message'   => 'User ID or Token ID not set'
            );
        }

        echo json_encode($response);

        wp_die();
    }

    /**
     * Ajax hook for creating subscription from customer id
     */
    public static function create_subscription() {
        $response = array(
            'error'     => false,
            'message'   => 'Successfully created subscription'
        );
        
        if(isset($_REQUEST['stripe_customer_id']) && isset($_REQUEST['stripe_plan'])) {
            if(!isset($_REQUEST['tax_percent'])) {
                $_REQUEST['tax_percent'] = false;
            }

            $sub = Stripe::create_subscription($_REQUEST['stripe_customer_id'], $_REQUEST['stripe_plan'], $_REQUEST['tax_percent']);
            if($sub['error']) {
                $response = array(
                    'error'     => true,
                    'message'   => $sub['error']
                );
            }

        } else {
            $response = array(
                'error'     => true,
                'message'   => 'User ID or Token ID not set'
            );
            
        }

        echo json_encode($response);

        wp_die();
    }

}

new Stripe_Ajax();