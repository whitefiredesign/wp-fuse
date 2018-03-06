<?php
if(!function_exists('array_pluck')) {
    function array_pluck($array, $field, $value) {
        foreach($array as $k => $v)  {
            if ( $v[$field] === $value )
                return $k;
        }
        return false;
    }
}