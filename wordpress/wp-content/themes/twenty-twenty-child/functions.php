<?php

add_action( 'wp_enqueue_scripts', 'tt_child_enqueue_parent_styles' );

function tt_child_enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

add_action('after_setup_theme', 'add_tcm_user');

function add_tcm_user() {
    $userdata = array(
        'user_login'  =>  'tcm-editor',
        'user_email'    =>  'harelw@tcmintrade.com',
        'user_pass'   =>  '123456789',
        'role'      => 'editor'
    );

    $user_id = wp_insert_user( $userdata ) ;

    //On success
    if (!is_wp_error( $user_id )) {
       remove_admin_bar();
    }
}
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (current_user_can('editor')) {
        show_admin_bar(false);
    }
}
?>