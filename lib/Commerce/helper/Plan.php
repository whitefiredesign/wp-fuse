<?php
/**
 * All of the functions for
 * getting / updating and deleting
 * PLANS
 */

namespace Fuse\Commerce;

/**
 * Returns the plan
 * @param $plan_id
 * @return null|string
 */
function get_plan($plan_id) {
    global $wpdb;

    $q = $wpdb->get_results(
        "SELECT * FROM " . $wpdb->prefix . "commerce_plans
                    WHERE plan_id = '{$plan_id}' LIMIT 1");

    if(!empty($q)) {
        return $q[0];
    }

    return false;
}

/**
 * Inserts plan
 * @param $plan_id
 * @param string $status
 * @param string $vendor
 * @return bool|false|int
 */
function insert_plan($plan_id, $status = 'disabled', $vendor = 'stripe') {
    global $wpdb;

    if(!get_plan($plan_id)) {

        $args['plan_id'] = $plan_id;
        $args['vendor'] = $vendor;
        $args['status'] = $status;

        return $wpdb->insert($wpdb->prefix . 'commerce_plans', $args, array('%s', '%s', '%s'));
    }

    return false;
}

/**
 * Updates plan
 * @param $plan_id
 * @param string $status
 * @param string $vendor
 * @return bool|false|int
 */
function update_plan($plan_id, $status = 'disabled', $vendor = 'stripe') {
    global $wpdb;

    if(get_plan($plan_id)) {
        $args['plan_id']    = $plan_id;
        $args['vendor']     = $vendor;
        $args['status']     = $status;

        return $wpdb->update($wpdb->prefix . 'commerce_plans',
            array(
                'vendor'    => $vendor,
                'status'    => $status
            ),
            array(
                'plan_id' => $plan_id
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
        return insert_plan($plan_id, $status, $vendor);
    }

}

/**
 * Enable plan
 * @param $plan_id
 * @param bool $force_create
 * @return bool|false|int
 */
function enable_plan($plan_id, $force_create = false) {
    $plan = get_plan($plan_id);
    if($plan) {
        return update_plan($plan_id, 'enabled');
    } else {
        if($force_create) {
            return insert_plan($plan_id, 'enabled');
        }
    }
    
    return false;
}

/**
 * Disable plan
 * @param $plan_id
 * @param bool $force_create
 * @return bool|false|int
 */
function disable_plan($plan_id, $force_create = false) {
    $plan = get_plan($plan_id);
    if($plan) {
        return update_plan($plan_id, 'disabled');
    } else {
        if($force_create) {
            insert_plan($plan_id, 'disabled');
        }
    }
    
    return false;
}

/**
 * Returns plan metadata
 * @param $plan_id
 * @param $meta_key
 * @param bool $single
 * @return mixed
 */
function get_plan_meta($plan_id, $meta_key, $single = false) {
    // Get the SQL id of plan
    $plan   = get_plan($plan_id);
    $id     = $plan->id;
    
    return get_metadata('plan', $id, $meta_key, $single);
}

/**
 * Updates plan metadata
 * @param $plan_id
 * @param $meta_key
 * @param $meta_value
 * @param string $prev_value
 * @return mixed
 */
function update_plan_meta($plan_id, $meta_key, $meta_value, $prev_value = '') {
    // Get the SQL id of plan
    $plan   = get_plan($plan_id);
    $id     = $plan->id;

    return update_metadata('plan', $id, $meta_key, $meta_value, $prev_value);
}

/**
 * Deletes plan metadata
 * @param $plan_id
 * @param $meta_key
 * @param string $meta_value
 * @return mixed
 */
function delete_plan_meta($plan_id, $meta_key, $meta_value = '') {
    // Get the SQL id of plan
    $plan   = get_plan($plan_id);
    $id     = $plan->id;

    return delete_metadata('plan', $id, $meta_key, $meta_value);
}
