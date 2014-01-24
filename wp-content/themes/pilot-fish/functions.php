<?php
/**
 * pilot-fish functions
 */
 
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

require ( get_template_directory() . '/includes/theme-options.php' );
require ( get_template_directory() . '/includes/scripts.php' );
require ( get_template_directory() . '/includes/hooks.php' );
require ( get_template_directory() . '/includes/template-tags.php' );
require ( get_template_directory() . '/includes/widgets.php' );

// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = 960; }

if (!function_exists('pilotfish_setup')):
function pilotfish_setup() {

// Make theme available for translation
  	load_theme_textdomain('pilotfish', get_template_directory() . '/languages');

// Register wp_nav_menu() menus
  	register_nav_menu( 'primary-navigation', __( 'Primary Navigation', 'pilotfish' ) );

// Add post thumbnails 
  	add_theme_support('post-thumbnails');
   	set_post_thumbnail_size(300, 175, true);


// Add post formats 
  	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status')); 
   
  	add_editor_style();
  
  	add_theme_support('automatic-feed-links');
  
  	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		// This is dependent on our current color scheme.
		'default-color' => 'ffffff',
	) );
  
// Add support for custom headers.
	$custom_header_support = array(
		// The default header text color.
		'default-text-color' => '377687',
		// The height and width of our custom header.
		'width' => apply_filters( 'pilotfish_header_image_width', 400 ),
		'height' => apply_filters( 'pilotfish_header_image_height', 125 ),
		// Callback for styling the header.
		'wp-head-callback' => 'pilotfish_header_style',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'pilotfish_admin_header_style',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => 'pilotfish_admin_header_image',
	);
	
  	add_theme_support( 'custom-header', $custom_header_support );
}
endif;

add_action('after_setup_theme', 'pilotfish_setup');


/**
 * Styles the header image and text displayed on the blog
 */
if ( ! function_exists( 'pilotfish_header_style' ) ) :
function pilotfish_header_style() {
	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )
		return;
		
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		.site-name,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-name a {
			font-family: 'Fredericka the Great', cursive;
			font-size: 3.5em;
			color: #<?php echo $text_color; ?> !important;
			line-height: 1.1em;
		} 
		.site-name a:hover {
			text-decoration: none;
			color: #<?php echo $text_color; ?> !important;
		}
		#logo .site-description {
			display:block;
			font-size:14px;
			margin:10px 33px 0 0;
			text-align: right;
			color: #<?php echo $text_color; ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // pilotfish_header_style

if ( ! function_exists( 'pilotfish_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in pilotfish_setup().
 */
function pilotfish_admin_header_style() {
?>
	<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Fredericka+the+Great);
	.site-name a {
		font-family: 'Fredericka the Great', cursive;
		font-size: 3.5em;
		color: #377687;
		line-height: 1.1em;
		text-decoration: none;
	} 
	.site-name a:hover {
		text-decoration: none;
		color: #377687;
	}
	#logo .site-description {
		display:block;
		font-size:14px;
		margin:10px 33px 0 0;
		text-align: right;
		color: #8CA1A1;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		.site-name a,
		.site-name a:hover,
		.site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#logo {
		float:left;
		margin-bottom: 20px;
	}
	</style>
<?php
}
endif; // pilotfish_admin_header_style

if ( ! function_exists( 'pilotfish_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in pilotfish_setup().
 *
 */
function pilotfish_admin_header_image() { ?>
	<div id="logo">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>
		<span class="site-name"<?php echo $style; ?>><a onclick="return false;" href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span><br />
            	<span class="site-description"<?php echo $style; ?>><?php bloginfo('description'); ?></span><br />
            	<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // pilotfish_admin_header_image