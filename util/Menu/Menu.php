<?php
namespace Fuse\Util;

recursive_load_files(__DIR__ . '/lib');

class Menu {

    public function __construct() {
        new MenuSubParent();
    }

}

new Menu();