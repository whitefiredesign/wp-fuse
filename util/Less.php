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

    static function compile($file = false, $suffix='compiled', $vars=array(), $destination=false, $compress = false, $force = false) {

        if(!$file) {
            return false;
        }

        $cache_dir  = get_template_directory() . '/tmp/less/';

        $options    = array(
            'cache_dir'=> $cache_dir,
            'compress' => $compress,
            'cache_method' => 'serialize'
        );


        if($force) {

            // If force compile is passed
            try {
                $parser = new \Less_Parser;
                $parser->parseFile($file);

                if(!empty($vars)) {
                    $parser->modifyVars($vars);
                }

                $css    = $parser->getCss();
                file_put_contents($destination . '/' . basename($file, '.less') . '.' . $suffix . '.css', $css);
            } catch(\Exception $e) {
                print_r($e->getMessage());
            }

        } else {

            // Get styles from cache
            try {
                $files = array($file => get_stylesheet_directory_uri());
                $css_file_name = \Less_Cache::Get($files, $options, $vars);
                copy($options['cache_dir'] . $css_file_name, $destination . '/' . basename($file, '.less') . '.' . $suffix . '.css');

            } catch (\Exception $e) {
                print_r($e->getMessage());
            }
        }


        return false;

    }
}