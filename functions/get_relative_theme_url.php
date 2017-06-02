<?php
function get_relative_theme_uri() {
    return str_replace( home_url(), "", get_stylesheet_directory_uri() );
}