<?php
/**
 * Part Name: Default Footer
 */
?>
<footer id="colophon" class="site-footer" role="contentinfo">

	<div id="footer-widgets" class="full-container">
		<?php dynamic_sidebar( 'sidebar-footer' ) ?>
	</div><!-- #footer-widgets -->

	<?php $site_info_text = apply_filters('vantage_site_info', siteorigin_setting('general_site_info_text') ); if( !empty($site_info_text) ) : ?>
		<div id="site-info">
			<?php echo wp_kses_post($site_info_text) ?><br>
			<img src='http://www.hit-counts.com/counter.php?t=MTI5MzE4Ng==' border='0'>
		</div><!-- .site-info -->
	<?php endif; ?>

</footer><!-- #colophon .site-footer -->