<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

  <head>
    <meta charset="utf-8">

    <!-- Google Chrome Frame for IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php wp_title(''); ?></title>

    <meta name="viewport" content="width=device-width"/>

    <?php
      ChalkPress::touch_icon_tags( 
        array( 
          '57x57'   => 'touch-icon.png',
          '72x72'   => 'touch-icon-72.png',
          '114x114' => 'touch-icon-114.png',
          '144x144' => 'touch-icon-144.png'
        ) 
      );
    ?>

    <?php ChalkPress::link_tag('icon', array('images', 'favicon.png') ); ?>

    <!--[if IE]>
      <?php ChalkPress::link_tag( 'shortcut icon', array('images', 'favicon.png') ); ?>
    <![endif]-->

    <meta name="msapplication-TileColor" content="#f01d4f">
    <meta name="msapplication-TileImage" content="<?php echo ChalkPress::image_url('win-tile-icon.png'); ?>">

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

    <!-- build:remove:dist -->
      <?php ChalkPress::vendor_javascript_tag("modernizr/modernizr.js"); ?>
    <!-- /build -->

    <!-- build:template:dist
      <?php ChalkPress::javascript_tag("modernizr.min.js"); ?>
    /build -->


  </head>

  <body <?php body_class(); ?>>
    <div class="outer-wrap">
      <div class="inner-wrap">

        <header class="contrast" role="banner">
          <div class="row">

            <div class="small-8 large-3 columns">
              <h1>
                <a href="<?php echo home_url(); ?>" rel="nofollow">
                  <?php bloginfo('name'); ?>
                </a>
              </h1>
            </div>
          
            <nav role="navigation" class="small-4 large-9 columns">
              <ul class="large-block-grid-5">
                <li><a href="#">Archive</a></li>
                <li><a href="<?php echo get_post_type_archive_link( 'post-type-1' ); ?>">Post Type 1</a></li>
                <li><a href="<?php echo chlk_get_page_permalink('my-page'); ?>">My Page</a></li>
                <li>
                  <a href="" data-icon="facebook" class="standalone inversed"></a>
                  <a href="" data-icon="twitter" class="standalone inversed mlm"></a>
                </li>
              </ul>
            </nav>

          </div>
        </header>
