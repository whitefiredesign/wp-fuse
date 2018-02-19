<?php
namespace Fuse;

class BlockUsers {

    public static $slug = 'fuse-blockusers';

    /**
     * BlockUsers constructor.
     */
    public function __construct() {
        add_action( 'wp_ajax_user_block',               array($this, 'block'));
        add_action( 'wp_ajax_nopriv_user_block',        array($this, 'block'));

        add_action( 'wp_ajax_user_unblock',             array($this, 'unblock'));
        add_action( 'wp_ajax_nopriv_user_unblock',      array($this, 'unblock'));
    }

    /**
     * Block User
     * @param $user_id
     * @return array
     */
    public static function block($user_id) {
        $ajax   = false;
        $output = array(
            'errors'    => false,
            'success'   => true,
            'function'  => 'user_block'
        );

        if($_REQUEST['blockusers-ajax']) {
            $ajax       = true;
            $user_id    = $_REQUEST['user_id'];
        }

        if(!user_id_exists($user_id)) {
            $output['success']  = false;
            $output['errors']   = 'User ID does not exist';
        } else {
            $output['user_id']  = $user_id;

            try {
                update_user_meta($user_id, 'user_blocked', true);
            } catch(\Exception $e) {
                $output['success']  = false;
                $output['errors']   = $e->getMessage();
            }
        }

        if($ajax) {
            echo json_encode($output);
            die();
        }

        return $output;
    }

    /**
     * Unblock User
     * @param $user_id
     * @return array
     */
    public static function unblock($user_id) {
        $ajax   = false;
        $output = array(
            'errors'    => false,
            'success'   => true,
            'function'  => 'user_unblock'
        );

        if($_REQUEST['blockusers-ajax']) {
            $ajax       = true;
            $user_id    = $_REQUEST['user_id'];
        }

        if(!user_id_exists($user_id)) {
            $output['success']  = false;
            $output['errors']   = 'User ID does not exist';
        } else {
            $output['user_id']  = $user_id;

            try {
                delete_user_meta($user_id, 'user_blocked', true);
            } catch(\Exception $e) {
                $output['success']  = false;
                $output['errors']   = $e->getMessage();
            }
        }


        if($ajax) {
            echo json_encode($output);
            die();
        }

        return $output;
    }

    /**
     * Check if user is blocked
     * @param $user_id
     * @return mixed
     */
    public static function is_blocked($user_id) {
        return get_user_meta($user_id, 'user_blocked', true);
    }
}

// If require Fuse.Gmap support in admin
$support = get_theme_support( 'Fuse.BlockUsers' );
if($support) {

    new BlockUsers();
}