<?php
namespace Fuse;

class Form_Email {

    public $to;
    public $from;
    public $subject;
    public $cc  = array();
    public $bcc = array();
    public $attachments = false;

    public $type = 'text/plain';

    public $template;
    
    public $data;

    public function __construct() {
    
    }
    
    public function send($template = false) {

        if(!$template) {

        }

        $headers = array();
        if($this->type) {
            $headers[] = 'Content-Type: '.$this->type.'; charset=UTF-8';
        }
        if($this->cc) {
            foreach($this->cc as $cc) {
                $headers[] = 'Cc: '.$cc.'';
            }
        }

        if($this->bcc) {
            foreach($this->bcc as $bcc) {
                $headers[] = 'Bcc: '.$bcc.'';
            }
        }

        wp_mail( $this->to, $this->subject, $this->template, $headers, $this->attachments);
    }
}