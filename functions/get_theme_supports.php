<?php
if(!function_exists('get_theme_supports')) {
    function get_theme_supports() {
        global $_wp_theme_features;
        
        return $_wp_theme_features;
    }
}

function get_fuse_supports() {
    $supports   = get_theme_supports();
    $res        = array();

    foreach($supports as $key => $val){

        $exp_key = explode('.', $key);
        if($exp_key[0] == 'Fuse'){
            $res[] = $key;
        }
    }
    
    if(isset($res)) {
        return $res;
    }
    
    return false;
}

