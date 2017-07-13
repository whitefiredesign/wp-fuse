<?php namespace Fuse;

function wrap_notice($classes = false, $text, $wrap = false) {
    if(!$text) {
        return false;
    }        

    $string = '<span class="'.$classes.'">'.__($text).'</span>';
    if($wrap) {
        $string = sprintf($wrap, $string);        
    }

    return $string;
}