<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.


$prefix = 'seting_website';

CSF::createOptions( $prefix, array(
    'menu_title' => 'تنظیمات قالب',
    'menu_slug'  => 'op_b2wall',
    'theme'  => 'light',
    'framework_title'  => 'تنظیمات قالب',

) );

CSF::createSection( $prefix, array(
    'title'  => ' هدر',
    'fields' => array(

        array(
            'id'    => 'nirweb_phone_number',
            'type'  => 'text',
            'title' => 'شماره تماس در هدر',
        ),

        array(
            'id'    => 'nirweb_text_baner',
            'type'  => 'text',
            'title' => 'بنر در هدر',
        ),

        array(
            'id'    => 'nirweb_logo_header',
            'type'  => 'media',
            'title' => 'انتخاب لوگوی هدر',
        ),

    )
) );



