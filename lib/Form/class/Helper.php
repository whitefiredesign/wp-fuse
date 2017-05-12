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
    public function get_saved_data($id) {
        $db = new Form_Db();
        return $db->get_saved_data($id);
    }
    
}
