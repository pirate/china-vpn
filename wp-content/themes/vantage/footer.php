			<?php do_action( 'vantage_main_bottom' ); ?>
		</div><!-- .full-container -->
	</div><!-- #main .site-main -->

	<?php do_action( 'vantage_after_main_container' ); ?>

	<?php do_action( 'vantage_before_footer' ); ?>

	<?php get_template_part( 'parts/footer', apply_filters( 'vantage_footer_type', '' ) ); ?>
	
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://nicksweeting.com/piwik/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "4"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->	
<!-- Start Open Web Analytics Tracker -->
<script type="text/javascript">
//<![CDATA[
var owa_baseUrl = 'http://nicksweeting.com/analytics/';
var owa_cmds = owa_cmds || [];
owa_cmds.push(['setSiteId', '154a14a44d60d1851d65c63b803d5172']);
owa_cmds.push(['trackPageView']);
owa_cmds.push(['trackClicks']);
owa_cmds.push(['trackDomStream']);

(function() {
	var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
	owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
	_owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
	var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
}());
//]]>
</script>
<!-- End Open Web Analytics Code -->
				
		

	<?php do_action( 'vantage_after_footer' ); ?>

</div><!-- #page-wrapper -->

<?php do_action('vantage_after_page_wrapper') ?>

<?php wp_footer(); ?>

</body>
</html>