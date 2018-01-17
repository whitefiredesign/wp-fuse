<?php

namespace Fuse;

/**
 * Listens for Stripe events
 * and converts them to WP hooks
 * Class Stripe_Hook
 * @package Fuse
 */
class Stripe_Hook {

    public function __construct() {
        $this->listener();
    }

    public function listener() {
        if (isset($_GET['wps-listener']) && $_GET['wps-listener'] == 'stripe') {

            $input  = @file_get_contents("php://input");
            $json   = json_decode($input);

            do_action('stripe_' . $json->type,  $json->data->object);

            return false;
        }
    }
}

add_action('fuse-loaded', function() {
    new Stripe_Hook();
});
