<?php
/**
 * Archive Template
 *
 * @file           archive.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/archive.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

<div id="content" class="row span8" role="main">
<?php if (have_posts()) : ?>
	<h6>
	<?php
              $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
              if ($term) {
                echo $term->name;
              } elseif (is_post_type_archive()) {
                echo get_queried_object()->labels->name;
              } elseif (is_day()) {
                printf(__('Daily Archives: %s', 'pilotfish'), get_the_date());
              } elseif (is_month()) {
                printf(__('Monthly Archives: %s', 'pilotfish'), get_the_date('F Y'));
              } elseif (is_year()) {
                printf(__('Yearly Archives: %s', 'pilotfish'), get_the_date('Y'));
              } elseif (is_author()) {
                global $post;
                $author_id = $post->post_author;
                printf(__('Author Archives: %s', 'pilotfish'), get_the_author_meta('user_nicename', $author_id));
              } else {
                single_cat_title();
              }
        ?>
	</h6>                  
        <?php while (have_posts()) : the_post();       
        	if( !get_post_format() ) {
					get_template_part( 'content', 'standard' );
				} else {
					get_template_part( 'content', get_post_format() );
				}
				comments_template( '', true ); ?>
	<?php endwhile; /* End loop */ ?>
<?php endif; ?>

		<?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if ($wp_query->max_num_pages > 1) { ?>
		  <nav id="post-nav" class="pager">
		    <div class="previous"><?php next_posts_link(__('&larr; previous', 'pilotfish')); ?></div>
		    <div class="next"><?php previous_posts_link(__('next &rarr;', 'pilotfish')); ?></div>
		  </nav>
		<?php } ?>
</div><!-- end of #content -->
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
