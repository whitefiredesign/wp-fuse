<?php namespace Fuse;

/**
 * Gateways may return value in smallest currency denominator
 * This makes the value more user friendly.
 * @param bool $amount
 * @return bool
 */

function clean_currency($amount = false) {
    if(!$amount || !is_numeric($amount)) {
        return false;
    }

    return number_format(($amount /100), 2, '.', ' ');
}