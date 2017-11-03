<?php namespace Fuse; ?>

<?php

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
         * Add / Update / Delete methods
         */
        // add coupon
        add_action( 'wp_ajax_stripe_add_coupon',
            array($this, 'add_coupon'));

        // delete coupon
        add_action( 'wp_ajax_stripe_delete_coupon',
            array($this, 'delete_coupon'));
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


}

new Stripe_Ajax();