<?php

require_once dirname(__FILE__) . '/library/chalkpress/chalk_press.php';

add_action('after_setup_theme','chlk_init', 16);
function chlk_init()
{
  ChalkPress::initialize('chlk_theme_setup'); 
}


function chlk_theme_setup()
{
  add_theme_support('post-thumbnails');

  set_post_thumbnail_size(125, 125, true);
  add_image_size( 'featured', 940, 9999 );
  add_image_size( 'all', 460, 9999 );
  add_image_size( 'half', 300, 9999 );
  add_image_size( 'quarter', 140, 9999 );

  add_action('init', 'chlk_head_cleanup');
  add_action('init', 'chlk_include_metabox_fields');
  add_action('wp_head', 'chlk_remove_recent_comments_style', 1);
  add_action('wp_enqueue_scripts', 'chlk_scripts_and_styles', 999);
  add_action('admin_menu', 'chlk_dashboard_cleanup');
  add_action('admin_menu', 'chlk_admin_menu');
  add_action('pre_get_posts', 'chlk_event_query');
  add_action('pre_get_posts', 'chlk_add_editor_styles');

  add_filter('the_generator', 'chlk_rss_version');
  add_filter('wp_head', 'chlk_remove_wp_widget_recent_comments_style', 1 );
  add_filter('gallery_style', 'chlk_gallery_style');
  add_filter('the_content', 'chlk_filter_ptags_on_images');
  add_filter('tiny_mce_before_init', 'chlk_mce_styles' );  
  add_filter('mce_buttons_2', 'chlk_mce_buttons');
  add_filter('intermediate_image_sizes_advanced', 'chlk_remove_stock_image_sizes' );
  add_filter('image_size_names_choose', 'chlk_custom_sizes' );
  add_filter('cmb_meta_boxes', 'chlk_metaboxes');
  add_filter('excerpt_more', 'chlk_excerpt_more');
  add_filter('body_class','chlk_class_names');

  remove_filter('the_excerpt', 'wpautop');
}

function chlk_include_metabox_fields()
{
  if ( ! class_exists( 'cmb_Meta_Box' ) ) {
    require_once ChalkPress::join_paths(get_stylesheet_directory(), 'library', 'metabox', 'init.php');
  }
}

function chlk_metaboxes( $meta_boxes )
{
  $prefix = '_chlk_';

  $meta_boxes[] = array(
    'id'         => $prefix . 'beer_intro_meta_box',
    'title'      => 'Beer Description',
    'pages'      => array( 'beers' ),
    'context'    => 'normal',
    'priority'   => 'high',
    'show_names' => true,
    'fields'     => array(
      array(
        'name'    => 'Style',
        'id'      => $prefix . 'beer_style',
        'type'    => 'text_medium'
      ),
      array(
        'name'    => 'Description',
        'id'      => $prefix . 'beer_intro',
        'type'    => 'textarea_small'
      ),
      array(
        'name'    => 'Order',
        'id'      => $prefix . 'beer_order',
        'type'    => 'text_small'
      )
    )
  );


  return $meta_boxes;
}

function chlk_remove_stock_image_sizes( $sizes ) {
  unset( $sizes['thumbnail']);
  unset( $sizes['medium']);
  unset( $sizes['large']);

  return $sizes;
}

function chlk_custom_sizes( $sizes ) {
  return array_merge( $sizes, array(
    'featured' => 'Featured',
    'all' => 'Full Content Width',
    'half' => 'Half Content Width',
    'quarter' => 'Quarter Content Width'
  ));
}

function chlk_admin_menu()
{
  ChalkPress::remove_admin_menu_section('Tools');
}

function chlk_mce_buttons( $buttons )
{
  array_unshift( $buttons, 'styleselect' );
  return $buttons;
}

