<?php
/**
 * Search Template
 *
 *
 * @file           search.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/search.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

        <div id="content" class="row span8" role="main">
            <h6><?php _e('We found','pilotfish'); ?> 
		<?php
                $allsearch = &new WP_Query("s=$s&showposts=-1");
                $key = esc_html($s, 1);
                $count = $allsearch->post_count;
                _e(' &#8211; ', 'pilotfish');
                echo $count . ' ';
                _e('articles for ', 'pilotfish');
                _e('<span class="post-search-terms">', 'pilotfish');
                echo $key;
                _e('</span><!-- end of .post-search-terms -->', 'pilotfish');
                wp_reset_query();
            ?>
            </h6>
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
   
        </div><!-- end of #search-results -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
