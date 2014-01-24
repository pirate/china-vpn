<?php
/**
 * Footer Widget Template
 *
 * @file           sidebar-footer.php
 * @package        Pilot Fish 
 * @filesource     wp-content/themes/pilot-fish/sidebar-footer.php
 * @since          Pilot Fish 0.3.2
 */
?>
<div id="widgets-footer">   
	    <div class="row span4">
	    <?php pilotfish_widgets(); // before widgets hook ?> 
	    	<?php dynamic_sidebar('sidebar-footer-1'); ?>
	    <?php pilotfish_widgets_end(); // after widgets hook ?>
	    </div>
	    
	    <div class="row span4">
	    <?php pilotfish_widgets(); // before widgets hook ?> 
	    	<?php dynamic_sidebar('sidebar-footer-2'); ?>
	    <?php pilotfish_widgets_end(); // after widgets hook ?>
	    </div>
	    
	    <div class="row span4 last">
	    <?php pilotfish_widgets(); // before widgets hook ?> 
	    	<?php dynamic_sidebar('sidebar-footer-3'); ?>
	    <?php pilotfish_widgets_end(); // after widgets hook ?>
	    </div>
</div> <!-- end of #widgets-footer -->