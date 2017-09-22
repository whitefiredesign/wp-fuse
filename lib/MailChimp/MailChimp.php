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

    /**
     * Check if email already exists in MC
     * 
     * @param $email
     * @return bool
     */
    public function check_exists($email) {
        $subscriber_hash    = $this->api->subscriberHash($email);
        $result = $this->api->get("lists/$this->list_id/members/$subscriber_hash");
        
        if($result['status']=='404') {
            return false;
        }
        
        return true;
    }

    /**
     * Inserts a new subscriber to 'Pending'
     *
     * @param $email
     * @param bool $merge_fields
     * @param string $status
     * @return array|false
     */
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

    /**
     * Sets subscriber to 'Unsubscribed'
     *
     * @param $email
     * @return array|false
     */
    public function unsubscribe($email) {
        $mc_data = array(
            'status'        => 'unsubscribed'
        );

        $subscriber_hash    = $this->api->subscriberHash($email);
        $unsubscribe        = $this->api->patch("lists/".$this->list_id."/members/".$subscriber_hash, $mc_data);

        return $unsubscribe;
    }

    /**
     * Sets subscriber to 'Subscribed'
     *
     * @param $email
     * @return array|false
     */
    public function subscribe($email) {
        $mc_data = array(
            'status'        => 'subscribed'
        );

        $subscriber_hash = $this->api->subscriberHash($email);
        $subscribe = $this->api->patch("lists/".$this->list_id."/members/".$subscriber_hash, $mc_data);
        
        return $subscribe;
    }

    /**
     * Updates subscriber meta data
     *
     * @param $email
     * @param $merge_fields
     * @param bool $meta
     * @return array|false
     */
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