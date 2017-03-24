<?php
namespace Fuse;

class MailChimp {

    public static $slug = 'fuse-mailchimp';
    
    public $api;
    
    public function __construct() {
        $options = self::get_options();

        if($options) {
            $key = $options['api-key'];

            try {
                $this->api = new \DrewM\MailChimp\MailChimp($key);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return false;
    }

    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Mailchimp', 'Mailchimp', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-mailchimp.php');
            });
        });
    }

    public static function save_options($options) {
        update_option('_mchimp_options', $options);
        return get_option('_mchimp_options');
    }

    public static function get_options() {
        $options    = get_option('_mchimp_options');

        if($options) {
            foreach ($options as $k => $v) {
                if ($v == '') {
                    unset($options[$k]);
                }
            }

            return $options;
        }

        throw new \Exception("No options for MailChimp available");
    }
    
}

// If require Fuse.Mailchimp support in admin
$support = get_theme_support( 'Fuse.MailChimp' );
if($support) {
    MailChimp::dashboard();
}