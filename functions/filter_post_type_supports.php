<?php
function filter_post_type_supports($post_types = array(), $feature) {
    $i = 0;
    foreach($post_types as $post_type) {

        /**
         * Automatically unset post type val
         */
        unset($post_types[$i]);

        /**
         * Automatically assume this post type not allowed
         */
        $allowed = false;
        $removed = array();


        /**
         * Allow only if user is logged in
         */
        if(is_user_logged_in()) {
            if(post_type_supports($post_type,  $feature)) {
                $post_types[$i] = $post_type;
                $allowed        = true;
            }
        }

        /**
         * If the _nopriv flag (public) has been added
         */
        if(post_type_supports($post_type,      $feature . '_nopriv')) {
            $post_types[$i]     = $post_type;
            $allowed            = true;
        }

        if(!$allowed) {
            $removed[] = $post_type;
        }

        $i++;
    }

    return array(
        'post_types'    => $post_types,
        'removed'       => $removed
    );
}