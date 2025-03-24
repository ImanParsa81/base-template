<?php

//-----------------------------------------------------------------
// post type کشاورز
//-----------------------------------------------------------------

// function register_farmer_post_type() {
//     $labels = array(
//         'name'               => 'کشاورزان',
//         'singular_name'      => 'کشاورز',
//         'menu_name'          => 'کشاورزان',
//         'name_admin_bar'     => 'کشاورز',
//         'add_new'            => 'افزودن کشاورز',
//         'add_new_item'       => 'افزودن کشاورز جدید',
//         'new_item'           => 'کشاورز جدید',
//         'edit_item'          => 'ویرایش کشاورز',
//         'view_item'          => 'مشاهده کشاورز',
//         'all_items'          => 'همه کشاورزان',
//         'search_items'       => 'جستجوی کشاورز',
//         'parent_item_colon'  => 'کشاورز والد:',
//         'not_found'          => 'کشاورزی پیدا نشد.',
//         'not_found_in_trash' => 'در زباله‌دان کشاورزی یافت نشد.'
//     );

//     register_taxonomy(
//         'farmer_category',
//         'farmer',
//         array(
//             'hierarchical'      => true,
//             'label'             => 'دسته بندی',
//             'show_ui'           => true,
//             'show_admin_column' => true,
//             'query_var'         => true,
//             'rewrite'           => array(
//                 'slug'       => 'learn_f',
//                 'with_front' => false
//             )
//         )
//     );

//     $args = array(
//         'labels'             => $labels,
//         'public'             => true,
//         'publicly_queryable' => true,
//         'show_ui'            => true,
//         'show_in_menu'       => true,
//         'query_var'          => true,
//         'rewrite'            => array(
//             'slug'       => 'farmers',
//             'with_front' => false
//         ),
//         'capability_type'    => 'post',
//         'has_archive'        => true,
//         'hierarchical'       => false,
//         'menu_position'      => 2,
//         'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
//         'menu_icon'          => 'dashicons-carrot'
//     );

//     register_post_type('farmer', $args);
// }

// add_action('init', 'register_farmer_post_type');




















