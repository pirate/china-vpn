<?php
/**
 * This template displays full width pages.
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 * 
 * Template Name: Full Width Page with status
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			
			<base href="http://vpn.nicksweeting.com/"/>
			
Server status: 
<?php 
include_once('dom.php');
$html = file_get_html('https://nicksweeting.com:10000/status/');
$conn = file_get_html('https://nicksweeting.com:10000/pptp-server/list_conns.cgi');

$conn = $conn->find('table[0]', 1);
$pattern = '/(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b)/';
$replacement = '<a href="http://geoiptool.org/?q=$1">$1</a>';
$conn= preg_replace($pattern, $replacement, $conn);

$elem = $html->find('tr[id=row_d_1386916015]', 0);
echo $elem->find('img', 0);
echo "<br>"; echo $conn;
?>
<?php get_template_part( 'content', 'page' ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_footer(); ?>