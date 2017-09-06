<?php
namespace Fuse;

function time_format($time) {

    $format = 'd-m-Y H:i:s';

    // Check if timestamp
    if(!((string) (int) $time === $time)
        && ($time <= PHP_INT_MAX)
        && ($time >= ~PHP_INT_MAX)) {

        $time = date($format, strtotime($time));
    } else {
        $time = date($format, $time);
    }
    

    return $time;
}