<?php

/*******************************************************************************
 style and js
********************************************************************************/

$test_mode = false;

function b2wall_css()
{
    $version = "2.7";
    // wp_enqueue_style("b2wall_css_nirweb_b2wall_font", URL . "assets/font/webfonts/fontiran.css", array(), $version, false);
}

function b2wall_js()
{
    $version = "2.7";

    // wp_enqueue_script('jquery');
    // wp_enqueue_script("b2wall_js_3point_title_product", URL . "assets/js/3point_title_product.js", array(), $version, true);
    // wp_localize_script('b2wall_js_main', 'nirweb', array(
    //     'ajax_url' => admin_url('admin-ajax.php'),
    //     'user_logged' => 0,
    // ));
}

add_action('wp_enqueue_scripts', 'b2wall_css');
add_action('wp_enqueue_scripts', 'b2wall_js');

function load_admin_scripts()
{
    $version_admin = "2.7";
}

add_action('admin_enqueue_scripts', 'load_admin_scripts');











