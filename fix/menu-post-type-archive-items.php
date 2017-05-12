<?php

/**
 * Register Archive Pages Taxonomy
 */
add_action( 'init', function () {
    register_taxonomy( 'archive_pages', 'nav_menu_item', array(
        'labels' => array(
            'name' => 'Archive Pages',
            'singular_name' => 'Archive Page',
        ),
        'show_ui' => false,
        'show_in_nav_menus' => true,
        'query_var' => false,
        'rewrite' => false,
    ) );
} );

add_action( 'wp_loaded', function () {
    static $terms_created = false;
    if ( ! $terms_created ) {
        $post_types = get_post_types( array(
            'publicly_queryable' => true,
            '_builtin' => false,
        ), 'objects' );

        $post = get_post_type_object('post');
        $post_types[] = $post;

        $taxonomy = 'archive_pages';
        foreach ( $post_types as $term_name => $term ) {
            if ( term_exists( $term_name, $taxonomy ) ) {
                continue;
            }
            wp_insert_term( $term->label, $taxonomy, array( 'slug' => $term_name ) );
        }
        $terms_created = true;
    }
});

/**
 * Filter Term Link
 * Replace term link with corresponding archive page link
 *
 * @param string $link
 * @param object $term
 * @param string $taxonomy
 *
 * @return string $link
 */
add_filter( 'term_link', function ( $link, $term, $taxonomy ) {
    if ( 'archive_pages' !== $taxonomy ) {
        return $link;
    }
    $terms = namespace_get_archive_page_terms();
    $found_term = array_filter( $terms, function ( $current_term ) use ( $term ) {
        if ( $term->slug !== $current_term['name'] ) {
            return false;
        }
        return true;
    } );
    if ( ! empty( $found_term ) && ! empty( $found_term[ $term->slug ]['link'] ) ) {
        $link = $found_term[ $term->slug ]['link'];
    }
    return $link;
}, 10, 3 );
/**
 * Get Archive Page Terms
 *
 * @return array $terms
 */
function namespace_get_archive_page_terms() {
    if ( $terms = wp_cache_get( 'namespace_archive_page_terms' ) ) {
        return maybe_unserialize( $terms );
    }
    $post_types = get_post_types( array( 'public' => true ), 'names' );
    $terms = array_map( function ( $post_type ) {
        return array(
            'name' => $post_type,
            'link' => get_post_type_archive_link( $post_type ),
        );
    }, $post_types );
    wp_cache_set( 'namespace_archive_page_terms', maybe_serialize( $terms ), false, DAY_IN_SECONDS );
    return $terms;
}


/**
 * Add the current menu item class for post type archives.
 *
 * @param  array  $classes
 * @param  object $item
 * @return array  $classes
 */
add_filter( 'nav_menu_css_class', function ( $classes, $item ) {
    $taxonomy = 'archive_pages';
    if ( $taxonomy !== $item->object ) {
        return $classes;
    }
    $term = get_term( $item->object_id, $taxonomy );
    $post_type = $term->slug;
    if ( is_single() ) {
        $classes[] = 'current-menu-parent';
    }
    if ( is_singular( $post_type ) ) {
        $classes[] = 'current-menu-parent';
    }
    else if ( is_post_type_archive( $post_type ) ) {
        $classes[] = 'current-menu-item';
    }
    return $classes;
}, 10, 2 );