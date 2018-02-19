<?php
namespace Fuse;

include_once(__DIR__ . '/class/Helper.php');
include_once(__DIR__ . '/class/Db.php');
include_once(__DIR__ . '/class/Builder.php');
include_once(__DIR__ . '/class/Shortcode.php');

class Form extends Form_Builder {


    public static $slug     = 'fuse-form';
    
    
    public function __construct($name = false, $fields = array(), $update = false, $ajax = false, $atts = false) {

        $db         = new Form_Db();
        
        // If just prepping the database
        if(!$name) {
            $db->db();
        } else {
            // If creating a form

            // Assigned in Form_Builder
            $this->name             = $name;
            $this->fields           = $fields;
            $this->messages         = json_decode(file_get_contents(__DIR__ . '/default_messages.json'), true);
            $this->ajax             = $ajax;
            $this->scatts           = $atts;
            $this->_id              = $db->exists($this->name);

            // If force change field values
            $i = -1;
            if($atts['field-values'] && is_array($atts['field-values'])) {
                foreach($this->fields as $field) {
                    foreach($atts['field-values'] as $k => $v) {
                        if($field['name']==$k) {
                            $this->fields[$i]['value'] = $v;
                        }
                    }

                    $i++;

                }

            }

            // Assign default value to submit
            $i=0;
            foreach($this->fields as $k => $v) {
                // Ignore config
                if($k=='config') {
                } else {
                    $i++;
                    if(isset($v['type']) && $v['type']=='submit') {
                        $this->fields[$i]['name'] = $this->name.'_submit_btn';
                    }
                }
            }


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

                add_action('admin_enqueue_scripts', function() {
                    // jQuery UI
                    wp_enqueue_script('jquery-ui-core');
                    wp_enqueue_script('jquery-ui-tabs');
                    wp_enqueue_style('jquery-ui-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');
                    wp_enqueue_style('jquery-ui-base', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/theme.min.css');

                    // Datatables
                    wp_enqueue_script('jquery-datatables', 'https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js', array('jquery'), config::$version, false);
                    wp_enqueue_script('jquery-datatables-jq-ui', 'https://cdn.datatables.net/1.10.15/js/dataTables.jqueryui.min.js', array('jquery'), config::$version, false);
                    wp_enqueue_style('jquery-datatables-theme', 'https://cdn.datatables.net/1.10.15/css/dataTables.jqueryui.min.css');
                });
                
            }
        }
    }

    public static function assets() {

        if(config::$dev) {
            Util\Uglify::compile_single(__DIR__ . '/scripts/Form_Ajax.js',                  'min');
            Util\Uglify::compile_single(__DIR__ . '/scripts/jquery-serialize-object.js',    'min');
        }

        wp_register_script('Fuse.Form_Ajax', get_file_abspath(__FILE__) . '/scripts/Form_Ajax.min.js', array('jquery'), config::$version, true);
        wp_register_script('serialize-object', get_file_abspath(__FILE__) . '/scripts/jquery-serialize-object.min.js', array('jquery'), config::$version, true);

        wp_enqueue_script('serialize-object');
        wp_enqueue_script('Fuse.Form_Ajax');
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

    public static function a_fuse_form_submit() {
        if(isset($_REQUEST['data'])) {
            
            $fields = Form_Helper::get_form($_REQUEST['data']['form_name']);

            $_POST  = $_REQUEST['data'];

            $fm     = new Form($_REQUEST['data']['form_name'], $fields->fields, false, true);
            $fm->submit_success = (bool) $fm->submit_success;

            $messages = array(
                'success'                       => \Fuse\wrap_notice('success bg-success', $fields->fields['config']['shortcode_atts']['success-msg']),
                'on-success'                    => $fields->fields['config']['shortcode_atts']['on-success'],
                'additional-success-message'    => html_entity_decode($fields->fields['config']['shortcode_atts']['additional-success-message'])
            );

            echo wp_json_encode(array(
                'form_html'     => $fm->form()->html,
                'form_db'       => $fm,
                'form_data'     => $_REQUEST['data'],
                'form_fields'   => $fields->fields,
                'form_messages' => $messages,
                'post'          => $_POST,
                'submit_success'=> $fm->submit_success
            ));
        }

        wp_die();
    }
    
}

// If require Fuse.Gmap support in admin
$support = get_theme_support( 'Fuse.Form' );
if($support) {
    Form::dashboard();

    add_action( 'wp_ajax_fuse_form_submit',           '\Fuse\Form::a_fuse_form_submit' );
    add_action( 'wp_ajax_nopriv_fuse_form_submit',    '\Fuse\Form::a_fuse_form_submit' );

    add_action( 'wp_enqueue_scripts', '\Fuse\Form::assets');
    new Form();
}