<?php
/**
 * Add Stylesheets and javascript files safely using wp_enqueue_style()
 */
if (!function_exists('pilotfish_script')):
function pilotfish_scripts() {
  	wp_enqueue_style('pilotfish_main_style', get_template_directory_uri() . '/style.css', true, null);

  	if (!is_admin()) {
    		wp_deregister_script('jquery');
    		wp_register_script('jquery', '', '', '', false);
  	}

  	if (is_single() && comments_open() && get_option('thread_comments')) {
    		wp_enqueue_script('comment-reply');
  	}

  	wp_register_script('pilotfish_modernizr', get_template_directory_uri() . '/js/modernizr.js', array('jquery'), null, false);
	wp_register_script('pilotfish_mediaqueries', get_template_directory_uri() . '/js/css3-mediaqueries.js', array('jquery'), null, false);
	wp_register_script('pilotfish_main', get_template_directory_uri() . '/js/main.js', array('jquery'), null, true);
  	wp_enqueue_script('pilotfish_modernizr');
	wp_enqueue_script('pilotfish_mediaqueries');
  	wp_enqueue_script('pilotfish_main');
}
endif;
add_action('wp_enqueue_scripts', 'pilotfish_scripts');


/**
 * Show post thumbnail
 */
if (!function_exists('pilotfish_the_thumbnail')): 
function pilotfish_the_thumbnail() {
	global $post;

	$id = (int) $post->ID;
	if ( $id == 0 ) {
		return false;
	}

	$html = get_the_post_thumbnail($id, array(300,175));
	if(!empty($html)){
		echo $html;
	}
}
endif;


/**
 * Register a Custom Post Type 
 * 
 * Label: Project (for portfolio items)
 */
	 
add_action( 'init', 'create_post_type' );
	
if (!function_exists('create_post_type')):
	
	function create_post_type() {
			register_post_type( 'project',
					array(
							'labels' => array(
									'name' => __( 'Projects','pilotfish' ),
									'singular_name' => __( 'Project','pilotfish' )
							),
					'public' => true,
					'has_archive' => true,
					'show_in_menu' => true, 
					'query_var' => true,
					'rewrite' => true,
					'capability_type' => 'post',
					'has_archive' => true, 
					'hierarchical' => false,
					'menu_position' => '5',
					'supports' => array('title',
					    'editor',
					    'author',
					    'thumbnail',
					    'excerpt',
					    'comments'
						)
					)
			);
	}
	
endif;

// Custom Categories for Projects
register_taxonomy( 'project_type', 
    	array('project'),
    	array('hierarchical' => true,     /* if this is true, it acts like categories */             
    		'labels' => array(
    			'name' => __( 'Project Type', 'pilotfish' ), /* name of the custom taxonomy */
    			'singular_name' => __( 'Project Type', 'pilotfish' ), /* single taxonomy name */
    			'search_items' =>  __( 'Search Project Types', 'pilotfish' ), /* search title for taxomony */
    			'all_items' => __( 'All Project Types', 'pilotfish' ), /* all title for taxonomies */
    			'parent_item' => __( 'Parent Project Type', 'pilotfish' ), /* parent title for taxonomy */
    			'parent_item_colon' => __( 'Parent Project Type:', 'pilotfish' ), /* parent taxonomy title */
    			'edit_item' => __( 'Edit Project Type', 'pilotfish' ), /* edit custom taxonomy title */
    			'update_item' => __( 'Update Project Type', 'pilotfish' ), /* update title for taxonomy */
    			'add_new_item' => __( 'Add New Project Type', 'pilotfish' ), /* add new title for taxonomy */
    			'new_item_name' => __( 'New Project Type', 'pilotfish' ) /* name title for taxonomy */
    		),
    		'show_admin_column' => true, 
    		'show_ui' => true,
    		'query_var' => true,
    		'rewrite' => array( 'slug' => 'project_type' ),
    	)
    ); 

// Custom Tags for Projects
register_taxonomy( 'skills', 
    	array('project'), 
    	array('hierarchical' => false,    /* if this is false, it acts like tags */                
    		'labels' => array(
    			'name' => __( 'Skills', 'pilotfish' ), /* name of the custom taxonomy */
    			'singular_name' => __( 'Skill', 'pilotfish' ), /* single taxonomy name */
    			'search_items' =>  __( 'Search Skills', 'pilotfish' ), /* search title for taxomony */
    			'all_items' => __( 'All Skills', 'pilotfish' ), /* all title for taxonomies */
    			'parent_item' => __( 'Parent Skill', 'pilotfish' ), /* parent title for taxonomy */
    			'parent_item_colon' => __( 'Parent Skill:', 'pilotfish' ), /* parent taxonomy title */
    			'edit_item' => __( 'Edit Skill', 'pilotfish' ), /* edit custom taxonomy title */
    			'update_item' => __( 'Update Skill', 'pilotfish' ), /* update title for taxonomy */
    			'add_new_item' => __( 'Add New Skill', 'pilotfish' ), /* add new title for taxonomy */
    			'new_item_name' => __( 'New Skill Name', 'pilotfish' ) /* name title for taxonomy */
    		),
    		'show_admin_column' => true,
    		'show_ui' => true,
    		'query_var' => true,
    		'rewrite' => array( 'slug' => 'skills' ),
    	)
    );   


