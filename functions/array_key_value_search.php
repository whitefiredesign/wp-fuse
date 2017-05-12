<?php
if(!function_exists('array_key_value_search')) {
    function array_key_value_search($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, array_key_value_search($subarray, $key, $value));
            }
        }

        return $results;
    }
}