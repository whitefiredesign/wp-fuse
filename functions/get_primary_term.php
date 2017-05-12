<?php
/**
 * Depends on Yoast SEO
 *
 * @param $post_id
 * @param $tax
 * @return array|bool|null|WP_Error|WP_Term|WPSEO_Primary_Term
 */
function get_primary_term($post_id, $tax) {

    if (class_exists('WPSEO_Primary_Term')) {
        $term = new WPSEO_Primary_Term($tax, $post_id);
        if ($term->get_primary_term()) {
            $primary = $term->get_primary_term();

            $term = get_term($primary, $tax);
            $term->permalink = get_term_link($primary, $tax);
            return $term;
        }
        return false;
    }
    
    return false;
}