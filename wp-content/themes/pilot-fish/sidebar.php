<?php
/**
 * Main Widget Template
 *
 * @file           sidebar.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/sidebar.php
 * @since          Pilot Fish 0.1
 */
?>
        <div id="widgets" class="row span4 last">
        <?php pilotfish_widgets(); // above widgets hook ?>
            
            <?php dynamic_sidebar('sidebar-primary'); ?>
            
        <?php pilotfish_widgets_end(); // after widgets hook ?>
        </div><!-- end of #widgets -->
