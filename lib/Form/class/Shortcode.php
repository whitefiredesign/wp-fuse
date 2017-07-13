<?php
namespace Fuse;

class Form_Shortcode extends Form_Db {

    public function __construct() {
        add_shortcode( 'fuse-form', array($this, 'shortcode'));
    }

    public function shortcode($atts) {
        $atts = shortcode_atts(array(
            'name'              => false,
            'success-msg'       => __('Your submission was successful'),
            'error-msg'         => __('Your submission was unsuccessful'),
            'validate_classes'  => array(),
            
            // JS Code
            'on-success'        => false
        ), $atts, 'fuse-form');

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

        $fm     = new Form($atts['name'], $form->fields, false);

        // If validate classes set
        if($validate_classes) {
            $fm->validate_classes = $validate_classes;
        }

        if(!$fm->form()->submit_success) {
            return $fm->form()->html;
        } else {
            
            if(!$atts['on-success']) {
                return \Fuse\wrap_notice('success bg-success', $atts['success-msg']);
            } else {
                
                // Run any script if specified
                return '<script type="text/javascript">'.html_entity_decode($atts['on-success']).'</script>';
            }
        }
    }
}

new Form_Shortcode();