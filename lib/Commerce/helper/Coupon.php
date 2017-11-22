<?php
/**
 * All of the functions for
 * getting / updating and deleting
 * PLANS
 */

namespace Fuse\Commerce;

/**
 * Returns the coupon
 * @param $plan_id
 * @return null|string
 */
function get_coupon($plan_id) {
    global $wpdb;

    $q = $wpdb->get_results(
        "SELECT * FROM " . $wpdb->prefix . "commerce_coupons
                    WHERE coupon_id = '{$plan_id}' LIMIT 1");

    if(!empty($q)) {
        return $q[0];
    }

    return false;
}

/**
 * Inserts coupon
 * @param $coupon_id
 * @param string $status
 * @param string $vendor
 * @return bool|false|int
 */
function insert_coupon($coupon_id, $status = 'disabled', $vendor = 'stripe') {
    global $wpdb;

    if(!get_coupon($coupon_id)) {

        $args['coupon_id'] = $coupon_id;
        $args['vendor'] = $vendor;
        $args['status'] = $status;

        return $wpdb->insert($wpdb->prefix . 'commerce_coupons', $args, array('%s', '%s', '%s'));
    }

    return false;
}

/**
 * Updates coupon
 * @param $coupon_id
 * @param string $status
 * @param string $vendor
 * @return bool|false|int
 */
function update_coupon($coupon_id, $status = 'disabled', $vendor = 'stripe') {
    global $wpdb;

    if(get_coupon($coupon_id)) {
        $args['coupon_id']    = $coupon_id;
        $args['vendor']     = $vendor;
        $args['status']     = $status;

        return $wpdb->update($wpdb->prefix . 'commerce_coupons',
            array(
                'vendor'    => $vendor,
                'status'    => $status
            ),
            array(
                'coupon_id' => $coupon_id
            ),
            array(
                '%s',
                '%s'
            ),
            array(
                '%s'
            )
        );

    } else {
        return insert_plan($coupon_id, $status, $vendor);
    }

}

/**
 * Enable coupon
 * @param $coupon_id
 * @param bool $force_create
 * @return bool|false|int
 */
function enable_coupon($coupon_id, $force_create = false) {
    $coupon = get_coupon($coupon_id);
    if($coupon) {
        return update_coupon($coupon_id, 'enabled');
    } else {
        if($force_create) {
            return insert_coupon($coupon_id, 'enabled');
        }
    }

    return false;
}

/**
 * Disable coupon
 * @param $coupon_id
 * @param bool $force_create
 * @return bool|false|int
 */
function disable_coupon($coupon_id, $force_create = false) {
    $coupon = get_coupon($coupon_id);
    if($coupon) {
        return update_coupon($coupon_id, 'disabled');
    } else {
        if($force_create) {
            insert_coupon($coupon_id, 'disabled');
        }
    }

    return false;
}

/**
 * Returns coupon metadata
 * @param $coupon_id
 * @param $meta_key
 * @param bool $single
 * @return mixed
 */
function get_coupon_meta($coupon_id, $meta_key, $single = false) {
    // Get the SQL id of coupon
    $coupon = get_coupon($coupon_id);
    $id     = $coupon->id;

    return get_metadata('coupon', $id, $meta_key, $single);
}

/**
 * Updates coupon metadata
 * @param $coupon_id
 * @param $meta_key
 * @param $meta_value
 * @param string $prev_value
 * @return mixed
 */
function update_coupon_meta($coupon_id, $meta_key, $meta_value, $prev_value = '') {
    // Get the SQL id of coupon
    $coupon = get_coupon($coupon_id);
    $id     = $coupon->id;

    return update_metadata('coupon', $id, $meta_key, $meta_value, $prev_value);
}

/**
 * Delete coupon metadata
 * @param $plan_id
 * @param $meta_key
 * @param string $meta_value
 * @return mixed
 */
function delete_coupon_meta($plan_id, $meta_key, $meta_value = '') {
    // Get the SQL id of plan
    $plan   = get_coupon($plan_id);
    $id     = $plan->id;

    return delete_metadata('coupon', $id, $meta_key, $meta_value);
}