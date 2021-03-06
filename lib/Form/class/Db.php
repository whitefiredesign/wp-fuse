<?php
namespace Fuse;

class Form_Db {

    protected $form_register_table  = 'forms';
    protected $form_save_table      = 'form_save';
    
    public function db() {

        if(!db_table_exists($this->form_register_table, true)) {
            $this->tables();
        }
    }

    /**
     * @return bool
     *
     * Creates the form tables
     */
    protected function tables() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate    = $wpdb->get_charset_collate();
        $reg_sql            = "CREATE TABLE $wpdb->prefix$this->form_register_table (
          id      mediumint(10) NOT NULL AUTO_INCREMENT,
          name    text NOT NULL,
          fields  longtext NOT NULL,
          msg     longtext NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";

        $sav_sql            = "CREATE TABLE $wpdb->prefix$this->form_save_table (
          id      mediumint(10) NOT NULL AUTO_INCREMENT,
          form_id mediumint(10) NOT NULL,
          data    longtext NOT NULL,
          time    datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";

        dbDelta( $reg_sql );
        dbDelta( $sav_sql );

        return false;
    }


    /**
     * Check if form exists
     *
     * @param $name
     * @return bool
     */
    public function exists($name) {
        global $wpdb;

        $result = $wpdb->get_row( "SELECT * FROM $wpdb->prefix$this->form_register_table WHERE name = '$name'" );
        
        if(empty($result)) {
            return false;
        }
        
        return (int) $result->id;
    }


    /**
     * Inserts new form into _forms
     *
     * @param $name
     * @param $fields
     * @return bool
     */
    public function insert($name, $fields) {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix.$this->form_register_table,
            array(
                'name'      => $name,
                'fields'    => serialize($fields)
            ),
            array(
                '%s',
                '%s'
            )
        );

        return false;

    }


    /**
     * Update existing form in _forms
     *
     * @param $id
     * @param $name
     * @param $fields
     */
    public function update($id, $name = false, $fields = false) {
        global $wpdb;

        $char       = array();
        $update_arr = array();

        if($name) {
            $update_arr['name']     = $name;
            array_push($char, '%s');
        }

        if($fields) {
            $update_arr['fields']   = maybe_serialize($fields);
            array_push($char, '%s');
        }

        $wpdb->update(
            $wpdb->prefix.$this->form_register_table,
            $update_arr,
            array( 'id' => $id ),
            $char,
            array( '%d' )
        );
    }


    /**
     * Save submitted form data
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public function save_data($id, $data) {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix.$this->form_save_table,
            array(
                'form_id'   => $id,
                'data'      => serialize($data),
                'time'      => current_time( 'mysql' )
            ),
            array(
                '%d',
                '%s',
                '%s'
            )
        );

        return false;
    }

    /**
     * Get saved data from form
     * 
     * @param $id / $name
     * @return array|null|object
     */
    public function get_saved_data($id = false) {
        global $wpdb;
        
        if(is_string($id)) {
            $id = $this->exists($id);
        }

        if(!$id) {
            $results    = $wpdb->get_results("SELECT * FROM $wpdb->prefix$this->form_save_table");
        } else {
            $results    = $wpdb->get_results("SELECT * FROM $wpdb->prefix$this->form_save_table WHERE form_id=$id");
        }

        $i = 0;
        foreach($results as $result) {
            $results[$i]->data = unserialize($results[$i]->data);

            $i++;
        }

        return $results;
    }

    /**
     * Gets a list of the forms
     * @return array|null|object
     */
    public function get_forms() {
        global $wpdb;

        $results    = $wpdb->get_results("SELECT * FROM $wpdb->prefix$this->form_register_table");

        $i = 0;
        foreach($results as $result) {
            $results[$i]->fields = unserialize($results[$i]->fields);

            unset($results[$i]->fields['config']);

            $field_i = 0;
            foreach($results[$i]->fields as $field) {
                if($field['type']=='break' || $field['type']=='submit') {
                    unset($results[$i]->fields[$field_i]);
                }
                $field_i ++;
            }

            $i++;
        }

        return $results;

    }

    /**
     * Return 1 form
     * @param $name
     * @return array|null|object|void
     */
    public function get_form($name, $sanitized = false) {
        global $wpdb;

        $form = $wpdb->get_row("SELECT * FROM $wpdb->prefix$this->form_register_table WHERE name='$name'");
        if(!$form) {
            return false;
        }
        
        $form->fields = unserialize($form->fields); 

        if($sanitized) {
            unset($form->fields['config']);

            $field_i = 0;
            foreach ($form->fields as $field) {
                if ($field['type'] == 'break' || $field['type'] == 'submit') {
                    unset($form->fields[$field_i]);
                }
                $field_i++;
            }
        }

        return $form;
    }
    
    
}