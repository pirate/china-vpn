<?php
/**
 * Blog Template
 *
 * Template Name: Blog Posts
 *
 * @file           blog.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/blog.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

<?php global $more; $more = 0; ?>
        <div id="content" class="row span8" role="main"> 
<?php
    $limit = get_option('posts_per_page');
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts('showposts=' . $limit . '&paged=' . $paged);
    $wp_query->is_archive = true; $wp_query->is_home = false;
    ?>    
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
        </div><!-- end of #content-blog -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
