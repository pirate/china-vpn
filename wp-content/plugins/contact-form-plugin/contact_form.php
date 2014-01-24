<?php
/*
Plugin Name: Contact Form
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin for Contact Form.
Author: BestWebSoft
Version: 3.69
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/
/*  @ Copyright 2013  BestWebSoft  ( http://support.bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once( dirname( __FILE__ ) . '/bws_menu/bws_menu.php' );
// Add option page in admin menu
if( ! function_exists( 'cntctfrm_admin_menu' ) ) {
	function cntctfrm_admin_menu() {
		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( "images/px.png", __FILE__ ), 1001 ); 
		add_submenu_page('bws_plugins', __( 'Contact Form Settings', 'contact_form' ), __( 'Contact Form', 'contact_form' ), 'manage_options', "contact_form.php", 'cntctfrm_settings_page');
		add_submenu_page('contact_form.php', __( 'Contact Form Pro Extra Settings', 'contact_form' ), __( 'Contact Form Pro', 'contact_form' ), 'manage_options', "contact_form_pro_extra.php", 'cntctfrm_settings_page_extra');

		//call register settings function
		add_action( 'admin_init', 'cntctfrm_settings' );
	}
}

// Register settings for plugin
if( ! function_exists( 'cntctfrm_settings' ) ) {
	function cntctfrm_settings() {
		global $wpmu, $cntctfrm_options, $cntctfrm_option_defaults, $wpdb, $bws_plugin_info;

		if ( function_exists( 'get_plugin_data' ) && ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) ) ) {
			$plugin_info = get_plugin_data( __FILE__ );	
			$bws_plugin_info = array( 'id' => '77', 'version' => $plugin_info["Version"] );
		}

		$cntctfrm_option_defaults = array(
			'cntctfrm_user_email'				=> 'admin',
			'cntctfrm_custom_email'				=> '',
			'cntctfrm_select_email'				=> 'user',
			'cntctfrm_from_email'				=> 'user',
			'cntctfrm_custom_from_email'		=> '',
			'cntctfrm_additions_options'		=> 0,
			'cntctfrm_attachment'				=> 0,
			'cntctfrm_attachment_explanations'	=> 1,
			'cntctfrm_send_copy'				=> 0,
			'cntctfrm_from_field'				=> get_bloginfo( 'name' ),
			'cntctfrm_select_from_field'		=> 'custom',
			'cntctfrm_display_name_field'		=> 1,
			'cntctfrm_display_address_field' 	=> 0,
			'cntctfrm_display_phone_field' 		=> 0,
			'cntctfrm_required_name_field' 		=> 1,
			'cntctfrm_required_address_field' 	=> 0,
			'cntctfrm_required_email_field' 	=> 1,
			'cntctfrm_required_phone_field' 	=> 0,
			'cntctfrm_required_subject_field' 	=> 1,
			'cntctfrm_required_message_field' 	=> 1,
			'cntctfrm_required_symbol'			=> '*',
			'cntctfrm_display_add_info' 		=> 1,
			'cntctfrm_display_sent_from' 		=> 1,
			'cntctfrm_display_date_time' 		=> 1,
			'cntctfrm_mail_method' 				=> 'wp-mail',
			'cntctfrm_display_coming_from' 		=> 1,
			'cntctfrm_display_user_agent' 		=> 1,
			'cntctfrm_language'					=> array(),
			'cntctfrm_change_label'				=> 0,
			'cntctfrm_name_label' 				=> array( 'en' => __( "Name:", 'contact_form' ) ),
			'cntctfrm_address_label' 			=> array( 'en' => __( "Address:", 'contact_form' ) ),
			'cntctfrm_email_label' 				=> array( 'en' => __( "Email Address:", 'contact_form' ) ),		 	
			'cntctfrm_phone_label' 				=> array( 'en' => __( "Phone number:", 'contact_form' ) ),
			'cntctfrm_subject_label' 			=> array( 'en' => __( "Subject:", 'contact_form' ) ),
			'cntctfrm_message_label' 			=> array( 'en' => __( "Message:", 'contact_form' ) ),
			'cntctfrm_attachment_label'			=> array( 'en' => __( "Attachment:", 'contact_form' ) ),
			'cntctfrm_attachment_tooltip'		=> array( 'en' => __( "Supported file types: HTML, TXT, CSS, GIF, PNG, JPEG, JPG, TIFF, BMP, AI, EPS, PS, RTF, PDF, DOC, DOCX, XLS, ZIP, RAR, WAV, MP3, PPT. Max file size: 2MB", 'contact_form' ) ),		
			'cntctfrm_send_copy_label'			=> array( 'en' => __( "Send me a copy", 'contact_form' ) ),
			'cntctfrm_submit_label'				=> array( 'en' => __( "Submit", 'contact_form' ) ),
			'cntctfrm_name_error' 				=> array( 'en' => __( "Your name is required.", 'contact_form' ) ),
			'cntctfrm_address_error' 			=> array( 'en' => __( "Address is required.", 'contact_form' ) ),
			'cntctfrm_email_error' 				=> array( 'en' => __( "A valid email address is required.", 'contact_form' ) ),
			'cntctfrm_phone_error' 				=> array( 'en' => __( "Phone number is required.", 'contact_form' ) ),
			'cntctfrm_subject_error' 			=> array( 'en' => __( "Subject is required.", 'contact_form' ) ),
			'cntctfrm_message_error' 			=> array( 'en' => __( "Message text is required.", 'contact_form' ) ),
			'cntctfrm_attachment_error' 		=> array( 'en' => __( "File format is not valid.", 'contact_form' ) ),
			'cntctfrm_attachment_upload_error'	=> array( 'en' => __( "File upload error.", 'contact_form' ) ),
			'cntctfrm_attachment_move_error' 	=> array( 'en' => __( "The file could not be uploaded.", 'contact_form' ) ),
			'cntctfrm_attachment_size_error' 	=> array( 'en' => __( "This file is too large.", 'contact_form' ) ),
			'cntctfrm_captcha_error' 			=> array( 'en' => __( "Please fill out the CAPTCHA.", 'contact_form' ) ),
			'cntctfrm_form_error'				=> array( 'en' => __( "Please make corrections below and try again.", 'contact_form' ) ),
			'cntctfrm_action_after_send' 		=> 1,
			'cntctfrm_thank_text' 				=> array( 'en' => __( "Thank you for contacting us.", 'contact_form' ) ),
			'cntctfrm_redirect_url'				=> '',
			'cntctfrm_delete_attached_file'		=> '0',
			'cntctfrm_html_email'				=> 1
		);

		// install the option defaults
		if ( 1 == $wpmu ) {
			if ( !get_site_option( 'cntctfrm_options' ) ) {
				add_site_option( 'cntctfrm_options', $cntctfrm_option_defaults, '', 'yes' );
			}
		} else {
			if ( !get_option( 'cntctfrm_options' ) )
				add_option( 'cntctfrm_options', $cntctfrm_option_defaults, '', 'yes' );
		}

		// get options from the database
		if ( 1 == $wpmu )
			$cntctfrm_options = get_site_option( 'cntctfrm_options' );
		else
			$cntctfrm_options = get_option( 'cntctfrm_options' );

		if ( empty( $cntctfrm_options['cntctfrm_language'] ) && ! is_array( $cntctfrm_options['cntctfrm_name_label'] ) ) {
			$cntctfrm_options['cntctfrm_name_label']				= array( 'en' => $cntctfrm_options['cntctfrm_name_label'] );
			$cntctfrm_options['cntctfrm_address_label']				= array( 'en' => $cntctfrm_options['cntctfrm_address_label'] );
			$cntctfrm_options['cntctfrm_email_label']				= array( 'en' => $cntctfrm_options['cntctfrm_email_label'] );
			$cntctfrm_options['cntctfrm_phone_label']				= array( 'en' => $cntctfrm_options['cntctfrm_phone_label'] );
			$cntctfrm_options['cntctfrm_subject_label']				= array( 'en' => $cntctfrm_options['cntctfrm_subject_label'] );
			$cntctfrm_options['cntctfrm_message_label']				= array( 'en' => $cntctfrm_options['cntctfrm_message_label'] );
			$cntctfrm_options['cntctfrm_attachment_label']			= array( 'en' => $cntctfrm_options['cntctfrm_attachment_label'] );
			$cntctfrm_options['cntctfrm_attachment_tooltip']		= array( 'en' => $cntctfrm_options['cntctfrm_attachment_tooltip'] );
			$cntctfrm_options['cntctfrm_send_copy_label']			= array( 'en' => $cntctfrm_options['cntctfrm_send_copy_label'] );
			$cntctfrm_options['cntctfrm_thank_text']				= array( 'en' => $cntctfrm_options['cntctfrm_thank_text'] );
			$cntctfrm_options['cntctfrm_submit_label']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_submit_label']['en'] );
			$cntctfrm_options['cntctfrm_name_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_name_error']['en'] );
			$cntctfrm_options['cntctfrm_address_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_address_error']['en'] );
			$cntctfrm_options['cntctfrm_email_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_email_error']['en'] );
			$cntctfrm_options['cntctfrm_phone_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_phone_error']['en'] );
			$cntctfrm_options['cntctfrm_subject_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_subject_error']['en'] );
			$cntctfrm_options['cntctfrm_message_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_message_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_error']			= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_upload_error']	= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_upload_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_move_error']		= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_move_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_size_error']		= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_size_error']['en'] );
			$cntctfrm_options['cntctfrm_captcha_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_captcha_error']['en'] );
			$cntctfrm_options['cntctfrm_form_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_form_error']['en'] );
		}

		if ( isset( $cntctfrm_options['cntctfrm_required_symbol'] ) && $cntctfrm_options['cntctfrm_required_symbol'] == '1' )
			$cntctfrm_options['cntctfrm_required_symbol'] = '*';
		elseif ( isset( $cntctfrm_options['cntctfrm_required_symbol'] ) && $cntctfrm_options['cntctfrm_required_symbol'] == '0' )
			$cntctfrm_options['cntctfrm_required_symbol'] = '';

		$cntctfrm_options = array_merge( $cntctfrm_option_defaults, $cntctfrm_options );

		update_option( 'cntctfrm_options', $cntctfrm_options );

		// create db table of fields list
		$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "cntctfrm_field" );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cntctfrm_field` (
			id int NOT NULL AUTO_INCREMENT,							
			name CHAR(100) NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$fields = array( 
			'name',
			'email',
			'subject',
			'message',
			'address',
			'phone',
			'attachment',
			'attachment_explanations',
			'send_copy',
			'sent_from',
			'date_time',
			'coming_from',
			'user_agent'
		);
		foreach ( $fields as $key => $value ) {
			$db_row = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_field WHERE `name` = '" . $value . "'", ARRAY_A );
			if ( !isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert(  $wpdb->prefix . "cntctfrm_field", array( 'name' => $value ), array( '%s' ) );	
			}
		}
	}
}

/* Function check if plugin is compatible with current WP version  */
if ( ! function_exists ( 'cntctfrm_version_check' ) ) {
	function cntctfrm_version_check() {
		global $wp_version;
		$plugin_data	=	get_plugin_data( __FILE__, false );
		$require_wp		=	"3.0"; /* Wordpress at least requires version */
		$plugin			=	plugin_basename( __FILE__ );
	 	if ( version_compare( $wp_version, $require_wp, "<" ) ) {
			if( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
				wp_die( "<strong>" . $plugin_data['Name'] . " </strong> " . __( 'requires', 'contact_form' ) . " <strong>WordPress " . $require_wp . "</strong> " . __( 'or higher, that is why it has been deactivated! Please upgrade WordPress and try again.', 'contact_form') . "<br /><br />" . __( 'Back to the WordPress', 'contact_form') . " <a href='" . get_admin_url( null, 'plugins.php' ) . "'>" . __( 'Plugins page', 'contact_form') . "</a>." );
			}
		}
	}
}

