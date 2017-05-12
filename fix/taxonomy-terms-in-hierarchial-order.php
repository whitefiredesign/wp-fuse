<?php
class Taxonomy_Checklist_Tree {

    public function __construct() {
        add_filter( 'wp_terms_checklist_args', array( $this, 'category_tree_view' ), 1, 2 );
    }


    function category_tree_view( $args, $post_id ) {
        $args['checked_ontop'] = false;
        return $args;
    }
}


new Taxonomy_Checklist_Tree();