function chlk_mce_styles( $init_array )
{
  $style_formats = array(  
    array(  
      'title' => 'pull-quote-left',  
      'inline' => 'q',
      'classes' => 'pull-quote pull-quote-left'
    ),
    array(
      'title' => 'pull-quote-right',
      'inline' => 'q',
      'classes' => 'pull-quote pull-quote-right'
    ),
    array(
      'title' => 'subhead',
      'block' => 'h4',
      'classes' => 'subhead headline-divider'
    ),
  );  

  $init_array['style_formats'] = json_encode( $style_formats );  
  
  return $init_array; 
}

function chlk_head_cleanup() 
{
  remove_action( 'wp_head', 'feed_links_extra', 3 );
  remove_action( 'wp_head', 'feed_links', 2 );
  remove_action( 'wp_head', 'rsd_link');
  remove_action( 'wp_head', 'wlwmanifest_link');
  remove_action( 'wp_head', 'index_rel_link' );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  remove_action( 'wp_head', 'wp_generator' );

  add_filter( 'style_loader_src', 'chlk_remove_wp_ver_css_js', 9999 );
  add_filter( 'script_loader_src', 'chlk_remove_wp_ver_css_js', 9999 );

}

function chlk_add_editor_styles()
{
  global $post;

  if ( !is_admin() || is_null($post) ) return;

  $post_type = get_post_type( $post->ID );

  if( in_array($post_type, array('news', 'events')) ) {
    add_editor_style( 'library/css/editor.css' );
  }
}

function chlk_dashboard_cleanup() 
{
  remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
  remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget
  remove_meta_box('welcome_panel', 'dashboard', 'core');         // Plugins Widget
  remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
  remove_meta_box('dashboard_primary', 'dashboard', 'core');         //
  remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //
  remove_meta_box('tagsdiv-hops', 'beers', 'side');
  remove_meta_box('tagsdiv-malts', 'beers', 'side');

  remove_action('welcome_panel', 'wp_welcome_panel');
}

function chlk_rss_version() { return ''; }

function chlk_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

function chlk_remove_wp_widget_recent_comments_style() {
   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
   }
}

// remove injected CSS from recent comments widget
function chlk_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

// remove injected CSS from gallery
function chlk_gallery_style($css) {
  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
}

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function chlk_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}


function chlk_scripts_and_styles() {
  if (!is_admin()) {

    // TODO: Enqueue scripts based on environment flag
    wp_deregister_script('jquery');
    wp_register_style( 'chlk-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
    wp_enqueue_style( 'chlk-stylesheet' );

  }
}


function chlk_event_query( $query ) {
  
  if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'events' ) ) {
    $meta_query = array(
      array(
        'key' => '_chlk_event_date_time',
        'value' => time(),
        'compare' => '>'
      )
    );
    $query->set( 'meta_query', $meta_query );
    $query->set( 'orderby', 'meta_value_num' );
    $query->set( 'meta_key', '_chlk_event_date_time' );
    $query->set( 'order', 'ASC' );
    $query->set( 'posts_per_page', '10' );
  }
 
}

function get_the_slug($echo=false) {
  global $post;

  if(!$echo) {
    return $post->post_name;
  }

  echo $post->post_name;
}

function the_slug() {
  get_the_slug(true);
}
 
function chlk_excerpt_more($more) {
  return '&hellip;';
}

function chlk_class_names($classes) {
  if( !is_admin() && ( is_post_type_archive( 'news' ) || is_singular( 'news' ) ) ) {
    $classes[] = 'news-active';
  } elseif ( !is_admin() && ( is_post_type_archive( 'events' ) || is_singular( 'events') ) ) {
    $classes[] = 'events-active';
  }
  return $classes;
}

function chlk_get_page_id_by_slug($page_slug) {
  $page = get_page_by_path($page_slug);
  if ($page) {
      return $page->ID;
  } else {
      return null;
  }
}

function chlk_get_page_permalink($page_slug) {
  $id = chlk_get_page_id_by_slug($page_slug);

  if( !is_null($id) ) 
    return get_permalink( $id );    
}