// Add settings page in admin area
if( ! function_exists( 'cntctfrm_settings_page' ) ) {
	function cntctfrm_settings_page() {
		global $cntctfrm_options, $wpdb, $cntctfrm_option_defaults, $wp_version;

		$plugin_info = get_plugin_data( __FILE__ );
		/* Get Captcha options */
		if ( get_option( 'cptch_options' ) )
			$cptch_options = get_option( 'cptch_options' );
		if ( get_option( 'cptchpr_options' ) )
			$cptchpr_options = get_option( 'cptchpr_options' );
		/* Get Contact Form to DB options */
		if ( get_option( 'cntctfrmtdb_options' ) )
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
		if ( get_option( 'cntctfrmtdbpr_options' ) )
			$cntctfrmtdbpr_options = get_option( 'cntctfrmtdbpr_options' );

		$userslogin = $wpdb->get_col( "SELECT user_login FROM  $wpdb->users ", 0 ); 

		$error = "";	
		// Save data for settings page
		if( isset( $_POST['cntctfrm_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'cntctfrm_nonce_name' ) ) {
			$cntctfrm_options_submit['cntctfrm_user_email'] = $_POST['cntctfrm_user_email'];
			$cntctfrm_options_submit['cntctfrm_custom_email'] = stripslashes( $_POST['cntctfrm_custom_email'] );
			$cntctfrm_options_submit['cntctfrm_select_email'] = $_POST['cntctfrm_select_email'];
			$cntctfrm_options_submit['cntctfrm_from_email'] = $_POST['cntctfrm_from_email'];
			$cntctfrm_options_submit['cntctfrm_custom_from_email'] = stripslashes( $_POST['cntctfrm_custom_from_email'] );
			$cntctfrm_options_submit['cntctfrm_additions_options'] = isset( $_POST['cntctfrm_additions_options']) ? $_POST['cntctfrm_additions_options'] : 0;
			if ( $cntctfrm_options_submit['cntctfrm_additions_options'] == 0 ) {
				$cntctfrm_options_submit['cntctfrm_attachment']					= 0;
				$cntctfrm_options_submit['cntctfrm_attachment_explanations']	= 1;
				$cntctfrm_options_submit['cntctfrm_send_copy']					= 0;
				$cntctfrm_options_submit['cntctfrm_from_field']					= get_bloginfo( 'name' );
				$cntctfrm_options_submit['cntctfrm_select_from_field']			= 'custom';				
				$cntctfrm_options_submit['cntctfrm_display_name_field']			= 1;
				$cntctfrm_options_submit['cntctfrm_display_address_field']		= 0;
				$cntctfrm_options_submit['cntctfrm_display_phone_field']		= 0;				
				$cntctfrm_options_submit['cntctfrm_required_name_field']		= 1;
				$cntctfrm_options_submit['cntctfrm_required_address_field']		= 0;
				$cntctfrm_options_submit['cntctfrm_required_email_field']		= 1;
				$cntctfrm_options_submit['cntctfrm_required_phone_field']		= 0;
				$cntctfrm_options_submit['cntctfrm_required_subject_field']		= 1;
				$cntctfrm_options_submit['cntctfrm_required_message_field']		= 1; 
				$cntctfrm_options_submit['cntctfrm_required_symbol']			= '*';
				$cntctfrm_options_submit['cntctfrm_display_add_info']			= 1;
				$cntctfrm_options_submit['cntctfrm_display_sent_from']			= 1;
				$cntctfrm_options_submit['cntctfrm_display_date_time']			= 1;
				$cntctfrm_options_submit['cntctfrm_mail_method']				= 'wp-mail';
				$cntctfrm_options_submit['cntctfrm_display_coming_from'] 		= 1;
				$cntctfrm_options_submit['cntctfrm_display_user_agent']			= 1;
				$cntctfrm_options_submit['cntctfrm_change_label']				= 0;
				$cntctfrm_options_submit['cntctfrm_action_after_send']			= 1;
				$cntctfrm_options_submit['cntctfrm_delete_attached_file']		= 0;
				$cntctfrm_options_submit['cntctfrm_html_email']					= 1;
				if ( empty( $cntctfrm_options['cntctfrm_language'] ) ) {
					$cntctfrm_options_submit['cntctfrm_name_label']					= $cntctfrm_option_defaults['cntctfrm_name_label'];
					$cntctfrm_options_submit['cntctfrm_address_label']				= $cntctfrm_option_defaults['cntctfrm_address_label'];
					$cntctfrm_options_submit['cntctfrm_email_label']				= $cntctfrm_option_defaults['cntctfrm_email_label'];
					$cntctfrm_options_submit['cntctfrm_phone_label']				= $cntctfrm_option_defaults['cntctfrm_phone_label'];
					$cntctfrm_options_submit['cntctfrm_subject_label']				= $cntctfrm_option_defaults['cntctfrm_subject_label'];
					$cntctfrm_options_submit['cntctfrm_message_label']				= $cntctfrm_option_defaults['cntctfrm_message_label'];
					$cntctfrm_options_submit['cntctfrm_attachment_label']			= $cntctfrm_option_defaults['cntctfrm_attachment_label'];
					$cntctfrm_options_submit['cntctfrm_attachment_tooltip']			= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip'];
					$cntctfrm_options_submit['cntctfrm_send_copy_label']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label'];
					$cntctfrm_options_submit['cntctfrm_thank_text']					= $cntctfrm_option_defaults['cntctfrm_thank_text'];
					$cntctfrm_options_submit['cntctfrm_submit_label']				= $cntctfrm_option_defaults['cntctfrm_submit_label'];
					$cntctfrm_options_submit['cntctfrm_name_error']					= $cntctfrm_option_defaults['cntctfrm_name_error'];
					$cntctfrm_options_submit['cntctfrm_address_error']				= $cntctfrm_option_defaults['cntctfrm_address_error'];
					$cntctfrm_options_submit['cntctfrm_email_error']				= $cntctfrm_option_defaults['cntctfrm_email_error'];
					$cntctfrm_options_submit['cntctfrm_phone_error']				= $cntctfrm_option_defaults['cntctfrm_phone_error'];
					$cntctfrm_options_submit['cntctfrm_subject_error']				= $cntctfrm_option_defaults['cntctfrm_subject_error'];
					$cntctfrm_options_submit['cntctfrm_message_error']				= $cntctfrm_option_defaults['cntctfrm_message_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_error']			= $cntctfrm_option_defaults['cntctfrm_attachment_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_upload_error']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_move_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_move_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_size_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_size_error'];
					$cntctfrm_options_submit['cntctfrm_captcha_error']				= $cntctfrm_option_defaults['cntctfrm_captcha_error'];
					$cntctfrm_options_submit['cntctfrm_form_error']					= $cntctfrm_option_defaults['cntctfrm_form_error'];
				} else {
					$cntctfrm_options_submit['cntctfrm_name_label']['en']				= $cntctfrm_option_defaults['cntctfrm_name_label']['en'];
					$cntctfrm_options_submit['cntctfrm_address_label']['en']			= $cntctfrm_option_defaults['cntctfrm_address_label']['en'];
					$cntctfrm_options_submit['cntctfrm_email_label']['en']				= $cntctfrm_option_defaults['cntctfrm_email_label']['en'];
					$cntctfrm_options_submit['cntctfrm_phone_label']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_label']['en'];
					$cntctfrm_options_submit['cntctfrm_subject_label']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_label']['en'];
					$cntctfrm_options_submit['cntctfrm_message_label']['en']			= $cntctfrm_option_defaults['cntctfrm_message_label']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_label']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_label']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_tooltip']['en']		= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip']['en'];
					$cntctfrm_options_submit['cntctfrm_send_copy_label']['en']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label']['en'];
					$cntctfrm_options_submit['cntctfrm_thank_text']['en']				= $cntctfrm_option_defaults['cntctfrm_thank_text']['en'];
					$cntctfrm_options_submit['cntctfrm_submit_label']['en']				= $cntctfrm_option_defaults['cntctfrm_submit_label']['en'];
					$cntctfrm_options_submit['cntctfrm_name_error']['en']				= $cntctfrm_option_defaults['cntctfrm_name_error']['en'];
					$cntctfrm_options_submit['cntctfrm_address_error']['en']			= $cntctfrm_option_defaults['cntctfrm_address_error']['en'];
					$cntctfrm_options_submit['cntctfrm_email_error']['en']				= $cntctfrm_option_defaults['cntctfrm_email_error']['en'];
					$cntctfrm_options_submit['cntctfrm_phone_error']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_error']['en'];
					$cntctfrm_options_submit['cntctfrm_subject_error']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_error']['en'];
					$cntctfrm_options_submit['cntctfrm_message_error']['en']			= $cntctfrm_option_defaults['cntctfrm_message_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_error']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_upload_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_move_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_move_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_size_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_size_error']['en'];
					$cntctfrm_options_submit['cntctfrm_captcha_error']['en']			= $cntctfrm_option_defaults['cntctfrm_captcha_error']['en'];
					$cntctfrm_options_submit['cntctfrm_form_error']['en']				= $cntctfrm_option_defaults['cntctfrm_form_error']['en'];
				}
			 $cntctfrm_options_submit['cntctfrm_redirect_url']				= '';
			} else {
				
				$cntctfrm_options_submit['cntctfrm_mail_method']				= $_POST['cntctfrm_mail_method'];
				$cntctfrm_options_submit['cntctfrm_from_field']					= $_POST['cntctfrm_from_field'];
				$cntctfrm_options_submit['cntctfrm_select_from_field']			= $_POST['cntctfrm_select_from_field'];
				$cntctfrm_options_submit['cntctfrm_display_name_field']			= isset( $_POST['cntctfrm_display_name_field']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_display_address_field']		= isset( $_POST['cntctfrm_display_address_field']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_display_phone_field']		= isset( $_POST['cntctfrm_display_phone_field']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_attachment']					= isset( $_POST['cntctfrm_attachment']) ? $_POST['cntctfrm_attachment'] : 0;
				$cntctfrm_options_submit['cntctfrm_attachment_explanations']	= isset( $_POST['cntctfrm_attachment_explanations']) ? $_POST['cntctfrm_attachment_explanations'] : 0;
				$cntctfrm_options_submit['cntctfrm_send_copy']					= isset( $_POST['cntctfrm_send_copy']) ? $_POST['cntctfrm_send_copy'] : 0;

				$cntctfrm_options_submit['cntctfrm_delete_attached_file'] = isset( $_POST['cntctfrm_delete_attached_file']) ? $_POST['cntctfrm_delete_attached_file'] : 0;

				if ( isset( $_POST['cntctfrm_display_captcha'] ) ) {
					if ( get_option( 'cptch_options' ) ) {
						$cptch_options['cptch_contact_form'] = 1;
						update_option( 'cptch_options', $cptch_options, '', 'yes' );
					}
					if ( get_option( 'cptchpr_options' ) ) {
						$cptchpr_options['cptchpr_contact_form'] = 1;
						update_option( 'cptchpr_options', $cptchpr_options, '', 'yes' );
					}
				} else {
					if ( get_option( 'cptch_options' ) ) {
						$cptch_options['cptch_contact_form'] = 0;
						update_option( 'cptch_options', $cptch_options, '', 'yes' );
					}
					if ( get_option( 'cptchpr_options' ) ) {
						$cptchpr_options['cptchpr_contact_form'] = 0;
						update_option( 'cptchpr_options', $cptchpr_options, '', 'yes' );
					}
				}			

				if ( isset( $_POST['cntctfrm_save_email_to_db'] ) ) {
					if ( get_option( 'cntctfrmtdb_options' ) ) {
						$cntctfrmtdb_options['cntctfrmtdb_save_messages_to_db'] = 1;
						update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options, '', 'yes' );
					}
					if ( get_option( 'cntctfrmtdbpr_options' ) ) {
						$cntctfrmtdbpr_options['save_messages_to_db'] = 1;
						update_option( 'cntctfrmtdbpr_options', $cntctfrmtdbpr_options, '', 'yes' );
					}
				} else {
					if ( get_option( 'cntctfrmtdb_options' ) ) {
						$cntctfrmtdb_options['cntctfrmtdb_save_messages_to_db'] = 0;
						update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options, '', 'yes' );
					}
					if ( get_option( 'cntctfrmtdbpr_options' ) ) {
						$cntctfrmtdbpr_options['save_messages_to_db'] = 0;
						update_option( 'cntctfrmtdbpr_options', $cntctfrmtdbpr_options, '', 'yes' );
					}
				}
				
				if ( $cntctfrm_options_submit['cntctfrm_display_name_field'] == 0 ) {
					$cntctfrm_options_submit['cntctfrm_required_name_field'] = 0;
				} else {
					$cntctfrm_options_submit['cntctfrm_required_name_field'] = isset( $_POST['cntctfrm_required_name_field']) ? 1 : 0;
				}
				if ( $cntctfrm_options_submit['cntctfrm_display_address_field'] == 0 ) {
					$cntctfrm_options_submit['cntctfrm_required_address_field']	= 0;
				} else {
					$cntctfrm_options_submit['cntctfrm_required_address_field']	= isset( $_POST['cntctfrm_required_address_field']) ? 1 : 0;
				}
				$cntctfrm_options_submit['cntctfrm_required_email_field'] = isset( $_POST['cntctfrm_required_email_field']) ? 1 : 0;
				if ( $cntctfrm_options_submit['cntctfrm_display_phone_field'] == 0 ) {
					$cntctfrm_options_submit['cntctfrm_required_phone_field']	= 0;
				} else {
					$cntctfrm_options_submit['cntctfrm_required_phone_field']	= isset( $_POST['cntctfrm_required_phone_field']) ? 1 : 0;
				}
				$cntctfrm_options_submit['cntctfrm_required_subject_field']		= isset( $_POST['cntctfrm_required_subject_field']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_required_message_field']		= isset( $_POST['cntctfrm_required_message_field']) ? 1 : 0;

				$cntctfrm_options_submit['cntctfrm_required_symbol']			= isset( $_POST['cntctfrm_required_symbol']) ? $_POST['cntctfrm_required_symbol'] : '*';
				
				$cntctfrm_options_submit['cntctfrm_html_email'] = isset( $_POST['cntctfrm_html_email']) ? 1 : 0;

				$cntctfrm_options_submit['cntctfrm_display_add_info']			= isset( $_POST['cntctfrm_display_add_info']) ? 1 : 0;				
				if ( $cntctfrm_options_submit['cntctfrm_display_add_info'] == 1 ) {
					$cntctfrm_options_submit['cntctfrm_display_sent_from']		= isset( $_POST['cntctfrm_display_sent_from']) ? 1 : 0;
					$cntctfrm_options_submit['cntctfrm_display_date_time']		= isset( $_POST['cntctfrm_display_date_time']) ? 1 : 0;
					$cntctfrm_options_submit['cntctfrm_display_coming_from']	= isset( $_POST['cntctfrm_display_coming_from']) ? 1 : 0;
					$cntctfrm_options_submit['cntctfrm_display_user_agent']		= isset( $_POST['cntctfrm_display_user_agent']) ? 1 : 0;
				} else {
					$cntctfrm_options_submit['cntctfrm_display_sent_from']		= 1;
					$cntctfrm_options_submit['cntctfrm_display_date_time']		= 1;
					$cntctfrm_options_submit['cntctfrm_display_coming_from']	= 1;
					$cntctfrm_options_submit['cntctfrm_display_user_agent']		= 1;
				}

				$cntctfrm_options_submit['cntctfrm_change_label']				= isset( $_POST['cntctfrm_change_label']) ? 1 : 0;
				if ( $cntctfrm_options_submit['cntctfrm_change_label'] == 1 ) {
					foreach ( $_POST['cntctfrm_name_label'] as $key => $val ){
						$cntctfrm_options_submit['cntctfrm_name_label'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_name_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_address_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_address_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_email_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_email_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_phone_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_phone_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_subject_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_subject_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_message_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_message_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_label'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_tooltip'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_tooltip'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_send_copy_label'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_send_copy_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_thank_text'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_submit_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_submit_label'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_name_error'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_name_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_address_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_address_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_email_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_email_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_phone_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_phone_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_subject_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_subject_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_message_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_message_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_error'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_upload_error'][ $key ]	= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_upload_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_move_error'][ $key ]		= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_move_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_attachment_size_error'][ $key ]		= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_size_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_captcha_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_captcha_error'][ $key ] ) );
						$cntctfrm_options_submit['cntctfrm_form_error'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_form_error'][ $key ] ) );
					}
				} else {
					if( empty( $cntctfrm_options['cntctfrm_language'] ) ) {
						$cntctfrm_options_submit['cntctfrm_name_label']					= $cntctfrm_option_defaults['cntctfrm_name_label'];
						$cntctfrm_options_submit['cntctfrm_address_label']				= $cntctfrm_option_defaults['cntctfrm_address_label'];
						$cntctfrm_options_submit['cntctfrm_email_label']				= $cntctfrm_option_defaults['cntctfrm_email_label'];
						$cntctfrm_options_submit['cntctfrm_phone_label']				= $cntctfrm_option_defaults['cntctfrm_phone_label'];
						$cntctfrm_options_submit['cntctfrm_subject_label']				= $cntctfrm_option_defaults['cntctfrm_subject_label'];
						$cntctfrm_options_submit['cntctfrm_message_label']				= $cntctfrm_option_defaults['cntctfrm_message_label'];
						$cntctfrm_options_submit['cntctfrm_attachment_label']			= $cntctfrm_option_defaults['cntctfrm_attachment_label'];
						$cntctfrm_options_submit['cntctfrm_attachment_tooltip']			= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip'];
						$cntctfrm_options_submit['cntctfrm_send_copy_label']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label'];
						$cntctfrm_options_submit['cntctfrm_thank_text']					= $_POST['cntctfrm_thank_text'];
						$cntctfrm_options_submit['cntctfrm_submit_label']				= $cntctfrm_option_defaults['cntctfrm_submit_label'];
						$cntctfrm_options_submit['cntctfrm_name_error']					= $cntctfrm_option_defaults['cntctfrm_name_error'];
						$cntctfrm_options_submit['cntctfrm_address_error']				= $cntctfrm_option_defaults['cntctfrm_address_error'];
						$cntctfrm_options_submit['cntctfrm_email_error']				= $cntctfrm_option_defaults['cntctfrm_email_error'];
						$cntctfrm_options_submit['cntctfrm_phone_error']				= $cntctfrm_option_defaults['cntctfrm_phone_error'];
						$cntctfrm_options_submit['cntctfrm_subject_error']				= $cntctfrm_option_defaults['cntctfrm_subject_error'];
						$cntctfrm_options_submit['cntctfrm_message_error']				= $cntctfrm_option_defaults['cntctfrm_message_error'];
						$cntctfrm_options_submit['cntctfrm_attachment_error']			= $cntctfrm_option_defaults['cntctfrm_attachment_error'];
						$cntctfrm_options_submit['cntctfrm_attachment_upload_error']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error'];
						$cntctfrm_options_submit['cntctfrm_attachment_move_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_move_error'];
						$cntctfrm_options_submit['cntctfrm_attachment_size_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_size_error'];
						$cntctfrm_options_submit['cntctfrm_captcha_error']				= $cntctfrm_option_defaults['cntctfrm_captcha_error'];
						$cntctfrm_options_submit['cntctfrm_form_error']					= $cntctfrm_option_defaults['cntctfrm_form_error'];	
						foreach ( $cntctfrm_options_submit['cntctfrm_thank_text'] as $key => $val ) {
							$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ] = stripcslashes( htmlspecialchars( $val ) );
						}				
					} else {
						$cntctfrm_options_submit['cntctfrm_name_label']['en']				= $cntctfrm_option_defaults['cntctfrm_name_label']['en'];
						$cntctfrm_options_submit['cntctfrm_address_label']['en']			= $cntctfrm_option_defaults['cntctfrm_address_label']['en'];
						$cntctfrm_options_submit['cntctfrm_email_label']['en']				= $cntctfrm_option_defaults['cntctfrm_email_label']['en'];
						$cntctfrm_options_submit['cntctfrm_phone_label']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_label']['en'];
						$cntctfrm_options_submit['cntctfrm_subject_label']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_label']['en'];
						$cntctfrm_options_submit['cntctfrm_message_label']['en']			= $cntctfrm_option_defaults['cntctfrm_message_label']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_label']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_label']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_tooltip']['en']		= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip']['en'];
						$cntctfrm_options_submit['cntctfrm_send_copy_label']['en']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label']['en'];
						$cntctfrm_options_submit['cntctfrm_submit_label']['en']				= $cntctfrm_option_defaults['cntctfrm_submit_label']['en'];
						$cntctfrm_options_submit['cntctfrm_name_error']['en']				= $cntctfrm_option_defaults['cntctfrm_name_error']['en'];
						$cntctfrm_options_submit['cntctfrm_address_error']['en']			= $cntctfrm_option_defaults['cntctfrm_address_error']['en'];
						$cntctfrm_options_submit['cntctfrm_email_error']['en']				= $cntctfrm_option_defaults['cntctfrm_email_error']['en'];
						$cntctfrm_options_submit['cntctfrm_phone_error']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_error']['en'];
						$cntctfrm_options_submit['cntctfrm_subject_error']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_error']['en'];
						$cntctfrm_options_submit['cntctfrm_message_error']['en']			= $cntctfrm_option_defaults['cntctfrm_message_error']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_error']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_error']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_upload_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_move_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_move_error']['en'];
						$cntctfrm_options_submit['cntctfrm_attachment_size_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_size_error']['en'];
						$cntctfrm_options_submit['cntctfrm_captcha_error']['en']			= $cntctfrm_option_defaults['cntctfrm_captcha_error']['en'];
						$cntctfrm_options_submit['cntctfrm_form_error']['en']				= $cntctfrm_option_defaults['cntctfrm_form_error']['en'];
						
						foreach ( $_POST['cntctfrm_thank_text'] as $key => $val ) {
							$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ] = stripcslashes( htmlspecialchars( $_POST['cntctfrm_thank_text'][ $key ] ) );
						}
					}
				}
				$cntctfrm_options_submit['cntctfrm_action_after_send']	= $_POST['cntctfrm_action_after_send'];
				$cntctfrm_options_submit['cntctfrm_redirect_url']	= $_POST['cntctfrm_redirect_url'];				
			}
			$cntctfrm_options = array_merge( $cntctfrm_options, $cntctfrm_options_submit  );
			if( $cntctfrm_options_submit['cntctfrm_action_after_send'] == 0 
				&& ( trim( $cntctfrm_options_submit['cntctfrm_redirect_url'] ) == "" 
				|| !preg_match( '@^(?:http://)?([^/]+)@i', trim( $cntctfrm_options_submit['cntctfrm_redirect_url'] ) ) ) ) {
					$error .=__(  "If the 'Redirect to page' option is selected then the URL field should be in the following format", 'contact_form' )." <code>http://your_site/your_page</code>";
					$cntctfrm_options['cntctfrm_action_after_send'] = 1;
			}
			if ( 'user' == $cntctfrm_options_submit['cntctfrm_select_email'] ) {
				if ( '3.3' > $wp_version && function_exists( 'get_userdatabylogin' ) && false !== get_userdatabylogin( $cntctfrm_options_submit['cntctfrm_user_email'] ) ) {
					//
				} else if( false !== get_user_by( 'login', $cntctfrm_options_submit['cntctfrm_user_email'] ) ) {
					//
				} else {
					$error .=__(  "Such user does not exist. Settings are not saved.", 'contact_form' );
				}
			} else {
				if ( $cntctfrm_options_submit['cntctfrm_custom_email'] == "" || !preg_match( "/^((?:[a-z0-9_']+(?:[a-z0-9\-_\.']+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", trim( $cntctfrm_options_submit['cntctfrm_custom_email'] ) ) ){
					$error .= __( "Please enter a valid email address in the 'FROM' field. Settings are not saved.", 'contact_form' );
				}
			}
			if ( 'custom' == $cntctfrm_options_submit['cntctfrm_from_email'] ) {
				if( $cntctfrm_options_submit['cntctfrm_custom_from_email'] == "" 
					&& !preg_match( "/^((?:[a-z0-9_']+(?:[a-z0-9\-_\.']+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", trim( $cntctfrm_options_submit['cntctfrm_custom_from_email'] ) ) ) {
					$error .= __( "Please enter a valid email address in the 'FROM' field. Settings are not saved.", 'contact_form' );
				}
			}
			if ( $error == '' ) {
				update_option( 'cntctfrm_options', $cntctfrm_options, '', 'yes' );
				$message = __( "Settings saved.", 'contact_form' );
			}
		}
		// Display form on the setting page
		$lang_codes = array(
			'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'bm' => 'Bambara', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali',
			'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese', 'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree',
			'cs' => 'Czech', 'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha', 'eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe', 'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Western Frisian', 'ff' => 'Fulah', 'ka' => 'Georgian', 'de' => 'German', 'gd' => 'Gaelic; Scottish Gaelic',
			'ga' => 'Irish', 'gl' => 'Galician', 'gv' => 'Manx', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian', 'ig' => 'Igbo', 'is' => 'Icelandic', 'io' => 'Ido', 'ii' => 'Sichuan Yi', 'iu' => 'Inuktitut', 'ie' => 'Interlingue',
			'ia' => 'Interlingua (International Auxiliary Language Association)', 'id' => 'Indonesian', 'ik' => 'Inupiaq', 'it' => 'Italian', 'jv' => 'Javanese', 'ja' => 'Japanese', 'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'ks' => 'Kashmiri', 'kr' => 'Kanuri', 'kk' => 'Kazakh', 'km' => 'Central Khmer', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz',
			'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish; Letzeburgesch', 'lu' => 'Luba-Katanga', 'lg' => 'Ganda', 'mk' => 'Macedonian', 'mh' => 'Marshallese', 'ml' => 'Malayalam',
			'mi' => 'Maori', 'mr' => 'Marathi', 'ms' => 'Malay', 'mg' => 'Malagasy', 'mt' => 'Maltese', 'mo' => 'Moldavian', 'mn' => 'Mongolian', 'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
			'no' => 'Norwegian', 'ny' => 'Chichewa; Chewa; Nyanja', 'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pi' => 'Pali', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto', 'qu' => 'Quechua', 'rm' => 'Romansh', 'ro' => 'Romanian', 'rn' => 'Rundi', 'ru' => 'Russian',
			'sg' => 'Sango', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'hr' => 'Croatian', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'se' => 'Northern Sami', 'sm' => 'Samoan', 'sn' => 'Shona', 'sd' => 'Sindhi', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'sc' => 'Sardinian', 'ss' => 'Swati', 'su' => 'Sundanese', 'sw' => 'Swahili',
			'sv' => 'Swedish', 'ty' => 'Tahitian', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'tg' => 'Tajik', 'tl' => 'Tagalog', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'tn' => 'Tswana', 'ts' => 'Tsonga', 'tk' => 'Turkmen', 'tr' => 'Turkish', 'tw' => 'Twi', 'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
			've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük', 'cy' => 'Welsh','wa' => 'Walloon','wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'za' => 'Zhuang; Chuang', 'zu' => 'Zulu' );
		
	?>
	<div class="wrap">
		<div class="icon32 icon32-bws" id="icon-options-general"></div>
		<h2><?php _e( "Contact Form Settings", 'contact_form' ); ?></h2>
		<div class="updated fade" <?php if ( ! isset( $_POST['cntctfrm_form_submit'] ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
		<div id="cntctfrm_settings_notice" class="updated fade" style="display:none"><p><strong><?php _e( "Notice:", 'contact_form' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'contact_form' ); ?></p></div>
		<div class="error" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
		<ul class="subsubsub">
			<li><a class="current" href="admin.php?page=contact_form.php"><?php _e( 'Settings', 'contact_form' ); ?></a></li> |
			<li><a href="admin.php?page=contact_form_pro_extra.php"><?php _e( 'Extra settings', 'contact_form' ); ?></a></li>
		</ul>
		<div class="clear"></div>
		<form id="cntctfrm_settings_form" method="post" action="admin.php?page=contact_form.php">
			<span style="margin-bottom:15px;">
				<p><?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget:", 'contact_form' ); ?> [contact_form] <?php _e( "or", 'contact_form' ); ?> [contact_form lang=en]<br />
				<?php _e( "If have any problems with the standard shortcode [contact_form], you should use the shortcode", 'contact_form' ); ?> [bws_contact_form] (<?php _e( "or", 'contact_form' ); ?> [bws_contact_form lang=en]) <?php _e( "or", 'contact_form' ); ?> [bestwebsoft_contact_form] (<?php _e( "or", 'contact_form' ); ?> 
[bestwebsoft_contact_form lang=en]). <?php _e( "They work the same way.", 'contact_form' ); ?></p>
				<?php _e( "If you leave the fields empty, the messages will be sent to the email address specified during registration.", 'contact_form' ); ?>
			</span>
			<table class="form-table" style="width:auto;">
				<tr valign="top">
					<th scope="row" style="width:200px;"><?php _e( "The user's email address:", 'contact_form' ); ?> </th>
					<td colspan="2" style="width:750px;">
						<input type="radio" id="cntctfrm_select_email_user" name="cntctfrm_select_email" value="user" <?php if ( $cntctfrm_options['cntctfrm_select_email'] == 'user' ) echo "checked=\"checked\" "; ?>/>
						<select name="cntctfrm_user_email">
							<option disabled><?php _e( "Create a username", 'contact_form' ); ?></option>
							<?php while( list( $key, $value ) = each( $userslogin ) ) { ?>
								<option value="<?php echo $value; ?>" <?php if( $cntctfrm_options['cntctfrm_user_email'] == $value ) echo "selected=\"selected\" "; ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
						<span class="cntctfrm_info"><?php _e( "Enter a username of the person who should get the messages from the contact form.", 'contact_form' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width:200px;"><?php _e( "Use this email address:", 'contact_form' ); ?> </th>
					<td colspan="2">
						<input type="radio" id="cntctfrm_select_email_custom" name="cntctfrm_select_email" value="custom" <?php if ( $cntctfrm_options['cntctfrm_select_email'] == 'custom' ) echo "checked=\"checked\" "; ?>/> <input type="text" name="cntctfrm_custom_email" value="<?php echo $cntctfrm_options['cntctfrm_custom_email']; ?>" onfocus="document.getElementById('cntctfrm_select_email_custom').checked = true;" />
						<span class="cntctfrm_info"><?php _e( "Enter the email address you want the messages forwarded to.", 'contact_form' ); ?></span>
					</td>
				</tr>
			</table>
			<table class="form-table bws_pro_version" style="width:auto;">
				<tr valign="top">
					<th scope="row" style="width:200px;"><?php _e( "Add department selectbox to the contact form:", 'contact_form' ); ?></th>
					<td colspan="2">
						<input type="radio" id="cntctfrmpr_select_email_department" name="cntctfrmpr_select_email" value="departments" disabled="disabled" /> 
						<div class="cntctfrmpr_department_table"><img src="<?php echo plugins_url( 'images/pro_screen_1.png', __FILE__ ); ?>" alt="" /></div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" colspan="2">
						* <?php _e( 'If you upgrade to Pro version all your settings will be saved.', 'contact_form' ); ?>
					</th>
				</tr>
				<tr valign="top" class="bws_pro_version_tooltip">
					<th scope="row" colspan="2">
						<?php _e( 'This functionality is available in the Pro version of the plugin. For more details, please follow the link', 'contact_form' ); ?>
						<a title="Contact Form Pro" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-pro/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"> <?php _e( 'Contact Form Pro', 'contact_form' ); ?></a>
					</th>
				</tr>
			</table>
			<table class="form-table" style="width:auto;">
				<tr valign="top">
					<th scope="row" style="width:200px;"><?php _e( "Save emails to the database", 'contact_form' ); ?> </th>
					<td colspan="2">
						<?php if ( ! function_exists( 'is_plugin_active_for_network' ) )
							require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
						$all_plugins = get_plugins();
						$active_plugins = get_option( 'active_plugins' );						
						if ( array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) || array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) {
							if ( 0 < count( preg_grep( '/contact-form-to-db\/contact_form_to_db.php/', $active_plugins ) ) || 0 < count( preg_grep( '/contact-form-to-db-pro\/contact_form_to_db_pro.php/', $active_plugins ) ) ||
								is_plugin_active_for_network( 'contact-form-to-db/contact_form_to_db.php' ) || is_plugin_active_for_network( 'contact-form-to-db-pro/contact_form_to_db_pro.php' ) ) { ?>
								<input type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( ( isset( $cntctfrmtdb_options ) && 1 == $cntctfrmtdb_options["cntctfrmtdb_save_messages_to_db"] ) || ( isset( $cntctfrmtdbpr_options ) && 1 == $cntctfrmtdbpr_options["save_messages_to_db"] ) ) echo "checked=\"checked\""; ?> />
								<span style="color: #888888;font-size: 10px;"> (<?php _e( 'Using Contact Form to DB powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>)</span>
							<?php } else { ?>
								<input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( ( isset( $cntctfrmtdb_options ) && 1 == $cntctfrmtdb_options["cntctfrmtdb_save_messages_to_db"] ) || ( isset( $cntctfrmtdbpr_options ) && 1 == $cntctfrmtdbpr_options["save_messages_to_db"] ) ) echo "checked=\"checked\""; ?> /> 
								<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Contact Form to DB powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate Contact Form to DB', 'contact_form' ); ?></a></span>
							<?php }
						} else { ?>
							<input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" />  
							<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Contact Form to DB powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/plugin/contact-form-to-db-pro/?k=19d806f45d866e70545de83169b274f2&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"><?php _e( 'Download Contact Form to DB', 'contact_form' ); ?></a></span>
						<?php } ?>
					</td>
				</tr>			
				<tr valign="top">
					<th scope="row" style="width:200px;"><label><input type="checkbox" id="cntctfrm_additions_options" name="cntctfrm_additions_options" value="1" <?php if($cntctfrm_options['cntctfrm_additions_options'] == '1') echo "checked=\"checked\" "; ?> /> <?php _e( "Additional options", 'contact_form' ); ?></label></th>
					<td colspan="2">
						<input id="cntctfrm_show_additional_settings" type="button" class="button-small button" value="<?php _e( "Show", 'contact_form' ); ?>" style="display: none;">
						<input id="cntctfrm_hide_additional_settings" type="button" class="button-small button" value="<?php _e( "Hide", 'contact_form' ); ?>" style="display: none;">
					</td>
				</tr>
				<tr class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( 'What to use?', 'contact_form' ); ?></th>
					<td colspan="2">
						<label><input type='radio' name='cntctfrm_mail_method' value='wp-mail' <?php if( $cntctfrm_options['cntctfrm_mail_method'] == 'wp-mail' ) echo "checked=\"checked\" "; ?>/>
						<?php _e( 'Wp-mail', 'contact_form' ); ?></label> <span class="cntctfrm_info">(<?php _e( 'You can use the wp_mail function for mailing', 'contact_form' ); ?>)</span><br />
						<label><input type='radio' name='cntctfrm_mail_method' value='mail' <?php if($cntctfrm_options['cntctfrm_mail_method'] == 'mail') echo "checked=\"checked\" "; ?>/>
						<?php _e( 'Mail', 'contact_form' ); ?> </label> <span class="cntctfrm_info">(<?php _e( 'To send mail you can use the php mail function', 'contact_form' ); ?>)</span><br />
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0') echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "The text in the 'From' field", 'contact_form' ); ?></th>
					<td colspan="2">
						<label><input type="radio" id="cntctfrm_select_from_field" name="cntctfrm_select_from_field" value="user_name" <?php if ( $cntctfrm_options['cntctfrm_select_from_field'] == 'user_name') echo "checked=\"checked\" "; ?>/> <?php _e( "User name", 'contact_form' ); ?></label> 
						<span class="cntctfrm_info">(<?php _e( "The name of the user who fills the form will be used in the field 'From'.", 'contact_form' ); ?>)</span><br/>
						<input type="radio" id="cntctfrm_select_from_custom_field" name="cntctfrm_select_from_field" value="custom" <?php if ( $cntctfrm_options['cntctfrm_select_from_field'] == 'custom') echo "checked=\"checked\" "; ?>/> 
						<input type="text" style="width:200px;" name="cntctfrm_from_field" value="<?php echo stripslashes( $cntctfrm_options['cntctfrm_from_field'] ); ?>" onfocus="document.getElementById('cntctfrm_select_from_custom_field').checked = true;" />
						<span  class="cntctfrm_info">(<?php _e( "This text will be used in the 'FROM' field", 'contact_form' ); ?>)</span>
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "The email address in the 'From' field", 'contact_form' ); ?></th>
					<td colspan="2">
						<label><input type="radio" id="cntctfrm_from_email" name="cntctfrm_from_email" value="user" <?php if( $cntctfrm_options['cntctfrm_from_email'] == 'user' ) echo "checked=\"checked\" "; ?>/> <?php _e( "User email", 'contact_form' ); ?> </label>
						<span class="cntctfrm_info">(<?php _e( "The email address of the user who fills the form will be used in the field 'From'.", 'contact_form' ); ?>)</span><br />
						<input type="radio" id="cntctfrm_from_custom_email" name="cntctfrm_from_email" value="custom" <?php if ( $cntctfrm_options['cntctfrm_from_email'] == 'custom') echo "checked=\"checked\" "; ?>/> 
						<input type="text" name="cntctfrm_custom_from_email" value="<?php echo $cntctfrm_options['cntctfrm_custom_from_email']; ?>" onfocus="document.getElementById('cntctfrm_from_custom_email').checked = true;" />
						<span class="cntctfrm_info">(<?php _e( "This email address will be used in the 'From' field.", 'contact_form' ); ?>)</span>
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Required symbol", 'contact_form' ); ?></th>
					<td colspan="2">
						<input type="text" id="cntctfrm_required_symbol" name="cntctfrm_required_symbol" value="<?php echo $cntctfrm_options['cntctfrm_required_symbol']; ?>"/>
					</td>
				</tr>
			</table>
			<br />
			<table class="cntctfrm_settings_table cntctfrm_additions_block<?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0') echo " cntctfrm_hidden"; ?>" style="width:auto;">
				<thead>
					<tr valign="top">
						<th scope="row" style="width: 210px;"><?php _e( "Fields", 'contact_form' ); ?></th>
						<th><?php _e( "Used", 'contact_form' ); ?></th>
						<th><?php _e( "Required", 'contact_form' ); ?></th>
						<th><?php _e( "Visible", 'contact_form' ); ?></th>
						<th><?php _e( "Disabled for editing", 'contact_form' ); ?></th>
						<th scope="row" style="width:200px;"><?php _e( "Field's default value", 'contact_form' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr valign="top">
						<td><?php _e( "Name", 'contact_form' ); ?></td>
						<td><input type="checkbox" name="cntctfrm_display_name_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_name_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td><input type="checkbox" id="cntctfrm_required_name_field" name="cntctfrm_required_name_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_name_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr valign="top">
						<td><?php _e( "Address", 'contact_form' ); ?></td>
						<td><input type="checkbox" id="cntctfrm_display_address_field" name="cntctfrm_display_address_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_address_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td><input type="checkbox" id="cntctfrm_required_address_field" name="cntctfrm_required_address_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_address_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr valign="top">
						<td><?php _e( "Email Address", 'contact_form' ); ?></td>
						<td></td>
						<td><input type="checkbox" id="cntctfrm_required_email_field" name="cntctfrm_required_email_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_email_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr valign="top">
						<td><?php _e( "Phone number", 'contact_form' ); ?></td>
						<td><input type="checkbox" id="cntctfrm_display_phone_field" name="cntctfrm_display_phone_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_phone_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td><input type="checkbox" id="cntctfrm_required_phone_field" name="cntctfrm_required_phone_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_phone_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr valign="top">
						<td><?php _e( "Subject", 'contact_form' ); ?></td>
						<td></td>
						<td><input type="checkbox" id="cntctfrm_required_subject_field" name="cntctfrm_required_subject_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_subject_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td class="bws_pro_version"><input class="subject" disabled="disabled" type="checkbox" name="cntctfrmpr_visible_subject" value="1" /></td>
						<td class="bws_pro_version"><input class="subject" disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_subject" value="1" /></td>						
						<td class="bws_pro_version"><input class="subject" disabled="disabled" type="text" name="cntctfrmpr_default_subject" value="" /></td>
					</tr>
					<tr valign="top">
						<td><?php _e( "Message", 'contact_form' ); ?></td>
						<td></td>
						<td><input type="checkbox" id="cntctfrm_required_message_field" name="cntctfrm_required_message_field" value="1" <?php if ( $cntctfrm_options['cntctfrm_required_message_field'] == '1' ) echo "checked=\"checked\" "; ?>/></td>						
						<td class="bws_pro_version"><input class="message" disabled="disabled" type="checkbox" name="cntctfrmpr_visible_message" value="1" /></td>
						<td class="bws_pro_version"><input class="message" disabled="disabled" disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_message" value="1" /></td>						
						<td class="bws_pro_version"><input class="message" disabled="disabled" type="text" name="cntctfrmpr_default_message" value="" /></td>
					</tr>
					<tr valign="top">
						<td></td>
						<td></td>
						<td></td>
						<td colspan="3" class="bws_pro_version_tooltip"> 
							<?php _e( 'This functionality is available in the Pro version of the plugin. For more details, please follow the link', 'contact_form' ); ?>
							<a title="Contact Form Pro" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-pro/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"> <?php _e( 'Contact Form Pro', 'contact_form' ); ?></a>
						</td>
					</tr>
					<tr valign="top">
						<td>
							<?php _e( "Attachment block", 'contact_form' ); ?> 
							<div class="cntctfrm_help_box" style="margin: -3px 0 0; float:right;">
								<div class="cntctfrm_hidden_help_text" style="display: none;"><?php echo __( "Users can attach the following file formats", 'contact_form' ) . ": html, txt, css, gif, png, jpeg, jpg, tiff, bmp, ai, eps, ps, rtf, pdf, doc, docx, xls, zip, rar, wav, mp3, ppt, aar, sce"; ?></div>
							</div>
						</td>
						<td><input type="checkbox" id="cntctfrm_attachment" name="cntctfrm_attachment" value="1" <?php if ( $cntctfrm_options['cntctfrm_attachment'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<br />		
			<table class="form-table" style="width:auto;">
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Add to the form", 'contact_form' ); ?></th>
					<td style="width:750px;" colspan="3">
						<div style="clear: both;">
							<label style="float: left">
								<input type="checkbox" id="cntctfrm_attachment_explanations" name="cntctfrm_attachment_explanations" value="1" <?php if ( $cntctfrm_options['cntctfrm_attachment_explanations'] == '1' && $cntctfrm_options['cntctfrm_attachment'] == '1' ) echo "checked=\"checked\" "; ?>/> 
								<?php _e( "Tips below the Attachment", 'contact_form' ); ?>
							</label> 
							<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
								<div class="cntctfrm_hidden_help_text" style="display: none;width: auto;"><img title="" src="<?php echo plugins_url( 'images/tooltip_attachment_tips.png', __FILE__ ); ?>" alt=""/></div>
							</div>
						</div>
						<div style="clear: both;">
							<label style="float: left">
								<input type="checkbox" id="cntctfrm_send_copy" name="cntctfrm_send_copy" value="1" <?php if ( $cntctfrm_options['cntctfrm_send_copy'] == '1') echo "checked=\"checked\" "; ?>/> 
								<?php _e( "'Send me a copy' block", 'contact_form' ); ?>
							</label>
							<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
								<div class="cntctfrm_hidden_help_text" style="display: none;width: auto;"><img title="" src="<?php echo plugins_url( 'images/tooltip_sendme_block.png', __FILE__ ); ?>" alt=""/></div>
							</div>
						</div>
						<div style="clear: both;">
							<?php $all_plugins = get_plugins();
							$active_plugins = get_option( 'active_plugins' );						
							if ( array_key_exists( 'captcha/captcha.php', $all_plugins ) || array_key_exists( 'captcha-pro/captcha_pro.php', $all_plugins ) ) {
								if ( 0 < count( preg_grep( '/captcha\/captcha.php/', $active_plugins ) ) || 0 < count( preg_grep( '/captcha-pro\/captcha_pro.php/', $active_plugins ) ) ||
									is_plugin_active_for_network( 'captcha/captcha.php' ) || is_plugin_active_for_network( 'captcha-pro/captcha_pro.php' ) ) { ?>
									<label><input type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( ( isset( $cptch_options ) && 1 == $cptch_options["cptch_contact_form"] ) || ( isset( $cptchpr_options ) && 1 == $cptchpr_options["cptchpr_contact_form"] ) ) echo "checked=\"checked\""; ?> />
									<?php _e( "Captcha", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>)</span>
								<?php } else { ?>
									<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( ( isset( $cptch_options ) && 1 == $cptch_options["cptch_contact_form"] ) || ( isset( $cptchpr_options ) && 1 == $cptchpr_options["cptchpr_contact_form"] ) ) echo "checked=\"checked\""; ?> /> 
									<?php _e( 'Captcha', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate captcha', 'contact_form' ); ?></a></span>
								<?php }
							} else { ?>
								<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" /> 
								<?php _e( 'Captcha', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/plugin/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/plugin/captcha-pro/?k=19ac1e9b23bea947cfc4a9b8e3326c03&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"><?php _e( 'Download captcha', 'contact_form' ); ?></a></span>
							<?php } ?>
						</div>						
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"></th>
					<td colspan="3" class="bws_pro_version">
						<label><input disabled="disabled" type="checkbox" value="1" name="cntctfrmpr_display_privacy_check"> <?php _e( 'Agreement checkbox', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'Required checkbox for submitting the form', 'contact_form' ); ?>)</span><br />
						<label><input disabled="disabled" type="checkbox" value="1" name="cntctfrmpr_display_optional_check"> <?php _e( 'Optional checkbox', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'Optional checkbox, the results of which will be displayed in email', 'contact_form' ); ?>)</span><br />
					</td>					
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th></th>
					<td colspan="3" class="bws_pro_version_tooltip"> 
						<?php _e( 'This functionality is available in the Pro version of the plugin. For more details, please follow the link', 'contact_form' ); ?>
						<a title="Contact Form Pro" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-pro/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"> <?php _e( 'Contact Form Pro', 'contact_form' ); ?></a>
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Delete an attachment file from the server after the email is sent", 'contact_form' ); ?> </th>
					<td colspan="3">
						<input type="checkbox" id="cntctfrm_delete_attached_file" name="cntctfrm_delete_attached_file" value="1" <?php if ( $cntctfrm_options['cntctfrm_delete_attached_file'] == '1' ) echo "checked=\"checked\" "; ?>/>
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Email in HTML format sending", 'contact_form' ); ?></th>
					<td colspan="2"><input type="checkbox" name="cntctfrm_html_email" value="1" <?php if ( $cntctfrm_options['cntctfrm_html_email'] == '1' ) echo "checked=\"checked\" "; ?>/></td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Display additional info in the email", 'contact_form' ); ?></th>
					<td style="width:15px;">
						<input type="checkbox" id="cntctfrm_display_add_info" name="cntctfrm_display_add_info" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_add_info'] == '1' ) echo "checked=\"checked\" "; ?>/>
					</td>
					<td style="max-width:150px;" class="cntctfrm_display_add_info_block <?php if ( $cntctfrm_options['cntctfrm_display_add_info'] == '0' ) echo "cntctfrm_hidden"; ?>">
						<label><input type="checkbox" id="cntctfrm_display_sent_from" name="cntctfrm_display_sent_from" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_sent_from'] == '1' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Sent from (ip address)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Sent from (IP address):	127.0.0.1", 'contact_form' ); ?></span><br />
						<label><input type="checkbox" id="cntctfrm_display_date_time" name="cntctfrm_display_date_time" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_date_time'] == '1' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Date/Time", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Date/Time:	August 19, 2013 8:50 pm", 'contact_form' ); ?></span><br />
						<label><input type="checkbox" id="cntctfrm_display_coming_from" name="cntctfrm_display_coming_from" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_coming_from'] == '1' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Sent from (referer)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Sent from (referer):	http://bestwebsoft.com/contacts/contact-us/", 'contact_form' ); ?></span><br />
						<label><input type="checkbox" id="cntctfrm_display_user_agent" name="cntctfrm_display_user_agent" value="1" <?php if ( $cntctfrm_options['cntctfrm_display_user_agent'] == '1' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Using (user agent)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Using (user agent):	Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36", 'contact_form' ); ?></span><br />
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Language settings for the field names in the form", 'contact_form' ); ?></th>
					<td colspan="2">
						<select name="cntctfrm_languages" id="cntctfrm_languages" style="width:300px;">
						<?php foreach ( $lang_codes as $key => $val ) {
							if ( in_array( $key, $cntctfrm_options['cntctfrm_language'] ) )
								continue;
							echo '<option value="' . esc_attr( $key ) . '"> ' . esc_html ( $val ) . '</option>';
						} ?>
						</select>
						<input type="button" class="button-primary" id="cntctfrm_add_language_button" value="<?php _e( 'Add a language', 'contact_form' ); ?>" />
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Change the names of the contact form fields and error messages", 'contact_form' ); ?></th>
					<td style="width:15px;">
						<input type="checkbox" id="cntctfrm_change_label" name="cntctfrm_change_label" value="1" <?php if ( $cntctfrm_options['cntctfrm_change_label'] == '1' ) echo "checked=\"checked\" "; ?>/>
					</td>
					<td class="cntctfrm_change_label_block <?php if ( $cntctfrm_options['cntctfrm_change_label'] == '0' ) echo "cntctfrm_hidden"; ?>">
						<div class="cntctfrm_label_language_tab cntctfrm_active" id="cntctfrm_label_en"><?php _e( 'English', 'contact_form' ); ?></div>
						<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) { 
							foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) {
								echo '<div class="cntctfrm_label_language_tab" id="cntctfrm_label_' . $val . '">' . $lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span></div>';
							} 
						} ?>
						<div class="clear"></div>
						<div class="cntctfrm_language_tab cntctfrm_tab_en">
							<div class="cntctfrm_language_tab_block_mini" style="display:none;"><br/></div>
							<div class="cntctfrm_language_tab_block">
								<input type="text" name="cntctfrm_name_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_name_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Name:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_address_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_address_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Address:", 'contact_form' ); ?></span><br />							
								<input type="text" name="cntctfrm_email_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_email_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Email Address:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_phone_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_phone_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Phone number:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_subject_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_subject_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Subject:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_message_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_message_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Message:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_attachment_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Attachment:", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_attachment_tooltip[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_tooltip']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Tips below the Attachment block", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_send_copy_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_send_copy_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Send me a copy", 'contact_form' ); ?></span><br />							
								<input type="text" name="cntctfrm_submit_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_submit_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Submit", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_name_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_name_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Name field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_address_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_address_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Address field", 'contact_form' ); ?></span><br />							
								<input type="text" name="cntctfrm_email_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_email_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Email field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_phone_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_phone_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Phone field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_subject_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_subject_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Subject field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_message_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_message_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Message field", 'contact_form' ); ?></span><br />							
								<input type="text" name="cntctfrm_attachment_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message about the file type for the Attachment field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_attachment_upload_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_upload_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while uploading a file for the Attachment field to the server", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_attachment_move_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_move_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_attachment_size_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_size_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message when file size limit for the Attachment field is exceeded", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_captcha_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_captcha_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Captcha field", 'contact_form' ); ?></span><br />
								<input type="text" name="cntctfrm_form_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_form_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the whole form", 'contact_form' ); ?></span><br />
							</div>
							<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); echo " [bestwebsoft_contact_form lang=en] " . __( "or", 'contact_form' ) . " [bestwebsoft_contact_form] "; _e( "for this language", 'contact_form' ); ?></span>
						</div>
						<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) { 
							foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) { ?>
								<div class="cntctfrm_language_tab hidden cntctfrm_tab_<?php echo $val; ?>">
									<div class="cntctfrm_language_tab_block_mini" style="display:none;"><br/></div>
									<div class="cntctfrm_language_tab_block">
										<input type="text" name="cntctfrm_name_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_name_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_name_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Name:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_address_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_address_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_address_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Address:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_email_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_email_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_email_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Email Address:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_phone_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_phone_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_phone_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Phone number:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_subject_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_subject_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_subject_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Subject:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_message_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_message_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_message_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Message:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_attachment_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Attachment:", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_attachment_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_tooltip'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_tooltip'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Tips below the Attachment block", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_send_copy_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_send_copy_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_send_copy_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Send me a copy", 'contact_form' ); ?></span><br />								
										<input type="text" name="cntctfrm_submit_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_submit_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_submit_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Submit", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_name_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_name_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_name_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Name field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_address_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_address_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_address_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Address field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_email_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_email_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_email_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Email field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_phone_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_phone_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_phone_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Phone field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_subject_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_subject_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_subject_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Subject field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_message_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_message_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_message_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Message field", 'contact_form' ); ?></span><br />									
										<input type="text" name="cntctfrm_attachment_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message about the file type for the Attachment field", 'contact_form' ); ?></span><br />									
										<input type="text" name="cntctfrm_attachment_upload_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_upload_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_upload_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while uploading a file for the Attachment field to the server", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_attachment_move_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_move_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_move_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_attachment_size_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_size_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_size_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message when file size limit for the Attachment field is exceeded", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_captcha_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_captcha_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_captcha_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Captcha field", 'contact_form' ); ?></span><br />
										<input type="text" name="cntctfrm_form_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_form_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_form_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the whole form", 'contact_form' ); ?></span><br />
									</div>
									<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); echo " [bestwebsoft_contact_form lang=" . $val . "] "; _e( "for this language", 'contact_form' ); ?></span>
								</div>
							<?php } 
						} ?>
					</td>
				</tr>
				<tr valign="top" class="cntctfrm_additions_block <?php if ( $cntctfrm_options['cntctfrm_additions_options'] == '0' ) echo "cntctfrm_hidden"; ?>">
					<th scope="row" style="width:200px;"><?php _e( "Action after email is sent", 'contact_form' ); ?></th>
					<td colspan="2" class="cntctfrm_action_after_send_block">
						<label><input type="radio" id="cntctfrm_action_after_send" name="cntctfrm_action_after_send" value="1" <?php if ( $cntctfrm_options['cntctfrm_action_after_send'] == '1' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Display text", 'contact_form' ); ?></label><br />
						<div class="cntctfrm_label_language_tab cntctfrm_active" id="cntctfrm_text_en"><?php _e( 'English', 'contact_form' ); ?></div>
						<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) { 
							foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) {
								echo '<div class="cntctfrm_label_language_tab" id="cntctfrm_text_' . $val . '">' . $lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span></div>';
							} 
						} ?>
						<div class="clear"></div>
						<div class="cntctfrm_language_tab cntctfrm_tab_en" style=" padding: 5px 10px 5px 5px;">
							<input type="text" name="cntctfrm_thank_text[en]" value="<?php echo $cntctfrm_options['cntctfrm_thank_text']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Text", 'contact_form' ); ?></span><br />
							<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); echo " [bestwebsoft_contact_form=en] " . __( "or", 'contact_form' ) . " [bestwebsoft_contact_form] "; _e( "for this language", 'contact_form' ); ?></span>
						</div>
						<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) { 
							foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) { ?>
								<div class="cntctfrm_language_tab hidden cntctfrm_tab_<?php echo $val; ?>" style=" padding: 5px 10px 5px 5px;">
									<input type="text" name="cntctfrm_thank_text[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_thank_text'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_thank_text'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Text", 'contact_form' ); ?></span><br />
									<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); echo " [bestwebsoft_contact_form lang=" . $val . "] "; _e( "for this language", 'contact_form' ); ?></span>
								</div>
							<?php } 
						} ?>
						<div id="cntctfrm_before"></div>
						<br />
						<input type="radio" id="cntctfrm_action_after_send_url" name="cntctfrm_action_after_send" value="0" <?php if ( $cntctfrm_options['cntctfrm_action_after_send'] == '0' ) echo "checked=\"checked\" "; ?>/> <?php _e( "Redirect to the page", 'contact_form' ); ?><br />
						<input type="text" name="cntctfrm_redirect_url" value="<?php echo $cntctfrm_options['cntctfrm_redirect_url']; ?>" onfocus="document.getElementById('cntctfrm_action_after_send_url').checked = true;" /> <span class="cntctfrm_info"><?php _e( "Url", 'contact_form' ); ?></span>
					</td>
			</table>    
			<input type="hidden" name="cntctfrm_form_submit" value="submit" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
			</p>
			<?php wp_nonce_field( plugin_basename(__FILE__), 'cntctfrm_nonce_name' ); ?>
		</form>
		<div class="bws-plugin-reviews">
			<div class="bws-plugin-reviews-rate">
			<?php _e( 'If you enjoy our plugin, please give it 5 stars on WordPress', 'contact_form' ); ?>:<br/>
			<a href="http://wordpress.org/support/view/plugin-reviews/contact-form-plugin" target="_blank" title="Contact Form reviews"><?php _e( 'Rate the plugin', 'contact_form' ); ?></a><br/>
			</div>
			<div class="bws-plugin-reviews-support">
			<?php _e( 'If there is something wrong about it, please contact us', 'contact_form' ); ?>:<br/>
			<a href="http://support.bestwebsoft.com">http://support.bestwebsoft.com</a>
			</div>
		</div>		
	</div>
	<?php 
	}
}

