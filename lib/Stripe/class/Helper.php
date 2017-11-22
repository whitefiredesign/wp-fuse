<?php namespace Fuse;
class Stripe_Helper  {

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
     * Returns the Stripe plan object
     * @param $plan_id
     * @return mixed
     */
    public static function get_one_plan($plan_id) {
        try {
            $output = self::sanitize_response(\Stripe\Plan::retrieve($plan_id));
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());
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
        include_once(config::$viewspath . 'admin/subs/Stripe/table/admin-subscriptions-available-plans.php');
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
        include_once(config::$viewspath . 'admin/subs/Stripe/table/admin-subscriptions-available-coupons.php');
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
        include_once(config::$viewspath . 'admin/subs/Stripe/table/admin-subscriptions-subscription-list.php');
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
        include_once(config::$viewspath . 'admin/subs/Stripe/table/admin-subscriptions-customer-list.php');
        $template = ob_get_contents();
        ob_end_clean();

        if($ajax) {
            echo $template;
            wp_die();
        }

        return $template;
    }

    /**
     * Create a coupon
     * @param $data
     * @return array|bool
     */
    public static function create_coupon($data) {

        try {
            $output = self::sanitize_response(\Stripe\Coupon::create($data));

            return json_decode($output);
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());

            return $output;
        }
        
    }

    /**
     * Delete a coupon
     * @param $id
     * @return array|bool
     */
    public static function delete_coupon($id) {

        try {
            $coupon = \Stripe\Coupon::retrieve($id);
            $output = $coupon->delete();
            if(function_exists('\\Fuse\\Commerce\\update_coupon')) {
                Commerce\update_coupon($id, 'deleted');
            }

            return json_decode($output);
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());

            return $output;
        }

    }

    /**
     * Create a plan
     * @param $data
     * @return array|bool
     */
    public static function create_plan($data) {

        try {
            $output = self::sanitize_response(\Stripe\Plan::create($data));

            return json_decode($output);
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());

            return $output;
        }

    }

    /**
     * Delete a plan
     * @param $id
     * @return array|bool
     */
    public static function delete_plan($id) {

        try {
            $plan = \Stripe\Plan::retrieve(trim($id));
            $output = $plan->delete();
            if(function_exists('\\Fuse\\Commerce\\update_plan')) {
                Commerce\update_plan($id, 'deleted');
            }

            return json_decode($output);
        } catch(\Exception $e) {
            $output = array('error' => $e->getMessage());

            return $output;
        }

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
    
    public static function prepare_request($object) {
        // Regex for checking date format dd-mm-yy
        $date_regex = '/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/';

        foreach($object as $k => $v) {

            // Unset keys with no values
            if($v=='') {
                unset($object[$k]);
            }

            // Convert string numbers to integers
            if(is_numeric($v)) {
                $v = (int) $v;
                $object[$k] = $v;
            }

            // Unset keys with 0 value
            if($v===0) {
                unset($object[$k]);
            }

            // Remove nonce
            if($k=='nonce') {
                unset($object[$k]);
            }

            // Convert dates to timestamps
            if(preg_match($date_regex, $v)) {
                $date       = trim($v);
                $bits       = explode("-", $date);
                $object[$k] = strtotime($bits[1]. '/' . $bits[0] . '/' . $bits[2]);
            }
        }
        
        return $object;
    }

    /**
     * Sanitize response from Stripe
     */
    public static function sanitize_response($response) {
        if(!$response) {
            return false;
        }

        if(isset($response->data)) {
            return json_encode($response->data);
        }

        return json_encode($response);

    }



    /**
     * Coupons
     */
    public static function get_coupon_meta($id, $key) {
        global $wpdb;

    }

    public static function update_coupon_meta($id, $key, $value) {
        global $wpdb;

    }

    public static function delete_coupon_meta($id, $key) {
        global $wpdb;

    }
}