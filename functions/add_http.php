<?php
if(!function_exists('add_http')) {
    function add_http($url, $secure = false) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http".($secure ? 's' : null) . "://" . $url;
        }
        return $url;
    }
}