// Add settings page in admin area
if ( ! function_exists( 'cntctfrm_settings_page_extra' ) ) {
	function cntctfrm_settings_page_extra() {
		global $wpdb, $wp_version, $cntctfrm_options; 
		$plugin_info = get_plugin_data( __FILE__ );
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php _e( "Contact Form Pro | Extra Settings", 'contact_form' ); ?></h2>
			<ul class="subsubsub">
				<li><a href="admin.php?page=contact_form.php"><?php _e( 'Settings', 'contact_form' ); ?></a></li> |
				<li><a class="current" href="admin.php?page=contact_form_pro_extra.php"><?php _e( 'Extra settings', 'contact_form' ); ?></a></li>
			</ul>
			<div class="clear"></div>
			<div id="cntctfrmpr_left_table">
				<table class="form-table bws_pro_version" style="width:auto;" >
					<tr class="bws_pro_version_tooltip">
						<th scope="row" colspan="2">
							<?php _e( 'This functionality is available in the Pro version of the plugin. For more details, please follow the link', 'contact_form' ); ?>
							<a title="Contact Form Pro" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-pro/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"> <?php _e( 'Contact Form Pro', 'contact_form' ); ?></a>
						</th>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:200px;"><?php _e( "Errors output", 'contact_form' ); ?></th>
						<td colspan="2">
							<select name="cntctfrmpr_error_displaying">
								<option value="labels"><?php _e( "Display error messages", 'contact_form' ); ?></option>
								<option value="input_colors"><?php _e( "Color of the input field errors.", 'contact_form' ); ?></option>
								<option value="both" selected="selected"><?php _e( "Display error messages & color of the input field errors", 'contact_form' ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:200px;"><?php _e( "Add placeholder to the input blocks", 'contact_form' ); ?></th>
						<td colspan="2">
							<input disabled='disabled' type="checkbox" name="cntctfrmpr_placeholder" value="1" checked="checked"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:200px;"><?php _e( "Add tooltips", 'contact_form' ); ?></th>
						<td colspan="2">
							<div>
								<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_name" value="1" checked="checked"/>
								<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_name"><?php _e( "Name", 'contact_form' ); ?></label>
							</div>
							<?php if ( $cntctfrm_options['cntctfrm_display_address_field'] == '1' ) { ?>
								<div>
									<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_address" value="1" checked="checked"/>
									<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_address"><?php _e( "Address", 'contact_form' ); ?></label>
								</div>
							<?php } ?>
							<div>
								<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_email" value="1" checked="checked"/>
								<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_email"><?php _e( "Email address", 'contact_form' ); ?></label>
							</div>
							<?php if ( $cntctfrm_options['cntctfrm_display_phone_field'] == '1' ) { ?>
								<div>
									<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_phone" value="1" checked="checked"/>
									<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_phone"><?php _e( "Phone Number", 'contact_form' ); ?></label>
								</div>
							<?php } ?>
							<div>
								<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_subject" value="1" checked="checked"/>
								<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_subject"><?php _e( "Subject", 'contact_form' ); ?></label>
							</div>
							<div>
								<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_message" value="1" checked="checked"/>
								<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_message"><?php _e( "Message", 'contact_form' ); ?></label>
							</div>
							<?php if ( $cntctfrm_options['cntctfrm_attachment_explanations'] == '1' ) { ?>
								<div>
									<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_attachment" value="1" checked="checked"/>
									<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_attachment"><?php _e( "Attachment", 'contact_form' ); ?></label>
								</div>
							<?php } ?>
							<div>
								<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_captcha" value="1" />
								<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_captcha"><?php _e( "Captcha", 'contact_form' ); ?></label><span style="color: #888888;font-size: 10px;"><?php _e( '(powered by bestwebsoft.com)', 'contact_form' ); ?></span>
							</div>									
						</td>
					</tr>
					<tr valign="top">
						<th colspan="3" scope="row" style="width:200px;"><input disabled='disabled' type="checkbox" id="cntctfrmpr_style_options" name="cntctfrmpr_style_options" value="1" checked="checked" /> <?php _e( "Style options", 'contact_form' ); ?></th>
					</tr>				
					<tr valign="top" class="cntctfrmpr_style_block <?php if ( $cntctfrm_options['style_options'] == '0') echo "cntctfrmpr_hidden"; ?>">
						<th scope="row" style="width:200px;"><?php _e( "Text color", 'contact_form' ); ?></th>
						<td colspan="2">
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_label_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( 'Label text color', 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_input_placeholder_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( "Placeholder color", 'contact_form' ); ?>
							</div>
						</td>
					</tr>
					<tr valign="top" class="cntctfrmpr_style_block">
						<th scope="row" style="width:200px;"><?php _e( "Errors color", 'contact_form' ); ?></th>
						<td colspan="2">
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_error_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( 'Error text color', 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_error_input_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( 'Background color of the input field errors', 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_error_input_border_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( 'Border color of the input field errors', 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" id="" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_input_placeholder_error_color" value="" class="cntctfrmpr_colorPicker " />
								<?php _e( "Placeholder color of the input field errors", 'contact_form' ); ?>
							</div>
						</td>
					</tr>					
					<tr valign="top" class="cntctfrmpr_style_block">
						<th scope="row" style="width:200px;"><?php _e( "Input fields", 'contact_form' ); ?></th>
						<td colspan="2">
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" id="" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_input_background" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( "Input fields background color", 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_input_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( "Text fields color", 'contact_form' ); ?>
							</div>
							<input disabled='disabled' style="margin-left: 66px;" size="8" type="text" value="" name="cntctfrmpr_border_input_width" /> <?php _e( 'Border width in px, numbers only', 'contact_form' ); ?><br />
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />								
								<input disabled='disabled' type="text" name="cntctfrmpr_border_input_color" value="" class="cntctfrmpr_colorPicker" />
								 <?php _e( 'Border color', 'contact_form' ); ?>
							</div>
						</td>
					</tr>
					<tr valign="top" class="cntctfrmpr_style_block">
						<th scope="row" style="width:200px;"><?php _e( "Submit button", 'contact_form' ); ?></th>
						<td colspan="2">
							<input disabled='disabled' style="margin-left: 66px;" size="8" type="text" value="" name="cntctfrmpr_button_width" /> <?php _e( 'Width in px, numbers only', 'contact_form' ); ?><br />
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_button_backgroud" value="" class="cntctfrmpr_colorPicker" />
								 <?php _e( 'Button color', 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_button_color" value="" class="cntctfrmpr_colorPicker" />
								<?php _e( "Button text color", 'contact_form' ); ?>
							</div>
							<div>
								<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
								<input disabled='disabled' type="text" name="cntctfrmpr_border_button_color" value="" class="cntctfrmpr_colorPicker" />
								 <?php _e( 'Border color', 'contact_form' ); ?>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" colspan="2">
							* <?php _e( 'If you upgrade to Pro version all your settings will be saved.', 'contact_form' ); ?>
						</th>
					</tr>				
				</table>    
				<input type="hidden" name="cntctfrmpr_form_submit" value="submit" />
				<p class="submit">
					<input disabled='disabled' type="button" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</div>
			<div id="cntctfrmpr_right_table">
				<h3><?php _e( "Contact Form Pro | Preview", 'contact_form' ); ?></h3>
				<div id="cntctfrmpr_contact_form">
					<div id="cntctfrmpr_show_errors_block">
						<input disabled="" type="checkbox" id="cntctfrmpr_show_errors" name="cntctfrmpr_show_errors" /> <?php _e( "Show with errors", 'contact_form' ); ?>
					</div>
					<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_form_error']['en']; ?></div>
					<div style="text-align: left; padding-top: 5px;">
						<label for="cntctfrmpr_contact_name"><?php echo $cntctfrm_options['cntctfrm_name_label']['en']; if ( $cntctfrm_options['cntctfrm_required_name_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
					</div>
					<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_name_error']['en']; ?></div>
					<div style="text-align: left;">
						<input placeholder="<?php _e( "Please enter your full name...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_name" id="cntctfrmpr_contact_name" style="text-align: left; margin: 0;" />
						<div class="cntctfrmpr_help_box">
							<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your full name...", 'contact_form' ); ?></div>
						</div>
					</div>
					<?php if ( $cntctfrm_options['cntctfrm_display_address_field'] == 1 ) { ?>
						<div style="text-align: left;">
							<label for="cntctfrmpr_contact_address"><?php echo $cntctfrm_options['cntctfrm_address_label']['en']; if ( $cntctfrm_options['cntctfrm_required_address_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<?php if ( $cntctfrm_options['cntctfrm_required_address_field'] == 1 ) { ?>
							<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_address_error']['en']; ?></div>
						<?php } ?>
						<div style="text-align: left;">
							<input placeholder="<?php _e( "Please enter your address...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_address" id="cntctfrmpr_contact_address" style="text-align: left; margin: 0;" />
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your address...", 'contact_form' ); ?></div>
							</div>
						</div>
					<?php } ?>
					<div style="text-align: left;">
						<label for="cntctfrmpr_contact_email"><?php echo $cntctfrm_options['cntctfrm_email_label']['en']; if ( $cntctfrm_options['cntctfrm_required_email_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
					</div>
					<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_email_error']['en']; ?></div>
					<div style="text-align: left;">
						<input placeholder="<?php _e( "Please enter your email address...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_email" id="cntctfrmpr_contact_email" style="text-align: left; margin: 0;" />
						<div class="cntctfrmpr_help_box">
							<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your email address...", 'contact_form' ); ?></div>
						</div>
					</div>
					<?php if ( $cntctfrm_options['cntctfrm_display_phone_field'] == 1 ) { ?>
						<div style="text-align: left;">
							<label for="cntctfrmpr_contact_phone"><?php echo $cntctfrm_options['cntctfrm_phone_label']['en']; if ( $cntctfrm_options['cntctfrm_required_phone_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['phone_error']['en']; ?></div>
						<div style="text-align: left;">
							<input placeholder="<?php _e( "Please enter your phone number...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_phone" id="cntctfrmpr_contact_phone" style="text-align: left; margin: 0;" />
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your phone number...", 'contact_form' ); ?></div>
							</div>
						</div>
					<?php } ?>
					<div style="text-align: left;">
						<label for="cntctfrmpr_contact_subject"><?php echo $cntctfrm_options['cntctfrm_subject_label']['en']; if ( $cntctfrm_options['cntctfrm_required_subject_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
					</div>
					<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_subject_error']['en']; ?></div>
					<div style="text-align: left;">
						<input placeholder="<?php _e( "Please enter subject...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_subject" id="cntctfrmpr_contact_subject" style="text-align: left; margin: 0;" />
						<div class="cntctfrmpr_help_box">
							<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter subject...", 'contact_form' ); ?></div>
						</div>
					</div>
					<div style="text-align: left;">
						<label for="cntctfrmpr_contact_message"><?php echo $cntctfrm_options['cntctfrm_message_label']['en']; if ( $cntctfrm_options['cntctfrm_required_message_field'] == 1 ) echo '<span class="required"> *</span>'; ?></label>
					</div>
					<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_message_error']['en']; ?></div>
					<div style="text-align: left;">
						<textarea placeholder="<?php _e( "Please enter your message...", 'contact_form' ); ?>" rows="5" cols="30" name="cntctfrmpr_contact_message" id="cntctfrmpr_contact_message"></textarea>
						<div class="cntctfrmpr_help_box">
							<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your message...", 'contact_form' ); ?></div>
						</div>
					</div>
					<?php if ( $cntctfrm_options['cntctfrm_attachment'] == 1 ) { ?>
						<div style="text-align: left;">
							<label for="cntctfrmpr_contact_attachment"><?php echo $cntctfrm_options['cntctfrm_attachment_label']['en']; ?></label>
						</div>					
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_attachment_error']['en']; ?></div>
						<div style="text-align: left;">
						<input type="file" name="cntctfrmpr_contact_attachment" id="cntctfrmpr_contact_attachment" style="float:left;" />
						<?php if ( $cntctfrm_options['cntctfrm_attachment_explanations'] == 1 ) { ?>
							<div class="cntctfrmpr_help_box cntctfrmpr_hidden_help_text_attach"><div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php echo $cntctfrm_options['cntctfrm_attachment_tooltip']['en']; ?></div></div>
						<?php } ?>
						</div>
					<?php } ?>
					<?php if ( $cntctfrm_options['cntctfrm_send_copy'] == 1 ) { ?>
						<div style="text-align: left;">
							<input type="checkbox" value="1" name="cntctfrmpr_contact_send_copy" id="cntctfrmpr_contact_send_copy" style="text-align: left; margin: 0;" />
							<label for="cntctfrmpr_contact_send_copy"><?php echo $cntctfrm_options['cntctfrm_send_copy_label']['en']; ?></label>
						</div>
					<?php } ?>					
					<div style="text-align: left; padding-top: 8px;">
						<input type="submit" value="<?php echo $cntctfrm_options['cntctfrm_submit_label']['en']; ?>" style="cursor: pointer; margin: 0pt; text-align: center;margin-bottom:10px;" /> 
					</div>				
				</div>
				<div id="cntctfrmpr_shortcode">
					<?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget:", 'contact_form' ); ?><br/>
					<div>
						<code id="cntctfrmpr_shortcode_code">
							[bestwebsoft_contact_form]
						</code>					
					</div>
				</div>
			</div>
			<div class="clear"></div>	
		</div>			
	<?php }
}

// Display contact form in front end - page or post
if( ! function_exists( 'cntctfrm_display_form' ) ) {
	function cntctfrm_display_form( $atts = array( 'lang' => 'en' ) ) {
		global $error_message, $cntctfrm_options, $cntctfrm_result;
		extract( shortcode_atts( array( 'lang' => 'en' ), $atts ) );
		$cntctfrm_options = get_option( 'cntctfrm_options' );
		$content = "";

		if ( '80' != $_SERVER["SERVER_PORT"] )
            $page_url = $page_url = ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ? "https://" : "http://" ).$_SERVER["SERVER_NAME"].':'. $_SERVER["SERVER_PORT"].strip_tags( $_SERVER["REQUEST_URI"] );
		else
            $page_url = ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ? "https://" : "http://" ) . $_SERVER["SERVER_NAME"] . strip_tags( $_SERVER["REQUEST_URI"] );

		// If contact form submited
		$name = isset( $_POST['cntctfrm_contact_name'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_name'] ) ) : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_address'] ) ) : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_subject'] ) ) : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_message'] ) ) : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_phone'] ) ) : "";

		$name = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $name ) ) );
		$address = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $address ) ) );
		$email = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $email ) ) );  
		$subject = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $subject ) ) );  
		$message = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $message ) ) ); 
		$phone = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $phone ) ) );  

		$send_copy = isset( $_POST['cntctfrm_contact_send_copy'] ) ? $_POST['cntctfrm_contact_send_copy'] : "";
		// If it is good
		if ( true === $cntctfrm_result ) {
			$_SESSION['cntctfrm_send_mail'] = true;
			if ( $cntctfrm_options['cntctfrm_action_after_send'] == 1 )
				$content .= '<div id="cntctfrm_thanks">' . $cntctfrm_options['cntctfrm_thank_text'][ $lang ] . '</div>';
			else
				$content .= "<script type='text/javascript'>window.location.href = '" . $cntctfrm_options['cntctfrm_redirect_url']."';</script>";
		} else if ( false === $cntctfrm_result ) {
			// If email not be delivered
			$error_message['error_form'] = __( "Sorry, email message could not be delivered.", 'contact_form' );
		}
		if ( true !== $cntctfrm_result ) { 
			$_SESSION['cntctfrm_send_mail'] = false;
			// Output form
			$content .= '<form method="post" id="cntctfrm_contact_form" action="' . $page_url . '" enctype="multipart/form-data">';
			if ( isset( $error_message['error_form'] ) ) { 
				$content .= '<div style="text-align: left; color: red;">' . $error_message['error_form'].'</div>';
			}

			if ( $cntctfrm_options['cntctfrm_display_name_field'] == 1 ) { 
				$content .= '<div style="text-align: left; padding-top: 5px;">
						<label for="cntctfrm_contact_name">' . $cntctfrm_options['cntctfrm_name_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_name_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
					</div>';
				if ( isset( $error_message['error_name'] ) ) {
					$content .= '<div style="text-align: left; color: red;">' . $error_message['error_name'] . '</div>';
				}
				$content .= '<div style="text-align: left;">
						<input class="text" type="text" size="40" value="' . $name . '" name="cntctfrm_contact_name" id="cntctfrm_contact_name" style="text-align: left; margin: 0;" />
					</div>';
			}

			if ( $cntctfrm_options['cntctfrm_display_address_field'] == 1 ) { 
				$content .= '<div style="text-align: left;">
						<label for="cntctfrm_contact_address">' . $cntctfrm_options['cntctfrm_address_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_address_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
					</div>';
				if( isset( $error_message['error_address'] ) ) {
					$content .= '<div style="text-align: left; color: red;">' . $error_message['error_address'] . '</div>';
				}
				$content .= '<div style="text-align: left;">
						<input class="text" type="text" size="40" value="' . $address . '" name="cntctfrm_contact_address" id="cntctfrm_contact_address" style="text-align: left; margin: 0;" />
					</div>
					';
			}

			$content .= '<div style="text-align: left;">
					<label for="cntctfrm_contact_email">' . $cntctfrm_options['cntctfrm_email_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_email_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_email'] ) ) {
				$content .= '<div style="text-align: left; color: red;">' . $error_message['error_email'] . '</div>';
			}
			$content .= '<div style="text-align: left;">
					<input class="text" type="text" size="40" value="' . $email . '" name="cntctfrm_contact_email" id="cntctfrm_contact_email" style="text-align: left; margin: 0;" />
				</div>
			';

			if ( $cntctfrm_options['cntctfrm_display_phone_field'] == 1 ) { 
				$content .= '<div style="text-align: left;">
						<label for="cntctfrm_contact_phone">' . $cntctfrm_options['cntctfrm_phone_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_phone_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
					</div>';
				if ( isset( $error_message['error_phone'] ) ) {
					$content .= '<div style="text-align: left; color: red;">' . $error_message['error_phone'] . '</div>';
				}
				$content .= '<div style="text-align: left;">
						<input class="text" type="text" size="40" value="' . $phone . '" name="cntctfrm_contact_phone" id="cntctfrm_contact_phone" style="text-align: left; margin: 0;" />
					</div>
					';
			}
			$content .= '<div style="text-align: left;">
					<label for="cntctfrm_contact_subject">' . $cntctfrm_options['cntctfrm_subject_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_subject_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_subject'] ) ) {
				$content .= '<div style="text-align: left; color: red;">' . $error_message['error_subject'] . '</div>';
			}
			$content .= '<div style="text-align: left;">
					<input class="text" type="text" size="40" value="' . $subject . '" name="cntctfrm_contact_subject" id="cntctfrm_contact_subject" style="text-align: left; margin: 0;" />
				</div>

				<div style="text-align: left;">
					<label for="cntctfrm_contact_message">' . $cntctfrm_options['cntctfrm_message_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_message_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_message'] ) ) {
				$content .= '<div style="text-align: left; color: red;">' . $error_message['error_message'] . '</div>';
			}
			$content .= '<div style="text-align: left;">
					<textarea rows="5" cols="30" name="cntctfrm_contact_message" id="cntctfrm_contact_message">' . $message . '</textarea>
				</div>';
			if ( $cntctfrm_options['cntctfrm_attachment'] == 1 ) {
				$content .= '<div style="text-align: left;">
						<label for="cntctfrm_contact_attachment">' . $cntctfrm_options['cntctfrm_attachment_label'][ $lang ] . '</label>
					</div>';
				if ( isset( $error_message['error_attachment'] ) ) {
					$content .= '<div style="text-align: left; color: red;">' . $error_message['error_attachment'] . '</div>';
				}
				$content .= '<div style="text-align: left;">
						<input type="file" name="cntctfrm_contact_attachment" id="cntctfrm_contact_attachment"' . ( isset( $error_message['error_attachment'] ) ? "class='error'": "" ) . ' />';
				if ( $cntctfrm_options['cntctfrm_attachment_explanations'] == 1 ) {
						$content .= '<label style="font-size:10px;"><br />' . $cntctfrm_options['cntctfrm_attachment_tooltip'][ $lang ] . '</label>';
				}
				$content .= '
				</div>';
			}
			if ( $cntctfrm_options['cntctfrm_send_copy'] == 1 ) {
				$content .= '<div style="text-align: left;">
						<input type="checkbox" value="1" name="cntctfrm_contact_send_copy" id="cntctfrm_contact_send_copy" style="text-align: left; margin: 0;" ' . ( $send_copy == '1' ? " checked=\"checked\" " : "" ) . ' />
						<label for="cntctfrm_contact_send_copy">' . $cntctfrm_options['cntctfrm_send_copy_label'][ $lang ] . '</label>
					</div>';
			}

			if ( has_filter( 'cntctfrm_display_captcha' ) ) {
				$content .= apply_filters( 'cntctfrm_display_captcha' , $error_message );
			}
				
			$content .= '<div style="text-align: left; padding-top: 8px;">
					<input type="hidden" value="send" name="cntctfrm_contact_action"><input type="hidden" value="Version: 3.30" />
					<input type="hidden" value="' . $lang . '" name="cntctfrm_language">
					<input type="submit" value="'. $cntctfrm_options['cntctfrm_submit_label'][ $lang ] . '" style="cursor: pointer; margin: 0pt; text-align: center;margin-bottom:10px;" /> 
				</div>
				</form>';
		}
		return $content ;
	}
}

