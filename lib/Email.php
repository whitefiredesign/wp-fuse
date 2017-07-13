<?php
namespace Fuse;

/**
 * Class Email
 * Responsible for sending emails
 *
 * @package Fuse
 */
class Email {

    public $to;
    public $from;
    public $subject;
    public $cc  = array();
    public $bcc = array();
    public $attachments = array();

    public $type    = 'text/plain';

    public $body    = 'No content';

    public $done    = false;

    public function __construct() {

    }

    public function send() {

        if($this->done) {
            return false;
        }

        $headers = array();
        if($this->type) {
            $headers[]  = 'Content-Type: '.$this->type.'; charset=UTF-8';
            $headers[]  = 'From: ' .$this->from;
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

        if($this->type=='text/html') {
            add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
        }
        $email = wp_mail( $this->to, $this->subject, $this->body, $headers, $this->attachments);
        if($this->type=='text/html') {
            remove_filter('wp_mail_content_type', 'set_html_content_type');
        }

        $this->done = true;

        return $email;

    }
}