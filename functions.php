<?php

define('PATH', get_template_directory() . '/');
define('URL', get_template_directory_uri() . '/');
define("op_b2wall", get_option('seting_website'));

/***************************************************************************
 *#----- General -----
 ***************************************************************************/

add_action('after_setup_theme', function () {

    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('widgets');
    add_theme_support('widgets-block-editor');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // ثبت منو
    register_nav_menus(array(
        'menuHeader' => __('menuHeader'),
    ));
});


/*******************************************************************************
 *# require
 ********************************************************************************/

require_once PATH . 'options/codestar-framework.php';
require_once PATH . 'options/inc/admin-options.php';

require_once PATH . 'functions/functions.php';
require_once PATH . 'functions/ajax.php';
require_once PATH . 'functions/create_db.php';
require_once PATH . 'functions/include.php';
require_once PATH . 'functions/widgets.php';
require_once PATH . 'functions/menus.php';
require_once PATH . 'functions/login_func.php';
require_once PATH . 'functions/postTypes.php';
require_once PATH . 'functions/Settings.php';
require_once PATH . 'functions/date.php';

require_once PATH . "pages/basic_template.php";


//---------------------------------------------------------------------------------------------------------------------------
//search
//---------------------------------------------------------------------------------------------------------------------------