if ( ! function_exists( 'cntctfrm_check_and_send' ) ) {
	function cntctfrm_check_and_send() {
		global $cntctfrm_result;
		$cntctfrm_options = get_option( 'cntctfrm_options' );
		if ( isset( $_POST['cntctfrm_contact_action'] ) ) {
			// Check all input data
			$cntctfrm_result = cntctfrm_check_form();
		}
		// If it is good
		if ( true === $cntctfrm_result ) {
			$_SESSION['cntctfrm_send_mail'] = true;
			if ( $cntctfrm_options['cntctfrm_action_after_send'] == 0 ) {
				wp_redirect( $cntctfrm_options['cntctfrm_redirect_url'] ); 
				exit;
			}
		}
	}
}

// Check all input data
if ( ! function_exists( 'cntctfrm_check_form' ) ) {
	function cntctfrm_check_form() {
		global $error_message;
		global $cntctfrm_options;

		$language = isset( $_POST['cntctfrm_language'] ) ? $_POST['cntctfrm_language'] : 'en';
		$path_of_uploaded_file = '';
		if ( empty( $cntctfrm_options ) )
			$cntctfrm_options = get_option( 'cntctfrm_options' );
		$cntctfrm_result = "";
		// Error messages array
		$error_message = array();

		$name = isset( $_POST['cntctfrm_contact_name'] ) ?  htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_name'] ) ) : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_address'] ) ) : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_subject'] ) ) : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_message'] ) ) : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_phone'] ) ) : "";

		$name = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $name ) ) ); 
		$address = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $address ) ) ); 
		$email = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $email ) ) );  
		$subject = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $subject ) ) );  
		$message = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $message ) ) ); 
		$phone = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $phone ) ) );  

		if ( $cntctfrm_options['cntctfrm_required_name_field'] == 1 && $cntctfrm_options['cntctfrm_display_name_field'] == 1 )
			$error_message['error_name'] = $cntctfrm_options['cntctfrm_name_error'][$language];
		if ( $cntctfrm_options['cntctfrm_required_address_field'] == 1 && $cntctfrm_options['cntctfrm_display_address_field'] == 1 )
			$error_message['error_address'] = $cntctfrm_options['cntctfrm_address_error'][$language];
		if ( $cntctfrm_options['cntctfrm_required_email_field'] == 1 )
			$error_message['error_email'] = $cntctfrm_options['cntctfrm_email_error'][$language];
		if ( $cntctfrm_options['cntctfrm_required_subject_field'] == 1 )
			$error_message['error_subject'] = $cntctfrm_options['cntctfrm_subject_error'][$language];
		if ( $cntctfrm_options['cntctfrm_required_message_field'] == 1 )
			$error_message['error_message'] = $cntctfrm_options['cntctfrm_message_error'][$language];
		if ( $cntctfrm_options['cntctfrm_required_phone_field'] == 1 && $cntctfrm_options['cntctfrm_display_phone_field'] == 1 )
			$error_message['error_phone'] = $cntctfrm_options['cntctfrm_phone_error'][$language];
		$error_message['error_form'] = $cntctfrm_options['cntctfrm_form_error'][$language];
		if ( $cntctfrm_options['cntctfrm_attachment'] == 1 ) {
			global $path_of_uploaded_file, $mime_type;
			$mime_type= array(
				'html'=>'text/html', 
				'htm'=>'text/html', 
				'txt'=>'text/plain', 
				'css'=>'text/css', 
				'gif'=>'image/gif',
				'png'=>'image/x-png',
				'jpeg'=>'image/jpeg',
				'jpg'=>'image/jpeg',
				'JPG'=>'image/jpeg',
				'jpe'=>'image/jpeg',
				'TIFF'=>'image/tiff',
				'tiff'=>'image/tiff',
				'tif'=>'image/tiff',
				'TIF'=>'image/tiff',
				'bmp'=>'image/x-ms-bmp',
				'BMP'=>'image/x-ms-bmp',
				'ai'=>'application/postscript',
				'eps'=>'application/postscript',
				'ps'=>'application/postscript',
				'rtf'=>'application/rtf',
				'pdf'=>'application/pdf',
				'doc'=>'application/msword',
				'docx'=>'application/msword',
				'xls'=>'application/vnd.ms-excel',
				'zip'=>'application/zip',
				'rar'=>'application/rar',
				'wav'=>'audio/wav',
				'mp3'=>'audio/mp3',
				'ppt'=>'application/vnd.ms-powerpoint',
				'aar'=>'application/sb-replay',
				'sce'=>'application/sb-scenario' );
			$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_error'][ $language ];
		}
		// Check information wich was input in fields
		if ( $cntctfrm_options['cntctfrm_display_name_field'] == 1 && $cntctfrm_options['cntctfrm_required_name_field'] == 1 && "" != $name )
			unset( $error_message['error_name'] );
		if ( $cntctfrm_options['cntctfrm_display_address_field'] == 1 && $cntctfrm_options['cntctfrm_required_address_field'] == 1 && "" != $address )
			unset( $error_message['error_address'] );
		if ( $cntctfrm_options['cntctfrm_required_email_field'] == 1 && "" != $email && preg_match( "/^(?:[a-z0-9_']+(?:[a-z0-9\-_\.']+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})$/i", trim( stripslashes( $email ) ) ) )
			unset( $error_message['error_email'] );
		if ( $cntctfrm_options['cntctfrm_display_phone_field'] == 1 && $cntctfrm_options['cntctfrm_required_phone_field'] == 1 && "" != $phone )
			unset( $error_message['error_phone'] );
		if ( $cntctfrm_options['cntctfrm_required_subject_field'] == 1 && "" != $subject )
			unset( $error_message['error_subject'] );
		if ( $cntctfrm_options['cntctfrm_required_message_field'] == 1 && "" != $message )
			unset( $error_message['error_message'] );
		// If captcha plugin exists
		if ( ! apply_filters( 'cntctfrm_check_form', $_POST ) )
			$error_message['error_captcha'] = $cntctfrm_options['cntctfrm_captcha_error'][ $language ];
		if ( isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" ) {
			if( is_multisite() ){
				if ( defined('UPLOADS') ) {
					if( ! is_dir( ABSPATH . UPLOADS ) ) {
						wp_mkdir_p( ABSPATH . UPLOADS );
					}
					$path_of_uploaded_file = ABSPATH . UPLOADS . $_FILES["cntctfrm_contact_attachment"]["name"];
				} else if ( defined( 'BLOGUPLOADDIR' ) ) {
					if ( ! is_dir( BLOGUPLOADDIR ) ) {
						wp_mkdir_p( BLOGUPLOADDIR );
					}
					$path_of_uploaded_file = BLOGUPLOADDIR . $_FILES["cntctfrm_contact_attachment"]["name"];
				} else {
					$uploads = wp_upload_dir();
					if ( ! isset( $uploads['path'] ) && isset ( $uploads['error'] ) )
						$error_message['error_attachment'] = $uploads['error'];
					else
						$path_of_uploaded_file = $uploads['path'] . "/" . $_FILES["cntctfrm_contact_attachment"]["name"];
				}
			} else {
				$uploads = wp_upload_dir();
				if ( ! isset( $uploads['path'] ) && isset ( $uploads['error'] ) )
					$error_message['error_attachment'] = $uploads['error'];
				else
					$path_of_uploaded_file = $uploads['path'] . "/" . $_FILES["cntctfrm_contact_attachment"]["name"];
			}
			$tmp_path = $_FILES["cntctfrm_contact_attachment"]["tmp_name"];
			$path_info = pathinfo( $path_of_uploaded_file );

			if ( array_key_exists ( $path_info['extension'], $mime_type ) ) {
				if ( is_uploaded_file( $tmp_path ) ) {					
					if ( move_uploaded_file( $tmp_path, $path_of_uploaded_file ) ) {
						do_action( 'cntctfrm_get_attachment_data', $path_of_uploaded_file );
						unset( $error_message['error_attachment'] );
					} else {
						$letter_upload_max_size = substr( ini_get('upload_max_filesize'), -1);
						$upload_max_size = substr( ini_get('upload_max_filesize'), 0, -1); $upload_max_size= '1';
						switch( strtoupper( $letter_upload_max_size ) ) {
							case 'P':
								$upload_max_size *= 1024;
							case 'T':
								$upload_max_size *= 1024;
							case 'G':
								$upload_max_size *= 1024;
							case 'M':
								$upload_max_size *= 1024;
							case 'K':
								$upload_max_size *= 1024;
							    break;
						}		
						if ( isset( $upload_max_size ) && isset( $_FILES["cntctfrm_contact_attachment"]["size"] ) &&
							 $_FILES["cntctfrm_contact_attachment"]["size"] <= $upload_max_size ) {
							$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_move_error'][ $language ];
						} else {
							$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_size_error'][ $language ];
						}
					}
				} else {
					$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_upload_error'][ $language ];
				}
			}
		} else {
			unset( $error_message['error_attachment'] );
		}
		if( 1 == count( $error_message ) ) {
			unset( $error_message['error_form'] );
			// If all is good - send mail
			$cntctfrm_result = cntctfrm_send_mail();
			do_action( 'cntctfrm_check_dispatch', $cntctfrm_result );
		}
		return $cntctfrm_result;
	}
}

