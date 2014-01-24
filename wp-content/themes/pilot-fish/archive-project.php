<?php
/**
 * Archive Projects Template
 *
 * Template Name: Projects Archive
 *
 * @file           archive-project.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/archive-project.php
 * @since          Pilot Fish 0.2
 */
get_header(); ?>

<div id="project-archive" class="row span12" role="main"> 
<?php if (have_posts()) : ?>
        <div id="thumbnail">
			<?php $var = 1; ?>
			<?php while (have_posts()) : the_post(); ?>
				<?php if ($var%3 == 0): ?>
					<div class="row span4-fixed last"><div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
				<?php else : ?>
					<div class="row span4-fixed"><div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
				<?php endif; ?>
				<a href="<?php the_permalink() ?>" rel="bookmark"><span class="title-overlay" style="display: inline;"><span><?php the_title(); ?></span></span><?php pilotfish_the_thumbnail(); ?></a>
				</div>
				</div>
				<?php $var++; ?>
			<?php endwhile; // no CR conform CSS ?> 
 	</div><!-- end of #thumbnail -->
<?php endif; ?>
        <?php /* Display navigation to next/previous pages when applicable */ ?>
		<?php if ($wp_query->max_num_pages > 1) { ?>
		  <nav id="project-nav" class="pager">
		    <div class="previous"><?php next_posts_link(__('&larr; previous', 'pilotfish')); ?></div>
		    <div class="next"><?php previous_posts_link(__('next &rarr;', 'pilotfish')); ?></div>
		  </nav>
	<?php } ?>	    
</div><!-- end of #project-archive -->
<?php get_footer(); ?>
