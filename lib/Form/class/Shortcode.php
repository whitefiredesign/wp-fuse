<?php
namespace Fuse;

class Form_Shortcode extends Form_Db {

    public function __construct() {
        add_shortcode( 'fuse-form', array($this, 'shortcode'));
    }

    public function shortcode($a) {
        $atts = shortcode_atts(array(
            'name'              => false,
            'success-msg'       => __('Your submission was successful'),
            'error-msg'         => __('Your submission was unsuccessful'),
            'validate_classes'  => array(),
            'field-values'      => false,
            
            // JS Code
            'on-success'        => false,
            'additional-success-message'=> false,
            'ajax'              => false
        ), $a, 'fuse-form');

        // If validate classes set
        $validate_classes = false;
        if($atts['validate_classes']) {
            $atts['validate_classes'] = str_replace(array('{','}'), '', $atts['validate_classes']);
            $temp = explode (',', $atts['validate_classes']);
            foreach ($temp as $pair) {
                list ($k,$v) = explode (':',$pair);
                $validate_classes[trim($k)] = trim($v);
            }
        }
    
        if(!$atts['name']) {
            return \Fuse\wrap_notice('error', __('No form has been specified.'));
        }
        
        $form   = $this->get_form($atts['name']);
        if( !$form || !$form->fields) {
            return \Fuse\wrap_notice('error', __('Form "'.$atts['name'].'" does not appear to exist.'));
        }

        $update = false;
        if($atts['ajax']) {
            $update = true;
            $form->fields['config']['shortcode_atts'] = $atts;
            //echo '<pre>';
            //print_r($form->fields['config']['shortcode_atts']);
            //echo '</pre>';
        }

        if($atts['field-values']) {
            $atts['field-values'] = (array) json_decode($atts['field-values']);
        }
        
        $fm     = new Form($atts['name'], $form->fields, $update, $atts['ajax'], $atts);

        // If validate classes set
        if($validate_classes) {
            $fm->validate_classes = $validate_classes;
        }

        if(!$fm->form()->submit_success) {
            return $fm->form()->html;
        } else {

            //ob_start();
            //echo '<pre>' . print_r($fm) . '</pre>';
            //$contents = ob_get_contents();
            //ob_end_clean();
            $output = '';

            $on_success = false;
            if($atts['on-success']) {
                $on_success = $atts['on-success'];
            }

            $additional_success_message = false;
            if($atts['additional-success-message']) {
                $additional_success_message = $atts['additional-success-message'];
            }
            
            if($on_success) {
                // Run any script if specified
                $output .= '<script type="text/javascript">' . $atts['on-success'] . '</script>';
            }

            $output .= \Fuse\wrap_notice('success bg-success', $atts['success-msg']);
            if($additional_success_message) {
                $output .= $additional_success_message;
            }

            return $output;
        }
    }
}

new Form_Shortcode();