<?php
if(!function_exists('wp_fuse_get_countries')) {
    function wp_fuse_get_countries($to_top = array()) {
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}countries");
        if(!empty($to_top) && is_array($to_top)) {
            $i      = 0;
            $temp   = array();
            foreach($results as $result) {
                foreach($to_top as $ccode) {
                    if($result->country_code == $ccode) {
                        $temp[] = $results[$i];
                        unset($results[$i]);
                    }
                }

                $i ++;
            }

            $results = array_merge($temp, $results);
        }

        return $results;
    }
}