// Send mail function
if( ! function_exists( 'cntctfrm_send_mail' ) ) {
	function cntctfrm_send_mail() {
		global $cntctfrm_options, $path_of_uploaded_file, $wp_version;
		$to = "";

		$name = isset( $_POST['cntctfrm_contact_name'] ) ? $_POST['cntctfrm_contact_name'] : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? $_POST['cntctfrm_contact_address'] : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? stripslashes( $_POST['cntctfrm_contact_email'] ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? $_POST['cntctfrm_contact_subject'] : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? $_POST['cntctfrm_contact_message'] : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? $_POST['cntctfrm_contact_phone'] : "";
		$user_agent = cntctfrm_clean_input( $_SERVER['HTTP_USER_AGENT'] );

		$name = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $name ) ) ) ); 
		$address = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $address ) ) ) ); 
		$email = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $email ) ) ) );  
		$subject = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $subject ) ) ) );  
		$message = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $message ) ) ) ); 
		$phone = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', $phone ) ) ) );  

		if ( isset( $_SESSION['cntctfrm_send_mail'] ) && $_SESSION['cntctfrm_send_mail'] == true )
			return true;
		if ( $cntctfrm_options['cntctfrm_select_email'] == 'user' ) {
			if ( '3.3' > $wp_version && function_exists('get_userdatabylogin') && false !== $user = get_userdatabylogin( $cntctfrm_options['cntctfrm_user_email'] ) ) {
				$to = $user->user_email;
			} elseif ( false !== $user = get_user_by( 'login', $cntctfrm_options['cntctfrm_user_email'] ) )
				$to = $user->user_email;
		} else {
			$to = $cntctfrm_options['cntctfrm_custom_email'];
		}
		if ( "" == $to ) {
			// If email options are not certain choose admin email
			$to = get_option("admin_email");
		}
		if ( "" != $to ) {
			$user_info_string = '';
			$userdomain = '';
			$form_action_url = '';
			$attachments = array();
			$headers  = "";

			if ( getenv('HTTPS') == 'on' ) {
				$form_action_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$form_action_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			}

			if ( $cntctfrm_options['cntctfrm_display_add_info'] == 1) {
				$userdomain = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
				if ( $cntctfrm_options['cntctfrm_display_add_info'] == 1 ||
						$cntctfrm_options['cntctfrm_display_sent_from'] == 1 ||
						$cntctfrm_options['cntctfrm_display_coming_from'] == 1 ||
						$cntctfrm_options['cntctfrm_display_user_agent'] == 1 ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td><br /></td><td><br /></td></tr>';
				}
				if ( $cntctfrm_options['cntctfrm_display_sent_from'] == 1 ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>' . __('Sent from (ip address)', 'contact_form').':</td><td>'.$_SERVER['REMOTE_ADDR']." ( ". $userdomain ." )".'</td></tr>';
					else
						$user_info_string .= __('Sent from (ip address)', 'contact_form') . ': ' . $_SERVER['REMOTE_ADDR'] . " ( " . $userdomain . " )" . "\n";					
				}
				if ( $cntctfrm_options['cntctfrm_display_date_time'] == 1 ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>'.__('Date/Time', 'contact_form').':</td><td>'.date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ).'</td></tr>';
					else
						$user_info_string .= __('Date/Time', 'contact_form').': '.date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) )."\n";					
				}
				if ( $cntctfrm_options['cntctfrm_display_coming_from'] == 1 ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>'.__( 'Sent from (referer)', 'contact_form' ).':</td><td>'.$form_action_url.'</td></tr>';
					else
						$user_info_string .= __( 'Sent from (referer)', 'contact_form' ).': '.$form_action_url."\n";					
				}
				if ( $cntctfrm_options['cntctfrm_display_user_agent'] == 1) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>'.__( 'Using (user agent)', 'contact_form' ).':</td><td>'.$user_agent.'</td></tr>';
					else
						$user_info_string .= __( 'Using (user agent)', 'contact_form' ).': '.$user_agent."\n";						
				}
			}
			// message
			if ( 1 == $cntctfrm_options['cntctfrm_html_email'] ) {
				$message_text = '<html>
				<head>
					<title>'. __( "Contact from", 'contact_form' ) . get_bloginfo('name').'</title>
				</head>
				<body>
					<table>';
				if ( $cntctfrm_options['cntctfrm_display_name_field'] == 1 )
					$message_text .= '<tr>
							<td width="160">'. __( "Name", 'contact_form' ) . '</td><td>' . $name . '</td>
						</tr>';
				if ( $cntctfrm_options['cntctfrm_display_address_field'] == 1 )
					$message_text .= '<tr>
							<td>'. __( "Address", 'contact_form' ) . '</td><td>' . $address . '</td>
						</tr>';
				$message_text .= '<tr>	
							<td>'. __( "Email", 'contact_form' ) .'</td><td>' . $email . '</td>
						</tr>';
				if ( $cntctfrm_options['cntctfrm_display_phone_field'] == 1 )
					$message_text .= '<tr>
							<td>'. __( "Phone", 'contact_form' ) . '</td><td>' . $phone . '</td>
						</tr>';
				$message_text .= '<tr>
							<td>'. __( "Subject", 'contact_form' ) . '</td><td>' . $subject . '</td>
						</tr>
						<tr>
							<td>'. __( "Message", 'contact_form' ) . '</td><td>' . $message . '</td>
						</tr>
						<tr>
							<td>'. __( "Site", 'contact_form' ) . '</td><td>' . get_bloginfo("url") . '</td>
						</tr>
						<tr>
							<td><br /></td><td><br /></td>
						</tr>';
				$message_text_for_user = $message_text . '</table></body></html>';
				$message_text .= $user_info_string . '</table></body></html>';
			} else {
				$message_text = '';
				if ( $cntctfrm_options['cntctfrm_display_name_field'] == 1 )
					$message_text .= __( "Name", 'contact_form' ) . ': ' . $name . "\n";
				if ( $cntctfrm_options['cntctfrm_display_address_field'] == 1 )
					$message_text .= __( "Address", 'contact_form' ) . ': ' . $address . "\n";
				$message_text .= __( "Email", 'contact_form' ) .': ' . $email . "\n";
				if ( $cntctfrm_options['cntctfrm_display_phone_field'] == 1 )
					$message_text .= __( "Phone", 'contact_form' ) . ': ' . $phone . "\n";
				$message_text .= __( "Subject", 'contact_form' ) . ': ' . $subject . "\n" .
						__( "Message", 'contact_form' ) . ': ' . $message . "\n" .
						__( "Site", 'contact_form' ) . ': ' . get_bloginfo("url") . "\n"
						 . "\n";
				$message_text_for_user = $message_text;
				$message_text .= $user_info_string;
			}

			do_action( 'cntctfrm_get_mail_data', $to, $name, $email, $address, $phone, $subject, $message, $form_action_url, $user_agent, $userdomain );

			if ( $cntctfrm_options['cntctfrm_mail_method'] == 'wp-mail' ) {
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\n";
				if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
					$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
				else
					$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";

				// Additional headers
				if ( 'custom' == $cntctfrm_options['cntctfrm_from_email'] )
					$headers .= 'From: '.stripslashes( $cntctfrm_options['cntctfrm_custom_from_email'] ). '';
				else
					$headers .= 'From: '. $email . '';

				if ( $cntctfrm_options['cntctfrm_attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" ) {
					$attachments = array( $path_of_uploaded_file );					
				}

				if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $_POST['cntctfrm_contact_send_copy'] == 1 )
					wp_mail( $email, $subject, $message_text_for_user, $headers, $attachments );

				// Mail it
				$mail_result = wp_mail( $to, $subject, $message_text, $headers, $attachments );
				// delete attachment
				if ( $cntctfrm_options['cntctfrm_attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" && $cntctfrm_options['cntctfrm_delete_attached_file'] == '1' ) {
					@unlink( $path_of_uploaded_file );	
				}
				return $mail_result;
			} else {
				if ( $cntctfrm_options['cntctfrm_attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "") {
					global $path_of_uploaded_file;
					$headers  = "";
					$message_block = $message_text;

					if ( 'custom' == $cntctfrm_options['cntctfrm_select_from_field'] )
						$from_field_name = stripslashes( $cntctfrm_options['cntctfrm_from_field'] );
					else
						$from_field_name = $name;

					// Additional headers
					if ( 'custom' == $cntctfrm_options['cntctfrm_from_email'] )
						$headers .= 'From: '.$from_field_name.' <'.stripslashes( $cntctfrm_options['cntctfrm_custom_from_email'] ). '>' . "\n";
					else
						$headers .= 'From: '.$from_field_name.' <'.stripslashes( $email ). '>' . "\n";


					$bound_text = 	"jimmyP123";
		 
					$bound = 	"--".$bound_text."";

					$bound_last = 	"--".$bound_text."--";

					$headers .= "MIME-Version: 1.0\n".
						"Content-Type: multipart/mixed; boundary=\"$bound_text\"";

					$message_text = 	__( "If you can see this MIME, it means that the MIME type is not supported by your email client!", "contact_form" ) . "\n";

					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$message_text .= $bound."\n" . "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
					else
						$message_text .= $bound."\n" . "Content-Type: text/plain; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
				 
						
					$file = file_get_contents($path_of_uploaded_file);
					$message_text .= $bound."\n";

					$message_text .= "Content-Type: application/octet-stream; name=\"".basename($path_of_uploaded_file)."\"\n" .
					"Content-Description: ".basename($path_of_uploaded_file)."\n" .
					"Content-Disposition: attachment;\n" . " filename=\"".basename($path_of_uploaded_file)."\"; size=".filesize($path_of_uploaded_file).";\n" .
					"Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n";
						$message_text .= $bound_last;
				} else {
					// To send HTML mail, the Content-type header must be set
					$headers  = 'MIME-Version: 1.0' . "\n";
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
					else
						$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";


					if ( 'custom' == $cntctfrm_options['cntctfrm_select_from_field'] )
						$from_field_name = stripslashes( $cntctfrm_options['cntctfrm_from_field'] );
					else
						$from_field_name = $name;

					// Additional headers
					if( 'custom' == $cntctfrm_options['cntctfrm_from_email'] )
						$headers .= 'From: '.$from_field_name.' <'.stripslashes( $cntctfrm_options['cntctfrm_custom_from_email'] ). '>' . "\n";
					else
						$headers .= 'From: '.$from_field_name.' <'.$email. '>' . "\n";
				}
				if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $_POST['cntctfrm_contact_send_copy'] == 1 )
					@mail( $email, $subject, $message_text_for_user, $headers );

				$mail_result = @mail( $to, $subject , $message_text, $headers);
				// delete attachment
				if ( $cntctfrm_options['cntctfrm_attachment'] == 1 && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && $_FILES["cntctfrm_contact_attachment"]["tmp_name"] != "" && $cntctfrm_options['cntctfrm_delete_attached_file'] == '1' ) {
					@unlink( $path_of_uploaded_file );	
				}
				return $mail_result;
			}			
		}
		return false;
	}
}

