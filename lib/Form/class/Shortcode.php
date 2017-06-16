<?php
namespace Fuse;

class Form_Shortcode extends Form_Db {

    public function __construct() {
        add_shortcode( 'fuse-form', array($this, 'shortcode'));
    }

    public function shortcode($atts) {
        $atts = shortcode_atts(array(
            'name'          => false,
            'success-msg'   => __('Your submission was successful'),
            'error-msg'     => __('Your submission was unsuccessful')
        ), $atts, 'fuse-form');
    
        if(!$atts['name']) {
            return \Fuse\wrap_notice('error', __('No form has been specified.'));
        }
        
        $form   = $this->get_form($atts['name']);
        if( !$form || !$form->fields) {
            return \Fuse\wrap_notice('error', __('Form "'.$atts['name'].'" does not appear to exist.'));
        }

        $fm     = new Form($atts['name'], $form->fields, false);

        if(!$fm->form()->submit_success) {
            return $fm->form()->html;
        } else {
            return \Fuse\wrap_notice('success', $atts['success-msg']);
        }
    }
}

new Form_Shortcode();