<?php
/**
 * @file           theme-options.php
 * @package        Pilot Fish
 * @filesource     wp-content/themes/pilot-fish/includes/theme-options.php
 * @since          Pilot Fish 0.3.3
 */
add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'pilotfish_options', 'pilotfish_theme_options', 'pilotfish_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'pilotfish' ), __( 'Theme Options', 'pilotfish' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . wp_get_theme() . __( ' Theme Options', 'pilotfish' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'pilotfish' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'pilotfish_options' ); ?>
			<?php $options = get_option( 'pilotfish_theme_options' ); ?>

			<table class="form-table">
				<?php /* Some Updates about Daniel :) */ ?>
				<tr valign="top"><th scope="row"></th>
					<td>
						<p><?php _e( 'Thank you for downloading Pilot Fish (^^) For more information on customizing the theme, please go to ', 'pilotfish' ); ?><a href="<?php echo esc_url(__('http://wordpress.danielatwork.com/pilotfish/faq/','pilotfish')); ?>" target="_blank"><?php _e('Pilot Fish FAQ', 'pilotfish'); ?></a><?php _e(', or find Danni on Twitter ', 'pilotfish'); ?><a href="<?php echo esc_url(__('https://twitter.com/danni1990','pilotfish')); ?>" target="_blank"><?php _e('@danni1990', 'pilotfish'); ?></a></p>
					</td>
				</tr>
				<?php
				/**
				 * Add Google Analytics option
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Google Analytics', 'pilotfish' ); ?></th>
					<td>
						<input id="pilotfish_theme_options[add_ga]" name="pilotfish_theme_options[add_ga]" type="checkbox" value="1" <?php checked( '1', $options['add_ga'] ); ?> />
						<label class="description" for="pilotfish_theme_options[add_ga]"><?php _e( 'Add Google Analytics to footer', 'pilotfish' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Google Analytics tracking code
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Google Analytics Tracking Code', 'pilotfish' ); ?></th>
					<td>
						<input id="pilotfish_theme_options[ga_tracking_code]" class="regular-text" type="text" name="pilotfish_theme_options[ga_tracking_code]" value="<?php if( !empty($options['ga_tracking_code']) ) echo esc_attr( $options['ga_tracking_code'] ); ?>" />
						<label class="description" for="pilotfish_theme_options[ga_tracking_code]"><?php _e( 'UA-XXXXX-X', 'pilotfish' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Turning Page Hierarchy On or Off
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Page Hierarchy', 'pilotfish' ); ?></th>
					<td>
						<input id="pilotfish_theme_options[add_ph]" name="pilotfish_theme_options[add_ph]" type="checkbox" value="1" <?php checked( '1', $options['add_ph'] ); ?> />
						<label class="description" for="pilotfish_theme_options[add_ph]"><?php _e( 'Add Child Page links to Pages', 'pilotfish' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Replace Featured Image
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Replace Landing Page Featured Image Bavarian Forest', 'pilotfish' ); ?></th>
					<td>
						<input id="pilotfish_theme_options[featured_image_url]" class="regular-text" placeholder="http://" type="text" name="pilotfish_theme_options[featured_image_url]" value="<?php if( !empty($options['featured_image_url']) ) echo esc_attr( $options['featured_image_url'] ); ?>" />
						<label class="description" for="pilotfish_theme_options[featured_image_url]"><?php _e( 'URL to your image', 'pilotfish' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'pilotfish' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function pilotfish_theme_options_validate( $input ) {

	// Our checkbox value is either 0 or 1 for Google Analytics
	if ( ! isset( $input['add_ga'] ) )
		$input['add_ga'] = null;
	$input['add_ga'] = ( $input['add_ga'] == 1 ? 1 : 0 );

	// Say our text option must be safe text with no HTML tags
	$input['ga_tracking_code'] = wp_filter_nohtml_kses( $input['ga_tracking_code'] );

	// Our checkbox value is either 0 or 1 for Page Hierarchy
	if ( ! isset( $input['add_ph'] ) )
		$input['add_ph'] = null;
	$input['add_ph'] = ( $input['add_ph'] == 1 ? 1 : 0 );

	return $input;
}
// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
