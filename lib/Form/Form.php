<?php
namespace Fuse;

include_once(__DIR__ . '/class/Helper.php');
include_once(__DIR__ . '/class/Db.php');
include_once(__DIR__ . '/class/Email.php');
include_once(__DIR__ . '/class/Builder.php');

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
    }
    
}

// If require Fuse.Gmap support in admin
$support = get_theme_support( 'Fuse.Form' );
if($support) {
    Form::dashboard();

    new Form();
}