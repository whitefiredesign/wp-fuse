<?php
function userinfo_js() {

    if(is_user_logged_in()) {
        $output="<script> var userObject = ".json_encode(wp_get_current_user()).";</script>";
        echo $output;
    } else {
        $output="<script> var userObject; </script>";
        echo $output;
    }
}
add_action('wp_head','userinfo_js');