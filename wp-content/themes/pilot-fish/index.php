<?php
/**
 * Index Template
 *
 * @file           index.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/index.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

        <div id="content" class="row span8" role="main">       
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post();
				if( !get_post_format() ) {
					get_template_part( 'content', 'standard' );
				} else {
					get_template_part( 'content', get_post_format() );
				}
				comments_template( '', true ); ?>
        <?php endwhile; ?> 
        
        <?php if ($wp_query->max_num_pages > 1) { ?>
		  <nav id="post-nav" class="pager">
		    <div class="previous"><?php next_posts_link(__('&larr; previous', 'pilotfish')); ?></div>
		    <div class="next"><?php previous_posts_link(__('next &rarr;', 'pilotfish')); ?></div>
		  </nav>
		<?php } ?>
<?php endif; ?>  
      
        </div><!-- end of #content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