/**
 * Show Home Link in the Primary Navigation 
 */
if (!function_exists('pilotfish_home_menu_args')):  
function pilotfish_home_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
endif;
add_filter( 'wp_page_menu_args', 'pilotfish_home_menu_args' );	


/**
 * Where the post has no post title, but must still display a link to the single-page post view.
 */
if (!function_exists('pilotfish_title')): 
function pilotfish_title($title) {
        if ($title == '') {
            return __('Untitled','pilotfish');
        } else {
            return $title;
        }
}
endif;
add_filter('the_title', 'pilotfish_title');


/**
 * Sets the post excerpt length to 60 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
if (!function_exists('pilotfish_excerpt_length')):
function pilotfish_excerpt_length( $length ) {
	return 60;
}
endif;
add_filter( 'excerpt_length', 'pilotfish_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
if (!function_exists('pilotfish_continue_reading_link')):
function pilotfish_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue Reading <span class="meta-nav">&rarr;</span>', 'pilotfish' ) . '</a>';
}
endif;

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and pilotfish_continue_reading_link().
 */
if (!function_exists('pilotfish_auto_excerpt_more'))://
function pilotfish_auto_excerpt_more( $more ) {
	return ' &hellip;' . pilotfish_continue_reading_link();
}
endif;
add_filter( 'excerpt_more', 'pilotfish_auto_excerpt_more' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
if (!function_exists('pilotfish_custom_excerpt_more')):
function pilotfish_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= pilotfish_continue_reading_link();
	}
	return $output;
}
endif;
add_filter( 'get_the_excerpt', 'pilotfish_custom_excerpt_more' );


/*
 * filter function for wp_title
 */
if (!function_exists('pilotfish_filter_wp_title')):
function pilotfish_filter_wp_title( $old_title, $sep, $sep_location ){
 
	// add padding to the sep
	$ssep = ' ' . $sep . ' ';
	 
	// find the type of index page this is
	if( is_category() ) $insert = $ssep . 'Category';
	elseif( is_tag() ) $insert = $ssep . 'Tag';
	elseif( is_author() ) $insert = $ssep . 'Author';
	elseif( is_year() || is_month() || is_day() ) $insert = $ssep . 'Archives';
	else $insert = NULL;
	 
	// get the page number we're on (index)
	if( get_query_var( 'paged' ) )
	$num = $ssep . 'page ' . get_query_var( 'paged' );
	 
	// get the page number we're on (multipage post)
	elseif( get_query_var( 'page' ) )
	$num = $ssep . 'page ' . get_query_var( 'page' );
	 
	// else
	else $num = NULL;
	 
	// concoct and return new title
	return get_bloginfo( 'name' ) . $insert . $old_title . $num;
}
endif;
// call our custom wp_title filter, with normal (10) priority, and 3 args
add_filter( 'wp_title', 'pilotfish_filter_wp_title', 10, 3 );

/*
 * Comment reply script
 */
function pilotfish_enqueue_comment_reply_script() {
	if ( comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'pilotfish_enqueue_comment_reply_script' );

/**
 * Return the URL for the first link found in the post content.
 */
function pilotfish_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

function modify_num_posts_for_projects($query)
{
    if ($query->is_main_query() && $query->is_post_type_archive('project') && !is_admin())
        $query->set('posts_per_page', 12);
}
 
add_action('pre_get_posts', 'modify_num_posts_for_projects');

/** 
 * Include the Google Analytics Tracking Code (ga.js)
 */
// @ http://code.google.com/apis/analytics/docs/tracking/asyncUsageGuide.html
function google_analytics_tracking_code(){

	$options = get_option('pilotfish_theme_options');
	$propertyID = $options['ga_tracking_code'];

	if ($options['add_ga'] == 1) { ?>

		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo $propertyID; ?>']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>

<?php }
}

// include GA tracking code before the closing head tag
// add_action('wp_head', 'google_analytics_tracking_code');

// include GA tracking code before the closing body tag
add_action('wp_footer', 'google_analytics_tracking_code');


/**
 * Change the Homepage Featured Image
 */
if ( ! function_exists( 'pilotfish_featured_image_override' ) ) :
function pilotfish_featured_image_override() {

	$options = get_option('pilotfish_theme_options');
	$featuredURL = $options['featured_image_url'];
	if ( $featuredURL != '' ):
?>
	<style type="text/css">
	#featured {
		background-image: url(<?php echo $featuredURL; ?>);
	}
	<?php endif; ?>
	</style>
<?php
}
endif; // pilotfish_featured_image_override
add_action('wp_footer', 'pilotfish_featured_image_override');