if ( ! function_exists ( 'cntctfrm_plugin_action_links' ) ) {
	function cntctfrm_plugin_action_links( $links, $file ) {
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if ( $file == $this_plugin ){
			$settings_link = '<a href="admin.php?page=contact_form.php">' . __('Settings', 'contact_form') . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}
} // end function cntctfrm_plugin_action_links

if ( ! function_exists ( 'cntctfrm_register_plugin_links' ) ) {
	function cntctfrm_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			$links[] = '<a href="admin.php?page=contact_form.php">' . __( 'Settings','contact_form' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/plugins/contact-form-plugin/faq/" target="_blank">' . __( 'FAQ','contact_form' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support','contact_form' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists ( 'cntctfrm_clean_input' ) ) {
	function cntctfrm_clean_input( $string, $preserve_space = 0 ) {
		if ( is_string( $string ) ) {
			if ( $preserve_space ) {
				return cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ), $preserve_space );
			}
			return trim( cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ) ) );
		} else if ( is_array( $string ) ) {
			reset( $string );
			while ( list($key, $value ) = each( $string ) ) {
				$string[ $key ] = cntctfrm_clean_input($value,$preserve_space);
			}
			return $string;
		} else {
			return $string;
		}
	}
} // end function ctf_clean_input

// functions for protecting and validating form vars
if ( ! function_exists ( 'cntctfrm_sanitize_string' ) ) {
	function cntctfrm_sanitize_string( $string, $preserve_space = 0 ) {
		if( ! $preserve_space )
			$string = preg_replace("/ +/", ' ', trim( $string ) );

		return preg_replace( "/[<>]/", '_', $string );
	}
}

