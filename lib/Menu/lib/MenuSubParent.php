<?php
namespace Fuse\Util;

class MenuSubParent {


    /****
     * @var array
     *
     *
     * Adds 'level' argument option to wp_nav_menu
     * to generate a second layer navigation
     * based on the selected parent
     *
     */

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize plugin
        add_action( 'init', array( $this, 'init' ), 1 );
    }


    public function init() {
        // Add filters
        add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
    }

    // Variables
    private $_menu_items = array();

    /**
     * Extends the default function
     *
     * @param array   $sorted_menu_items
     * @param object  $args
     * @return array
     */
    public function wp_nav_menu_objects( $sorted_menu_items, $args ) {
        // Add additional args
        if ( ! isset( $args->level ) )
            $args->level = 0;
        if ( ! isset( $args->child_of ) )
            $args->child_of = '';

        // Check if we need to do anything
        if ( ! $sorted_menu_items || ( $args->level == 0 && $args->child_of == '' ) )
            return $sorted_menu_items;

        // Build a tree
        $this->_menu_items = $sorted_menu_items;
        $temp_array = array();
        foreach ( $this->_menu_items as $item ) {
            $temp_array[ $item->menu_item_parent ][] = $item;
        }
        
        $tree = $this->build_items_tree( $temp_array, array_values($temp_array)[0]);

        // Prepare updated items
        $updated_items = $this->get_level_items( $tree, $args->level, $args->child_of );

        // Start array keys from 1
        $updated_items = array_filter( array_merge( array( 0 ), $updated_items ) );

        // Return updated items
        return $updated_items;
    }

    /**
     * Builds a tree of menu items recursively
     *
     * @param  array  $list
     * @param  object $parent
     * @return array
     */
    private function build_items_tree( &$list, $parent, $level = 1 ) {
        $tree = array();

        foreach ( $parent as $k => $l ) {
            if ( isset( $list[ $l->ID ] ) )
                $l->children = $this->build_items_tree( $list, $list[ $l->ID ], $level + 1 );

            $l->level = $level;
            $tree[] = $l;
        }

        return $tree;
    }

    /**
     * Gets items from a particular level
     *
     * @param  array   $tree
     * @param  integer $level
     * @param  string  $child_of
     * @return array
     */
    private function get_level_items( $tree, $level = 1, $child_of = '' ) {
        $items = array();

        foreach ( $tree as $item ) {
            $child_of_flag = false;

            if ( $child_of != '' ) {
                if ( gettype( $child_of ) == 'integer' && $item->menu_item_parent != $child_of )
                    $child_of_flag = true;
                elseif ( gettype( $child_of ) == 'string' && $item->menu_item_parent != $this->get_menu_id_from_title( $child_of ) )
                    $child_of_flag = true;
            }

            if ( $item->level == $level && ! $child_of_flag ) {
                unset( $item->children );
                $items[] = $item;
            }

            if ( isset( $item->children ) && $item->children )
                $items = $items + $this->get_level_items( $item->children, $level, $child_of );
        }

        return $items;
    }

    /**
     * Gets a menu ID based on the title of the item
     *
     * @param  string $name
     * @return string
     */
    private function get_menu_id_from_title( $name = '' ) {
        foreach ( $this->_menu_items as $item ) {
            if ( $item->title == $name )
                return $item->ID;
        }

        return '';
    }
}