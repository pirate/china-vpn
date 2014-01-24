<?php
/**
 * Front Page with Featured Content
 *
 *
 * @file           home.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/home.php
 * @since          Pilot Fish 0.1
 */

get_header(); ?>
    
	<div id="featured" class="hidden-phone">
	<div id="banner-text" class="span12">     
		<h1 class="featured-title"><?php echo __('Hello World!','pilotfish'); ?></h1>
	</div>
		<h2 class="featured-subtitle"><?php echo __('A Minimal, Responsive Portfolio Theme','pilotfish'); ?></h2>
            	<p><?php echo __('You can edit this section from home.php in the Edit Panel. Happy Blogging! ','pilotfish'); ?></p>
        </div><!-- end of #featured -->
             
	<div class="center"><h2><?php _e('Featured Widgets Area','pilotfish')?></h2></div>
	<hr>
	
<?php get_sidebar('home'); ?>
<?php get_footer(); ?>