//Function '_plugin_init' are using to add language files.
if ( ! function_exists ( 'cntctfrm_plugin_init' ) ) {
	function cntctfrm_plugin_init() {
		if ( ! session_id() )
			@session_start();
		load_plugin_textdomain( 'contact_form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		load_plugin_textdomain( 'bestwebsoft', false, dirname( plugin_basename( __FILE__ ) ) . '/bws_menu/languages/' ); 
	}
} // end function cntctfrm_plugin_init

if ( ! function_exists ( 'cntctfrm_admin_head' ) ) {
	function cntctfrm_admin_head() {
		global $wp_version;
		if ( $wp_version < 3.8 )
			wp_enqueue_style( 'cntctfrmStylesheet', plugins_url( 'css/style_wp_before_3.8.css', __FILE__ ) );	
		else
			wp_enqueue_style( 'cntctfrmStylesheet', plugins_url( 'css/style.css', __FILE__ ) );

		if ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'contact_form.php' || $_REQUEST['page'] == 'contact_form_pro_extra.php' ) ) {
			if ( $wp_version < 3.5 ) {
				wp_enqueue_script( 'cntctfrmScript', plugins_url( 'js/script_wp_before_3.5.js', __FILE__ ) );	
			} else {
				wp_enqueue_script( 'cntctfrmprScript', plugins_url( 'js/script.js', __FILE__ ) );
			}		
			echo '<script type="text/javascript">var confirm_text = "'.__('Are you sure that you want to delete this language data?', 'contact_form').'"</script>';
		}

		if ( isset( $_GET['page'] ) && $_GET['page'] == "bws_plugins" )
			wp_enqueue_script( 'bws_menu_script', plugins_url( 'js/bws_menu.js' , __FILE__ ) );
	}
}

