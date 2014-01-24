<?php
/**
 * Header Template
 *
 *
 * @file           header.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/header.php
 * @since          Pilot Fish 0.1
 */
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700|Droid+Serif:400,700|Fredericka+the+Great' rel='stylesheet' type='text/css'>

<title><?php wp_title(); ?></title>
<script>window.jQuery || document.write('<script src="http://code.jquery.com/jquery-1.7.2.min.js"><\/script>')</script>
<?php pilotfish_head(); // head hook ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>                
<?php pilotfish_container(); // before container hook ?>
<div id="container" class="hentry">
    <?php pilotfish_header(); // before header hook ?>
    <div id="header">
    <?php pilotfish_in_header(); // header hook ?>
	<?php if ( get_header_image() != '' ) : ?>
        <div id="logo">
            <span class="site-name"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span>
            <span class="site-description"><?php bloginfo('description'); ?></span>
            <a href="<?php echo home_url( '/' ); ?>"><img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php bloginfo('description'); ?>" /></a>
        </div><!-- end of #logo -->
    <?php endif; ?>
    
    <?php if ( !get_header_image() ) : ?>
        <div id="logo">
            <span class="site-name"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span>
            <span class="site-description"><?php bloginfo('description'); ?></span>
        </div><!-- end of #logo -->  
    <?php endif; // header image was removed ?>

<!-- Primary Navigation Menu -->
    <nav id="access">
	<?php wp_nav_menu( array( 'theme_location' => 'primary-navigation' ) ); ?>
    </nav>
    </div><!-- end of #header -->

    <?php pilotfish_header_end(); // after header hook ?>
	<?php pilotfish_wrapper(); // before wrapper ?>
    <div id="wrapper" class="clearfix">

    <?php pilotfish_in_wrapper(); // wrapper hook ?>
