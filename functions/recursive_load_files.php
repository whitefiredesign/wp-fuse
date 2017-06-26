<?php
if(!function_exists('recursive_load_files')) {
    function recursive_load_files($dir, $depth=0) {
        $scan = glob("$dir/*");
        foreach ($scan as $path) {
            if (preg_match('/\.php$/', $path)) {
                require_once $path;
            }
            elseif (is_dir($path)) {
                recursive_load_files($path, $depth+1);
            }
        }
    }
}
