<?php namespace Fuse;

function wrap_notice($type = false, $text) {
    if(!$text) {
        return false;
    }        
    
    return '<span class="'.$type.'">'.__($text).'</span>';
}