if ( ! function_exists ( 'cntctfrm_wp_head' ) ) {
	function cntctfrm_wp_head() {
		wp_enqueue_style( 'cntctfrmStylesheet', plugins_url( 'css/style.css', __FILE__ ) );
	}
}

if ( ! function_exists ( 'cntctfrm_email_name_filter' ) ) {
	function cntctfrm_email_name_filter( $data ){
		global $cntctfrm_options;
		if ( isset( $_POST['cntctfrm_contact_name'] ) && 'custom' != $cntctfrm_options['cntctfrm_select_from_field'] ) {
			$name = stripslashes( strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', trim( $_POST['cntctfrm_contact_name'] ) ) ) ) ); 
			if ( $name != '' )
				return $name;
			else
				return $data;

		} elseif ( isset( $cntctfrm_options['cntctfrm_from_field'] ) && trim( $cntctfrm_options['cntctfrm_from_field'] ) != "" )
			return stripslashes( $cntctfrm_options['cntctfrm_from_field'] );
		else
			return $data;
	}
}

if ( ! function_exists ( 'cntctfrm_add_language' ) ) {
	function cntctfrm_add_language(){
		$lang = strip_tags( preg_replace ( '/<[^>]*>/', '', preg_replace ( '/<script.*<\/[^>]*>/', '', htmlspecialchars( $_REQUEST['lang'] ) ) ) );
		$cntctfrm_options = get_option( 'cntctfrm_options' );
		$cntctfrm_options['cntctfrm_language'][] = $lang;
		update_option( 'cntctfrm_options', $cntctfrm_options, '', 'yes' );
		die();
	}
}

if ( ! function_exists ( 'cntctfrm_remove_language' ) ) {
	function cntctfrm_remove_language(){
		$cntctfrm_options = get_option( 'cntctfrm_options' );
		if( $key = array_search( $_REQUEST['lang'], $cntctfrm_options['cntctfrm_language'] ) !== false ) 
			$cntctfrm_options['cntctfrm_language'] = array_diff( $cntctfrm_options['cntctfrm_language'], array( $_REQUEST['lang'] ) );
		if( isset( $cntctfrm_options['cntctfrm_name_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_name_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_address_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_address_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_email_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_email_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_phone_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_phone_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_subject_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_subject_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_message_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_message_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_tooltip'][$_REQUEST['lang']] ) ) 
			unset( $cntctfrm_options['cntctfrm_attachment_tooltip'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_send_copy_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_send_copy_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_thank_text'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_thank_text'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_submit_label'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_submit_label'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_name_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_name_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_address_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_address_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_email_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_email_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_phone_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_phone_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_subject_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_subject_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_message_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_message_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_upload_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_upload_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_move_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_move_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_attachment_size_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_size_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_captcha_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_captcha_error'][$_REQUEST['lang']]);
		if( isset( $cntctfrm_options['cntctfrm_form_error'][$_REQUEST['lang']] ) )
			unset( $cntctfrm_options['cntctfrm_form_error'][$_REQUEST['lang']]);
		update_option( 'cntctfrm_options', $cntctfrm_options );
		die();
	}
}

// Function for delete options
if ( ! function_exists ( 'cntctfrm_delete_options' ) ) {
	function cntctfrm_delete_options() {
		delete_option( 'cntctfrm_options' );
		delete_site_option( 'cntctfrm_options' );
	}
}
if ( ! function_exists ( 'cntctfrm_plugin_banner' ) ) {
	function cntctfrm_plugin_banner() {
		global $hook_suffix;
		if ( $hook_suffix == 'plugins.php' ) {   
			$banner_array = array(
				array( 'pdtr_hide_banner_on_plugin_page', 'updater/updater.php', '1.12' ),
				array( 'cntctfrmtdb_hide_banner_on_plugin_page', 'contact-form-to-db/contact_form_to_db.php', '1.2' ),
				array( 'cntctfrmpr_for_ctfrmtdb_hide_banner_on_plugin_page', 'contact-form-pro/contact_form_pro.php', '1.14' ),
				array( 'cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page', 'contact-form-plugin/contact_form.php', '3.62' ),
				array( 'cntctfrm_hide_banner_on_plugin_page', 'contact-form-plugin/contact_form.php', '3.47' ),	
				array( 'cptch_hide_banner_on_plugin_page', 'captcha/captcha.php', '3.8.4' ),
				array( 'gllr_hide_banner_on_plugin_page', 'gallery-plugin/gallery-plugin.php', '3.9.1' )				
			);
			$plugin_info = get_plugin_data( __FILE__ );		
			if ( ! function_exists( 'is_plugin_active_for_network' ) )
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			$active_plugins = get_option( 'active_plugins' );
			$all_plugins = get_plugins();
			$this_banner = 'cntctfrm_hide_banner_on_plugin_page';
			$this_banner_1 = 'cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page';
			
			foreach ( $banner_array as $key => $value ) {
				if ( $this_banner == $value[0] || $this_banner_1 == $value[0] ) {
					global $wp_version;
			       	echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
			       		<script type="text/javascript" src="' . plugins_url( 'js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>
						<script type="text/javascript">		
							(function($) {
								$(document).ready( function() {		
									var hide_message = $.cookie( "cntctfrm_hide_banner_on_plugin_page" );
									var hide_message_for_ctfrmtdb = $.cookie( "cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page" );
									if ( hide_message == "true" ) {
										$( ".cntctfrm_message" ).css( "display", "none" );
										if ( hide_message_for_ctfrmtdb == "true" ) {
											$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "none" );
										};
									} else {
										$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "none" );
									}
									$( ".cntctfrm_close_icon" ).click( function() {
										$( ".cntctfrm_message" ).css( "display", "none" );
										$.cookie( "cntctfrm_hide_banner_on_plugin_page", "true", { expires: 32 } );
									});	
									$( ".cntctfrm_for_ctfrmtdb_close_icon" ).click( function() {
										$( ".cntctfrm_message_for_ctfrmtdb" ).css( "display", "none" );
										$.cookie( "cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page", "true", { expires: 32 } );
									});
								});
							})(jQuery);				
						</script>					                      
						<div class="cntctfrm_message">
							<img class="cntctfrm_close_icon" title="" src="' . plugins_url( 'images/close_banner.png', __FILE__ ) . '" alt=""/>
							<img class="cntctfrm_icon" title="" src="' . plugins_url( 'images/banner.png', __FILE__ ) . '" alt=""/>
							<div class="cntctfrm_text">
								It’s time to upgrade your <strong>Contact Form plugin</strong> to <strong>PRO</strong> version!<br />
								<span>Extend standard plugin functionality with new great options.</span>
							</div> 
							<a class="button cntctfrm_button" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-pro/?k=f575dc39cba54a9de88df346eed52101&pn=77&v=' . $plugin_info["Version"] . '&wp_v=' . $wp_version . '">Learn More</a>		
						</div>';										
						if ( !array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) && !array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) {
							echo '<div class="cntctfrm_message_for_ctfrmtdb">
								<img class="cntctfrm_for_ctfrmtdb_close_icon" title="" src="' . plugins_url( 'images/close_banner.png', __FILE__ ) . '" alt=""/>
								<img class="cntctfrm_icon" title="" src="' . plugins_url( 'images/banner_for_ctfrmtdb.png', __FILE__ ) . '" alt=""/>
								<div class="cntctfrm_text">
									<strong>Contact Form to DB</strong> allows to store your messages to the database.<br />
									<span>Manage messages that have been sent from your website.</span>
								</div> 
								<a class="button cntctfrm_button" target="_blank" href="http://bestwebsoft.com/plugin/contact-form-to-db-pro/?k=6ebf0743736411607343ad391dc3b436&pn=77&v=' . $plugin_info["Version"] . '&wp_v=' . $wp_version . '">Learn More</a>		
							</div>';
						}
					echo '</div>'; 
					break;
				}
				if ( isset( $all_plugins[ $value[1] ] ) && $all_plugins[ $value[1] ]["Version"] >= $value[2] && ( 0 < count( preg_grep( '/' . str_replace( '/', '\/', $value[1] ) . '/', $active_plugins ) ) || is_plugin_active_for_network( $value[1] ) ) && ! isset( $_COOKIE[ $value[0] ] ) ) {
					break;
				}
			}    
		}
	}
}
		
add_action( 'init', 'cntctfrm_plugin_init' );
add_action( 'init', 'cntctfrm_check_and_send' );

add_action( 'admin_menu', 'cntctfrm_admin_menu' );
add_action( 'admin_init', 'cntctfrm_version_check' );

// adds "Settings" link to the plugin action page
add_filter( 'plugin_action_links', 'cntctfrm_plugin_action_links', 10, 2 );
//Additional links on the plugin page
add_filter( 'plugin_row_meta', 'cntctfrm_register_plugin_links', 10, 2 );

add_action( 'admin_enqueue_scripts', 'cntctfrm_admin_head' );
add_action( 'wp_enqueue_scripts', 'cntctfrm_wp_head' );

add_shortcode( 'contact_form', 'cntctfrm_display_form' );
add_shortcode( 'bws_contact_form', 'cntctfrm_display_form' );
add_shortcode( 'bestwebsoft_contact_form', 'cntctfrm_display_form' );
add_filter( 'widget_text', 'do_shortcode' );

add_filter( 'wp_mail_from_name', 'cntctfrm_email_name_filter', 10, 1 );

add_action( 'wp_ajax_cntctfrm_add_language', 'cntctfrm_add_language' );
add_action( 'wp_ajax_cntctfrm_remove_language', 'cntctfrm_remove_language' );

add_action( 'admin_notices', 'cntctfrm_plugin_banner');

register_uninstall_hook( __FILE__, 'cntctfrm_delete_options' );
?>