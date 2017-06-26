<?php
namespace Fuse\Util;

recursive_load_files(__DIR__ . '/Menu');

class Menu {
    
    public function __construct() {
        new MenuSubParent();           
    }
    
}

new Menu();