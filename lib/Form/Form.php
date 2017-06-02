<?php
namespace Fuse;

include_once(__DIR__ . '/class/Helper.php');
include_once(__DIR__ . '/class/Db.php');
include_once(__DIR__ . '/class/Email.php');
include_once(__DIR__ . '/class/Builder.php');
include_once(__DIR__ . '/class/Shortcode.php');

class Form extends Form_Builder {


    public static $slug     = 'fuse-form';
    
    
    public function __construct($name = false, $fields = false, $update = false) {

        $db         = new Form_Db();
        
        // If just prepping the database
        if(!$name) {
            $db->db();
        } else {
            // If creating a form

            // Assigned in Form_Builder
            $this->name     = $name;
            $this->fields   = $fields;
            $this->messages = json_decode(file_get_contents(__DIR__ . '/default_messages.json'), true);
            $this->_id      = $db->exists($this->name);

            // Check if form is in DB
            if($this->_id) {
            } else {
                $db->insert($this->name, $this->fields);
            }

            // If form needs updating
            if($update) {
                $db->update($this->_id, $this->name, $this->fields);
            }

        }
    }
    

    public static function dashboard() {
        add_action( 'admin_menu', function() {
            add_submenu_page(config::$slug, 'Form', 'Form', 'manage_options', self::$slug, function () {
                include_once(config::$viewspath . 'admin/fuse-dashboard-form.php');
            });
        });

        if(is_admin()) {
            if (isset($_GET['page']) && $_GET['page']=='fuse-form') {

                // jQuery UI
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_style('jquery-ui-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');
                wp_enqueue_style('jquery-ui-base', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/theme.min.css');

                // Datatables
                wp_enqueue_script('jquery-datatables', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), config::$version, false);
                wp_enqueue_script('jquery-datatables-jq-ui', 'https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js', array('jquery'), config::$version, false);
                wp_enqueue_style('jquery-datatables-theme', 'https://cdn.datatables.net/1.10.15/css/dataTables.jqueryui.min.css');

            }
        }
    }


    /**
     * Helper hooks
     */
    public static function get_forms() {
        return Form_Helper::get_forms();
    }
    
    public static function get_saved_data($id = false) {
        return Form_Helper::get_saved_data($id);
    }
    
}

// If require Fuse.Gmap support in admin
$support = get_theme_support( 'Fuse.Form' );
if($support) {
    Form::dashboard();

    new Form();
}