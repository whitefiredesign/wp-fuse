<?php namespace Fuse; ?>

<?php
class Stripe_Helper {

    /**
     * Returns an object of available plans
     * @param bool $ajax
     * @return bool|string
     */
    public static function get_available_plans($ajax = true, $args = array()) {

        try {
            $output = self::sanitize_response(\Stripe\Plan::all($args));
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());
        }

        if($ajax) {
            echo $output;
            wp_die();
        }

        return json_decode($output);

    }

    /**
     * Displays the available plans results in a table
     * @param bool $ajax
     * @return mixed
     */
    public static function get_available_plans_admin_table($ajax = true) {

        $data = self::get_available_plans(false);

        ob_start();
        include_once(config::$viewspath . 'admin/subs/Stripe/admin-subscriptions-available-plans.php');
        $template = ob_get_contents();
        ob_end_clean();

        if($ajax) {
            echo $template;
            wp_die();
        }

        return $template;
    }

    /**
     * Returns an object of available coupons
     * @param bool $ajax
     * @return bool|string
     */
    public static function get_available_coupons($ajax = true, $args = array()) {
        
        try {
            $output = self::sanitize_response(\Stripe\Coupon::all($args));
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());
        }

        if($ajax) {
            echo $output;
            wp_die();
        }
        
        return json_decode($output);
    }

    /**
     * Displays the available plans results in a table
     * @param bool $ajax
     * @return mixed
     */
    public static function get_available_coupons_admin_table($ajax = true) {

        $data = self::get_available_coupons(false);

        ob_start();
        include_once(config::$viewspath . 'admin/subs/Stripe/admin-subscriptions-available-coupons.php');
        $template = ob_get_contents();
        ob_end_clean();

        if($ajax) {
            echo $template;
            wp_die();
        }

        return $template;
    }

    /**
     * Returns an object of subscriptions
     * @param bool $ajax
     * @return bool|string
     */
    public static function get_subscription_list($ajax = true, $args = array()) {

        try {
            $output = self::sanitize_response(\Stripe\Subscription::all($args));
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());
        }

        if($ajax) {
            echo $output;
            wp_die();
        }

        return json_decode($output);
    }

    /**
     * Displays the subscription list results in a table
     * @param bool $ajax
     * @return mixed
     */
    public static function get_subscription_list_admin_table($ajax = true) {

        // Add customer data to subscription
        $data = self::add_customers_to_subscriptions(self::get_subscription_list(false), self::get_customer_list(false));

        ob_start();
        include_once(config::$viewspath . 'admin/subs/Stripe/admin-subscriptions-subscription-list.php');
        $template = ob_get_contents();
        ob_end_clean();

        if($ajax) {
            echo $template;
            wp_die();
        }

        return $template;
    }

    /**
     * Returns an object of customers
     * @param bool $ajax
     * @return bool|string
     */
    public static function get_customer_list($ajax = true, $args = array()) {

        try {
            $output = self::sanitize_response(\Stripe\Customer::all($args));
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());
        }

        if($ajax) {
            echo $output;
            wp_die();
        }

        return json_decode($output);
    }

    /**
     * Displays the customer list results in a table
     * @param bool $ajax
     * @return mixed
     */
    public static function get_customer_list_admin_table($ajax = true) {

        // Add customer data to subscription
        $data = self::get_customer_list(false);

        ob_start();
        include_once(config::$viewspath . 'admin/subs/Stripe/admin-subscriptions-customer-list.php');
        $template = ob_get_contents();
        ob_end_clean();

        if($ajax) {
            echo $template;
            wp_die();
        }

        return $template;
    }

    /**
     * Adds the customer object to each subscription
     * @param array $subscriptions
     * @param array $customers
     * @return array
     */
    public static function add_customers_to_subscriptions($subscriptions = array(), $customers = array()) {

        $i      = 0;
        foreach($subscriptions as $subscription) {
            foreach($customers as $customer) {
                if($subscription->customer==$customer->id) {
                    $subscriptions[$i]->customer = $customer;
                }
            }
            $i++;
        }

        return $subscriptions;
    }

    /**
     * Gets the current mode (Live / Test)
     */
    public static function get_mode() {
        $options = Stripe::get_options();
        $mode = $options['mode'];

        return $mode;
    }

    /**
     * Sanitize response from Stripe
     */
    public static function sanitize_response($response) {
        if(!$response) {
            return false;
        }

        return json_encode($response->data);

    }
}
