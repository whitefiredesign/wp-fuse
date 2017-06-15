<?php
namespace Fuse;

class MailChimp {

    public static $slug = 'fuse-mailchimp';
    
    public $api;
    public $list_id;
    
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
        $options    = get_option('_mchimp_options', true);

        if($options && is_array($options)) {
            foreach ($options as $k => $v) {
                if ($v == '') {
                    unset($options[$k]);
                }
            }

            return $options;
        }

        return false;
    }

    public function insert($email, $merge_fields = false, $status = 'pending') {

        $mc_data = array(
            'email_address' => $email,
            'status'        => $status,
        );

        $insert = $this->api->post("lists/".$this->list_id."/members", $mc_data);

        if($merge_fields) {
            $this->update($email, $merge_fields);
        }

        return $insert;
    }

    public function update($email, $merge_fields, $meta = false) {

        $subscriber_hash = $this->api->subscriberHash($email);
        return $this->api->patch("lists/$this->list_id/members/$subscriber_hash", array(
            'merge_fields' => $merge_fields,
            $meta,
        ));
    }
    
}

// If require Fuse.Mailchimp support in admin
$support = get_theme_support( 'Fuse.MailChimp' );
if($support) {
    MailChimp::dashboard();
}