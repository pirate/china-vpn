<?php
/**
 * Single Projects Template for Portfolio
 *
 * @file           single-project.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/single-project.php
 * @since          Pilot Fish 0.2
 */
get_header(); ?>
        <div id="project" class="row span12" role="main">                
<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?> 
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header><h1><?php the_title(); ?></h1></header> <!-- Post Title -->                               
                <div class="post-entry">
                    <?php the_content(__('Continue Reading &rarr;', 'pilotfish')); ?>
           
                    <?php if ( get_the_author_meta('description') != '' ) : ?>
                    
                    <div id="author-meta">
                    <?php if (function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '80' ); }?>
                        <div class="about-author"><?php _e('About','pilotfish'); ?> <?php the_author_posts_link(); ?></div>
                        <p><?php the_author_meta('description') ?></p>
                    </div><!-- end of #author-meta -->
                    
                    <?php endif; // no description, no author's meta ?>
                    
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'pilotfish'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
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
					<?php echo the_terms( $post->ID, 'project_type', 'Project Type: ', ', ', '' ); ?>
                    <br />
                    <?php echo the_terms( $post->ID, 'skills', '', ', ', '' ); ?> 
                </footer><!-- end of .post-data -->             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'pilotfish')); ?></div>             
            </article><!-- end of #post-<?php the_ID(); ?> -->
		<?php comments_template( '', true ); ?>
        <?php endwhile; ?>
         
        <nav id="post-nav" class="pager">
	<span class="previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> previous project', 'pilotfish' ) ); ?></span>
	<span class="next"><?php next_post_link( '%link', __( 'next project <span class="meta-nav">&rarr;</span>', 'pilotfish' ) ); ?></span>
	</nav>
<?php endif; ?>  
	</div><!-- end of #project -->
<?php get_footer(); ?>
