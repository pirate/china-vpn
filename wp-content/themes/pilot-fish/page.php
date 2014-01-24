<?php
/**
 * Pages Template
 *
 * @file           page.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/page.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>
        <div id="content-full" class="row span12" role="main">       
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header><h1><?php the_title(); ?></h1></header>                
                <div class="post-entry">
                    <?php the_content(__('Continue Reading &rarr;', 'pilotfish')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'pilotfish'), 'after' => '</div>')); ?>
	
	<!-- Display children if page has children -->
		<?php
			$options = get_option('pilotfish_theme_options');
			if ($options['add_ph'] == 1) { ?> 
			<div class="page-hierarchy">             
		        <?php
			//if the post has a parent
			if($post->post_parent){
			  //collect ancestor pages
			  $relations = get_post_ancestors($post->ID);
			  //get child pages
			  $result = $wpdb->get_results( "SELECT ID FROM wp_posts WHERE post_parent = $post->ID AND post_type='page'" );
			  if ($result){
			    foreach($result as $pageID){
			      array_push($relations, $pageID->ID);
			    }
			  }
			  //add current post to pages
			  array_push($relations, $post->ID);
			  //get comma delimited list of children and parents and self
			  $relations_string = implode(",",$relations);
			  //use include to list only the collected pages. 
			  $sidelinks = wp_list_pages("title_li=&echo=0&include=".$relations_string);
			}else{
			  // display only main level and children
			  $sidelinks = wp_list_pages("title_li=&echo=0&depth=1&child_of=".$post->ID);
			}

			if ($sidelinks) { ?>
			  <h5><?php _e('Page Hierarchy:', 'pilotfish'); ?> <?php the_title(); ?></h5>
			  <ul>
			    <?php //links in <li> tags
			    echo $sidelinks; ?>
			  </ul>         
			<?php } ?>
		<?php } ?>
		</div><!-- end of post-hierarchy -->

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
		</footer>
            <div class="post-edit"><?php edit_post_link(__('Edit', 'pilotfish')); ?></div> 
            </article><!-- end of #post-<?php the_ID(); ?> -->
            
			<?php comments_template( '', true ); ?>
           
        <?php endwhile; ?> 
        
        <?php if ($wp_query->max_num_pages > 1) { ?>
		  <nav id="post-nav" class="pager">
		    <div class="previous"><?php next_posts_link(__('&larr; previous', 'pilotfish')); ?></div>
		    <div class="next"><?php previous_posts_link(__('next &rarr;', 'pilotfish')); ?></div>
		  </nav>
		<?php } ?>
<?php endif; ?>  
      
        </div><!-- end of #content-full -->
<?php get_footer(); ?>
