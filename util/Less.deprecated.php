<?php
namespace Fuse\Util;

/**
 * Class Less
 *
 * The Less class compiler to use in project
 * Less::compile();
 *
 */
class DCLess {
    
    static function compile($file = false, $suffix='compiled', $vars=array(), $destination=false, $compress = false, $force = false) {

        if(!$file) {
            return false;
        }

        $less = new \lessc;

        if($compress) {
            $less->setFormatter("compressed");
        }

        if(!$destination) {
            $destination = dirname($file);
        }

        if(!empty($vars)) {
            $less->setVariables($vars);
        }

        try {
            if($force) {
                $less->compileFile($file, $destination . '/' . basename($file, '.less') . '.' . $suffix . '.css');
            } else {
                $less->checkedCompile($file, $destination . '/' . basename($file, '.less') . '.' . $suffix . '.css');
            }
        } catch (\Exception $e) {
            echo "fatal error: " . $e->getMessage();
        }

        return false;
        
    }
}

