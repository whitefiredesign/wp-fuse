<?php
/**
 * Returns the files absolute path
 */
if(!function_exists('get_file_abspath')) {
    function get_file_abspath($file = __FILE__)
    {
        $link = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, dirname($file));

        if ($link) {
            return $link;
        }
    }
}