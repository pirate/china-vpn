<?php
/**
 * Image Attachment Template
 *
 * @file           image.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/image.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

        <div id="post-images" class="row span8">        
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
          
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header><h1><?php the_title(); ?></h1></header>
                <p><?php _e('&larr; Return to', 'pilotfish'); ?> <a href="<?php echo get_permalink($post->post_parent); ?>" rel="gallery"><?php echo get_the_title($post->post_parent); ?></a></p>
                                
                <div class="attachment-entry">
                    <a href="<?php echo wp_get_attachment_url($post->ID); ?>"><div class="stack"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></div></a>
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?>
                    <?php the_content(__('Continue Reading &rarr;', 'pilotfish')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'pilotfish'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->

               <div class="pager">
	               <div class="previous stack"><?php previous_image_link( 'thumbnail' ); ?></div>
			<div class="next stack"><?php next_image_link( 'thumbnail' ); ?></div>
		       </div><!-- end of .navigation -->
                        
                <footer class="post-data">
		        <div class="post-meta">
		        <?php pilotfish_entry_meta(); ?>
					    <?php if ( comments_open() ) : ?>
		                <span class="comments-link">
		                <span class="mdash">&mdash;</span>
		            <?php comments_popup_link(__('No Response &darr;', 'pilotfish'), __('One Response &darr;', 'pilotfish'), __('% Responses &darr;', 'pilotfish')); ?>
		                </span>
		            <?php endif; ?> 
		        </div><!-- end of .post-meta -->  
			<?php the_tags(__('TAGS:', 'pilotfish') . ' ', ', ', ' | '); ?>
			<?php printf(__('FILED UNDER: %s', 'pilotfish'), get_the_category_list(', ')); ?> | 
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'pilotfish'), the_title_attribute('echo=0')); ?>"><?php _e('Permalink', 'pilotfish'); ?></a>
                </footer><!-- end of .post-data -->             
            </article><!-- end of #post-<?php the_ID(); ?> -->
            
			<?php comments_template( '', true ); ?>
            
        <?php endwhile; ?>  
<?php endif; ?>  
      
        </div><!-- end of #post-images -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
