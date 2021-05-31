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
function create_posttype() {
  register_post_type( 'products',
      array(
          'labels' => array(
              'name' => __( 'Products' ),
              'singular_name' => __( 'Product' )
          ),
          'supports' => array(
'title',
'editor',
'excerpt',
'custom-fields',
'thumbnail',
'page-attributes'
),
          'public' => true,
          'has_archive' => true,
          'rewrite' => array('slug' => 'products'),
          'show_in_rest' => true,

      )
  );
}

add_action( 'init', 'create_posttype' );

function global_notice_meta_box() {

  add_meta_box(
      'global-notice',
      __( 'Global Notice', 'sitepoint' ),
      'global_notice_meta_box_callback',
      'products'
  );
}



add_action( 'add_meta_boxes', 'global_notice_meta_box' );

function global_notice_meta_box_callback( $post ) {

  // Add a nonce field so we can check for it later.
  wp_nonce_field( 'global_notice_nonce', 'global_notice_nonce' );

  $value = get_post_meta( $post->ID, '_global_notice', true );

  echo '<input type="number" name="thePrice" id="thePrice" value="' . esc_attr( $value ) . '">' ;
}

add_action( 'save_post', 'saving_the_metabox_data', 10, 3  );

function saving_the_metabox_data ( $post_id, $post, $update ) {
update_post_meta( $post_id, 'price', $_REQUEST['thePrice'] );
}
?>