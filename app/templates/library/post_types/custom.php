<?php

return array(
  'description'   => 'Test Post Type',
  'public'        => true,
  'menu_position' => 9,
  'supports'      => array( 'title' ),
  'has_archive'   => true,
  'labels'        => array(
    'name'               => _x( 'Custom Items', 'post type general name' ),
    'singular_name'      => _x( 'Custom Item', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'custom' ),
    'add_new_item'       => __( 'Add New Custom Item' ),
    'edit_item'          => __( 'Edit Custom Item' ),
    'new_item'           => __( 'New Custom Item' ),
    'all_items'          => __( 'All Custom Items' ),
    'view_item'          => __( 'View Custom Items' ),
    'search_items'       => __( 'Search Custom Items' ),
    'not_found'          => __( 'No Custom Items Found' ),
    'not_found_in_trash' => __( 'No Custom Items found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => 'Custom'
  )
);
