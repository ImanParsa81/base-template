<?php

/*******************************************************************************
 style and js
********************************************************************************/

$test_mode = false;

function b2wall_css()
{
    $version = "1.0";

    wp_enqueue_style("b2wall_css_bootstrap", URL . "assets/css/general/bootstrap-grid.min.css", array(), $version, false);
    wp_enqueue_style("b2wall_css_swiper", URL . "assets/css/general/swiper-bundle.min.css", array(), $version, false);
}

function b2wall_js()
{
    $version = "1.0";

    wp_enqueue_script("b2wall_js_toast", URL . "tools/toast/toast.js", array(), $version, true);
    wp_enqueue_script("b2wall_js_swiper-bundle", URL . "assets/js/swiper-bundle.min.js", array(), $version, false);


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
    $version_admin = "1.0";
}

add_action('admin_enqueue_scripts', 'load_admin_scripts');











