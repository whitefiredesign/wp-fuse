<?php
namespace Fuse\Util;

/**
 * Class Less
 *
 * The Less class compiler to use in project
 * Less::compile();
 *
 */
class Less {
    
    static function compile($file = false, $suffix='compiled', $force = false) {

        if(!$file) {
            return false;
        }

        $less = new \lessc;

        $less->checkedCompile($file, dirname($file) . '/' . basename($file, '.less') . '.'.$suffix.'.css');

        return false;
        
    }
}

