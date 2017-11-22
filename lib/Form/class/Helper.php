<?php
namespace Fuse;

class Form_Helper {
    
    /**
     * Filters field data for the save log
     *
     * @param $fields
     * @return array
     */
    public function filter_data($fields) {

        $filtered   = array();
        $allowed    = array('label'=>true, 'name' => true, 'group' => true, 'value' => true);

        foreach($fields as $field) {
            $filtered[] = array_filter(
                $field,
                function ($val, $key) use ($allowed) {
                    return isset($allowed[$key]) && (
                        $allowed[$key] === true || $allowed[$key] === $val
                    );
                },
                ARRAY_FILTER_USE_BOTH
            );
        }

        $i = 0;
        foreach($filtered as $el) {
            if(!$el['name'] || $el['name'] == '') {
                unset($filtered[$i]);
            }

            $i++;
        }

        return $filtered;
    }


    /**
     * Get saved data from form
     *
     * @param $id / $name
     * @return array|null|object
     */
    public static function get_saved_data($id = false) {
        $db = new Form_Db();
        return $db->get_saved_data($id);
    }
    
    /**
     * Get the forms
     */
    public static function get_forms() {
        $db = new Form_Db();
        return $db->get_forms();
    }

    /**
     * Get a single form
     */
    public static function get_form($name, $sanitized = false) {
        $db = new Form_Db();
        return $db->get_form($name, $sanitized);
    }

    /**
     * Modify the array to group the multifields into groups
     */
    public static function group_multival($fields_array) {

        $multival = array();
        
        $i = 0;
        foreach($fields_array as $field) {
            if(isset($field['group'])) {
                $multival[$field['group']][] = $field;
                unset($fields_array[$i]);
            }
            $i++;
        }

        foreach($multival as $key => $val) {
            $fields_array[] = array(
                'name' => $key,
                'data' => $val
            );
        }
        

        return $fields_array;
    }

    public static function get_admin_url() {
        return admin_url() . 'admin.php?page=' . Form::$slug;
    }
    
    public static function get_admin_edit_form_url($form_name) {
        if(!is_admin()) {
            return false;
        }    
        
        return self::get_admin_url() . '&form-edit=' . $form_name;
    }
}
