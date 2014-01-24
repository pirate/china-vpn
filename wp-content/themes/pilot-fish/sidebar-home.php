<?php
/**
 * Home Widgets Template
 *
 * @file           sidebar-home.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/sidebar-home.php
 * @since          Pilot Fish 0.1
 */
?>
    <div id="widgets" class="sidebar-home">
        <div class="row span4">
        <?php pilotfish_widgets(); // before widgets hook ?> 
            
            <?php if (!dynamic_sidebar('sidebar-home-1')) : ?>
                
                <section id="sidebar-home-1" class="widgets-home">
                <div class="widget-inner">
                <img class="aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/bulb.png" alt="">
                <h3 class="center"><?php _e('featured one', 'pilotfish'); ?></h3>
                <div class="textwidget"><?php _e('Replace the text here in the sidebar-home.php, or add widgets to Featured One area.','pilotfish'); ?></div>
                </div>
                </section>
                
	<?php endif; //end of sidebar-home-1 ?>

        <?php pilotfish_widgets_end(); // after widgets hook ?>
        </div><!-- end of .span4 -->

        <div class="row span4">
        <?php pilotfish_widgets(); // before widgets hook ?>
        
	    <?php if (!dynamic_sidebar('sidebar-home-2')) : ?>
                
                <section id="sidebar-home-2" class="widgets-home">
                <div class="widget-inner">
                <img class="aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/wheel.png" alt="">
                <h3 class="center"><?php _e('featured two', 'pilotfish'); ?></h3>
                <div class="textwidget"><?php _e('Replace the text here in the sidebar-home.php, or add widgets to Featured Two area.','pilotfish'); ?></div>
                </div>
                </section>
                            
	<?php endif; //end of sidebar-home-2 ?>
        <?php pilotfish_widgets_end(); // after widgets hook ?>
        </div><!-- end of .span4 -->

        <div class="row span4 last">
        <?php pilotfish_widgets(); // before widgets hook ?>
            <?php if (!dynamic_sidebar('sidebar-home-3')) : ?>
                
                <section id="sidebar-home-3" class="widgets-home">
                <div class="widget-inner">
                <img class="aligncenter" src="<?php echo get_stylesheet_directory_uri(); ?>/images/wrench.png" alt="">
                <h3 class="center"><?php _e('featured three', 'pilotfish'); ?></h3>
                <div class="textwidget"><?php _e('Replace the text here in the sidebar-home.php, or add widgets to Featured Three area.','pilotfish'); ?></div>
                </div>
                </section>
                
	<?php endif; //end of sidebar-home-3 ?>
	
        <?php pilotfish_widgets_end(); // after widgets hook ?>
        </div><!-- end of .span4 last -->     
    </div><!-- end of #widgets -->
