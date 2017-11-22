<?php namespace Fuse;

function wrap_notice($classes = false, $text, $wrap = false) {
    if(!$text) {
        return false;
    }        

    $string = '<div class="'.$classes.'">'.__($text).'</div>';
    if($wrap) {
        $string = sprintf($wrap, $string);        
    }

    return $string;
}