<?php
function get_view_count($post_id = false) {
    if(!$post_id) {
        $post_id = get_the_ID();
    }

    $view_count = get_post_meta($post_id, '_fuse_post_views_count', true);
    if($view_count) {
        return $view_count;
    }

    return 1;
}