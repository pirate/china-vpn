<?php
/**
 * Single Posts Template
 *
 * @file           single.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/single.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>
	<div id="post" class="row span8" role="main">
		<?php if (have_posts()) : ?>
			<?php while ( have_posts() ) : the_post(); 
				if( !get_post_format() ) {
					get_template_part( 'content', 'standard' );
				} else {
					get_template_part( 'content', get_post_format() );
				}
				comments_template( '', true ); ?>
			<?php endwhile; ?> 

	<nav id="post-nav" class="pager">
	<span class="previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> previous post', 'pilotfish' ) ); ?></span>
	<span class="next"><?php next_post_link( '%link', __( 'next post <span class="meta-nav">&rarr;</span>', 'pilotfish' ) ); ?></span>
	</nav>
		<?php endif; ?>  
        </div><!-- end of #post -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
