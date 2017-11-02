<?php namespace Fuse; ?>

<?php

/**
 * Class Ajax
 * Ajaxify helper functions
 * @package Fuse\Stripe
 */
class Stripe_Ajax {

    public function __construct() {
        
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
}

new Stripe_Ajax();