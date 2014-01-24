<?php
/**
 * 404 Not Found Template
 *
 * @file           404.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/404.php
 * @since          Pilot Fish 0.1
 */
get_header(); ?>

        <div id="post" class="row span8" role="main">
            <article id="post-0" class="post error404 not-found">
                <header></header>
                <div class="post-entry">
                    <h1 class="title-404"><?php _e('404 &#8212; Oops! Deadend Here...', 'pilotfish'); ?></h1>
                    <h6><?php _e( 'You can return', 'pilotfish' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'pilotfish' ); ?>"><?php _e( '&larr; Home', 'pilotfish' ); ?></a> <?php _e( 'or explore other pages and posts.', 'pilotfish' ); ?></h6>
                </div><!-- end of .post-entry -->
            </article><!-- end of #post-0 -->
        </div><!-- end of #content-full-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
