<?php
namespace Fuse\Auth0;

class LoginOption {

    public static function template($option = false) {
        if(!$option) {
            return false;
        }

        ob_start();
        if($option=='google') {
            include_once(\Fuse\config::$viewspath . '/client/parts/login-google.php');
        }
        if($option=='twitter') {
            include_once(\Fuse\config::$viewspath . '/client/parts/login-twitter.php');
        }
        if($option=='facebook') {
            include_once(\Fuse\config::$viewspath . '/client/parts/login-facebook.php');
        }
        if($option=='linkedin') {
            include_once(\Fuse\config::$viewspath . '/client/parts/login-linkedin.php');
        }
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }
}