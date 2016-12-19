<?php
namespace Fuse\Util;

/**
 * Class Uglify
 * 
 * The Uglify compiler to use in project
 * Uglify::compile_bundle()
 * Uglify::compile_single()
 *
 */

class Uglify {

    private function pack($script) {
        $pack   = new \GK\JavascriptPacker($script, 'Normal', true, false);
        
        return $pack;
    }

    private function out($file, $contents) {
        file_put_contents($file, $contents);
        
        return false;
    }

    public static function compile_bundle($files = array(), $fileout='compiled', $force = false) {

        if(empty($files)) {
            return false;
        }

        $uglify = new Uglify();

        $packed = '';
        $path   = dirname($files[0]);
        if(!empty($files)) {
            foreach($files as $file) {
                $pack       = $uglify->pack(file_get_contents($file));
                $packed     .= $pack->pack();

            }
        }

        $uglify->out($path.'/'.$fileout.'.js', $packed);
        
        return false;
    }

    public static function compile_single($file = false, $suffix = 'compiled', $force = false)  {

        if(!$file) {
            return false;
        }

        $uglify = new Uglify();

        $path       = dirname($file);
        $name       = basename($file, '.php');
        $pack       = $uglify->pack(file_get_contents($file));
        $packed     = $pack->pack();

        $uglify->out($path.'/'.basename($name, '.js').'.'.$suffix.'.js', $packed);
        
        return false;

    }
}