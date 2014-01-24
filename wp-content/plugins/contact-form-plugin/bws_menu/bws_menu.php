<?php
/*
 Function for displaying BestWebSoft menu
*/
if ( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $wpdb, $wp_version, $bws_plugin_info;
		if ( ! function_exists( 'is_plugin_active_for_network' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		$all_plugins = get_plugins();
		$error = '';
		$message = '';
		$bwsmn_form_email = '';
		$active_plugins = get_option( 'active_plugins' );

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://bestwebsoft.com/plugin/captcha-plugin/?k=d678516c0990e781edfb6a6c874f0b8a&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/captcha-plugin/?k=d678516c0990e781edfb6a6c874f0b8a&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Captcha+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=captcha.php' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://bestwebsoft.com/plugin/contact-form/?k=012327ef413e5b527883e031d43b088b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/contact-form/?k=012327ef413e5b527883e031d43b088b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Contact+Form+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=contact_form.php' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/?k=05ec4f12327f55848335802581467d55&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/?k=05ec4f12327f55848335802581467d55&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Facebook+Like+Button+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=facebook-button-plugin.php' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://bestwebsoft.com/plugin/twitter-plugin/?k=f8cb514e25bd7ec4974d64435c5eb333&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/twitter-plugin/?k=f8cb514e25bd7ec4974d64435c5eb333&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Twitter+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=twitter.php' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://bestwebsoft.com/plugin/portfolio-plugin/?k=1249a890c5b7bba6bda3f528a94f768b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/portfolio-plugin/?k=1249a890c5b7bba6bda3f528a94f768b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Portfolio+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=portfolio.php' ),
			array( 'gallery-plugin\/gallery-plugin.php', 'Gallery', 'http://bestwebsoft.com/plugin/gallery-plugin/?k=2da21c0a64eec7ebf16337fa134c5f78&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/gallery-plugin/?k=2da21c0a64eec7ebf16337fa134c5f78&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Gallery+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=gallery-plugin.php' ),
			array( 'adsense-plugin\/adsense-plugin.php', 'Google AdSense Plugin', 'http://bestwebsoft.com/plugin/google-adsense-plugin/?k=60e3979921e354feb0347e88e7d7b73d&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/google-adsense-plugin/?k=60e3979921e354feb0347e88e7d7b73d&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Adsense+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=adsense-plugin.php' ),
			array( 'custom-search-plugin\/custom-search-plugin.php', 'Custom Search Plugin', 'http://bestwebsoft.com/plugin/custom-search-plugin/?k=933be8f3a8b8719d95d1079d15443e29&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/custom-search-plugin/?k=933be8f3a8b8719d95d1079d15443e29&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Search+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_search.php' ),
			array( 'quotes-and-tips\/quotes-and-tips.php', 'Quotes and Tips', 'http://bestwebsoft.com/plugin/quotes-and-tips/?k=5738a4e85a798c4a5162240c6515098d&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/quotes-and-tips/?k=5738a4e85a798c4a5162240c6515098d&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Quotes+and+Tips+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=quotes-and-tips.php' ),
			array( 'google-sitemap-plugin\/google-sitemap-plugin.php', 'Google sitemap plugin', 'http://bestwebsoft.com/plugin/google-sitemap-plugin/?k=5202b2f5ce2cf85daee5e5f79a51d806&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/google-sitemap-plugin/?k=5202b2f5ce2cf85daee5e5f79a51d806&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Google+sitemap+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=google-sitemap-plugin.php' ),
			array( 'updater\/updater.php', 'Updater', 'http://bestwebsoft.com/plugin/updater-plugin/?k=66f3ecd4c1912009d395c4bb30f779d1&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/updater-plugin/?k=66f3ecd4c1912009d395c4bb30f779d1&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=updater+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=updater-options' ),
			array( 'custom-fields-search\/custom-fields-search.php', 'Custom Fields Search', 'http://bestwebsoft.com/plugin/custom-fields-search/?k=f3f8285bb069250c42c6ffac95ed3284&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/custom-fields-search/?k=f3f8285bb069250c42c6ffac95ed3284&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Fields+Search+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_fields_search.php' ),
			array( 'google-one\/google-plus-one.php', 'Google +1', 'http://bestwebsoft.com/plugin/google-plus-one/?k=ce7a88837f0a857b3a2bb142f470853c&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/google-plus-one/?k=ce7a88837f0a857b3a2bb142f470853c&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&type=term&s=Google+%2B1+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=google-plus-one.php' ),
			array( 'relevant\/related-posts-plugin.php', 'Related Posts Plugin', 'http://bestwebsoft.com/plugin/related-posts-plugin/?k=73fb737037f7141e66415ec259f7e426&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/related-posts-plugin/?k=73fb737037f7141e66415ec259f7e426&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&s=Related+Posts+Plugin+Bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=related-posts-plugin.php' ),
			array( 'contact-form-to-db\/contact_form_to_db.php', 'Contact Form to DB', 'http://bestwebsoft.com/plugin/contact-form-to-db/?k=ba3747d317c2692e4136ca096a8989d6&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/contact-form-to-db/?k=ba3747d317c2692e4136ca096a8989d6&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&s=Contact+Form+to+DB+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=cntctfrmtdb_settings' ),
			array( 'pdf-print\/pdf-print.php', 'PDF & Print', 'http://bestwebsoft.com/plugin/pdf-print/?k=bfefdfb522a4c0ff0141daa3f271840c&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/pdf-print/?k=bfefdfb522a4c0ff0141daa3f271840c&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&s=PDF+Print+Bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=pdf-print.php' ),
			array( 'donate-button\/donate.php', 'Donate', 'http://bestwebsoft.com/plugin/donate/?k=a8b2e2a56914fb1765dd20297c26401b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/donate/?k=a8b2e2a56914fb1765dd20297c26401b&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/plugin-install.php?tab=search&s=Donate+Bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=donate.php' )
		);
		foreach ( $array_plugins as $plugins ) {
			if ( 0 < count( preg_grep( "/" . $plugins[0] . "/", $active_plugins ) ) || is_plugin_active_for_network( str_replace( '\\', '', $plugins[0] ) ) ) {
				$array_activate[ $count_activate ]["title"]		= $plugins[1];
				$array_activate[ $count_activate ]["link"]		= $plugins[2];
				$array_activate[ $count_activate ]["href"]		= $plugins[3];
				$array_activate[ $count_activate ]["url"]		= $plugins[5];
				$count_activate++;
			} else if ( array_key_exists( str_replace( "\\", "", $plugins[0] ), $all_plugins ) ) {
				$array_install[ $count_install ]["title"]	= $plugins[1];
				$array_install[ $count_install ]["link"]	= $plugins[2];
				$array_install[ $count_install ]["href"]	= $plugins[3];
				$count_install++;
			} else {
				$array_recomend[ $count_recomend ]["title"] = $plugins[1];
				$array_recomend[ $count_recomend ]["link"]	= $plugins[2];
				$array_recomend[ $count_recomend ]["href"]	= $plugins[3];
				$array_recomend[ $count_recomend ]["slug"]	= $plugins[4];
				$count_recomend++;
			}
		}

		$array_activate_pro = array();
		$array_install_pro	= array();
		$array_recomend_pro = array();
		$count_activate_pro = $count_install_pro = $count_recomend_pro = 0;
		$array_plugins_pro	= array(
			array( 'gallery-plugin-pro\/gallery-plugin-pro.php', 'Gallery Pro', 'http://bestwebsoft.com/plugin/gallery-pro/?k=382e5ce7c96a6391f5ffa5e116b37fe0&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/gallery-pro/?k=382e5ce7c96a6391f5ffa5e116b37fe0&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#purchase', 'admin.php?page=gallery-plugin-pro.php' ),
			array( 'contact-form-pro\/contact_form_pro.php', 'Contact Form Pro', 'http://bestwebsoft.com/plugin/contact-form-pro/?k=773dc97bb3551975db0e32edca1a6d71&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/contact-form-pro/?k=773dc97bb3551975db0e32edca1a6d71&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#purchase', 'admin.php?page=contact_form_pro.php' ),
			array( 'captcha-pro\/captcha_pro.php', 'Captcha Pro', 'http://bestwebsoft.com/plugin/captcha-pro/?k=ff7d65e55e5e7f98f219be9ed711094e&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/captcha-pro/?k=ff7d65e55e5e7f98f219be9ed711094e&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#purchase', 'admin.php?page=captcha_pro.php' ),
			array( 'updater-pro\/updater_pro.php', 'Updater Pro', 'http://bestwebsoft.com/plugin/updater-pro/?k=cf633acbefbdff78545347fe08a3aecb&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/updater-pro?k=cf633acbefbdff78545347fe08a3aecb&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#purchase', 'admin.php?page=updater-pro-options' ),
			array( 'contact-form-to-db-pro\/contact_form_to_db_pro.php', 'Contact Form to DB Pro', 'http://bestwebsoft.com/plugin/contact-form-to-db-pro/?k=6ce5f4a9006ec906e4db643669246c6a&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/plugin/contact-form-to-db-pro/?k=6ce5f4a9006ec906e4db643669246c6a&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#purchase', 'admin.php?page=cntctfrmtdbpr_settings' )
		);
		foreach ( $array_plugins_pro as $plugins ) {
			if ( 0 < count( preg_grep( "/" . $plugins[0] . "/", $active_plugins ) ) || is_plugin_active_for_network( str_replace( '\\', '', $plugins[0] ) ) ) {
				$array_activate_pro[ $count_activate_pro ]["title"] = $plugins[1];
				$array_activate_pro[ $count_activate_pro ]["link"]	= $plugins[2];
				$array_activate_pro[ $count_activate_pro ]["href"]	= $plugins[3];
				$array_activate_pro[ $count_activate_pro ]["url"]	= $plugins[4];
				$count_activate_pro++;
			} else if ( array_key_exists( str_replace( "\\", "", $plugins[0]), $all_plugins ) ) {
				$array_install_pro[ $count_install_pro ]["title"]	= $plugins[1];
				$array_install_pro[ $count_install_pro ]["link"]	= $plugins[2];
				$array_install_pro[ $count_install_pro ]["href"]	= $plugins[3];
				$count_install_pro++;
			} else {
				$array_recomend_pro[ $count_recomend_pro ]["title"] = $plugins[1];
				$array_recomend_pro[ $count_recomend_pro ]["link"]	= $plugins[2];
				$array_recomend_pro[ $count_recomend_pro ]["href"]	= $plugins[3];
				$count_recomend_pro++;
			}
		}
		if ( $wp_version >= '3.4' ) {
			$wp_list_table = _get_list_table( 'WP_Themes_List_Table'  );
			$wp_list_table->prepare_items();
			$current_theme = wp_get_theme();
			$array_activate_theme = array();
			$array_install_theme = array();
			$array_recomend_theme = array();
			$count_activate_theme = $count_install_theme = $count_recomend_theme = 0;
			$array_theme = array(
				array( 'central', 'Central', 'http://bestwebsoft.com/theme/central/?k=77c0199aabdb1f601a0504e312bee220&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/theme/central/?k=77c0199aabdb1f601a0504e312bee220&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/theme-install.php?tab=search&s=Central&search=Search' ),
				array( 'simple-classic', 'Simple Classic', 'http://bestwebsoft.com/theme/simple-classic/?k=b3990bfc85125747f48ece9f011f4cde&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/theme/simple-classic/?k=b3990bfc85125747f48ece9f011f4cde&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/theme-install.php?tab=search&type=term&s=Simple+Classic&search=Search' ),
				array( 'reddish', 'Reddish', 'http://bestwebsoft.com/theme/reddish/?k=1ea187e3fd401fd278e23a333abaf4f6&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/theme/reddish/?k=1ea187e3fd401fd278e23a333abaf4f6&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/theme-install.php?tab=search&type=term&s=reddish&search=Search' ),
				array( 'wordpost', 'Wordpost', 'http://bestwebsoft.com/theme/wordpost/?k=f0fc8c98135c9657751224562aca7a55&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version, 'http://bestwebsoft.com/theme/wordpost/?k=f0fc8c98135c9657751224562aca7a55&pn=' . $bws_plugin_info["id"] . '&v=' . $bws_plugin_info["version"] . '&wp_v=' . $wp_version . '#download', '/wp-admin/theme-install.php?tab=search&type=term&s=Wordpost&search=Search' )
			);
			foreach ( $array_theme as $theme ) {
				if ( $current_theme->get( 'Name' ) == $theme[1] ) {
					$array_activate_theme[ $count_activate_theme ]["title"] = $theme[1];
					$array_activate_theme[ $count_activate_theme ]["link"]	= $theme[2];
					$array_activate_theme[ $count_activate_theme ]["href"]	= $theme[3];
					$count_activate_theme++;
				} elseif ( array_key_exists( $theme[0], $wp_list_table->items ) ) {
					$array_install_theme[ $count_install_theme ]["title"]	= $theme[1];
					$array_install_theme[ $count_install_theme ]["link"]	= $theme[2];
					$array_install_theme[ $count_install_theme ]["href"]	= $theme[3];
					$count_install_theme++;
				} else {
					$array_recomend_theme[ $count_recomend_theme ]["title"] = $theme[1];
					$array_recomend_theme[ $count_recomend_theme ]["link"]	= $theme[2];
					$array_recomend_theme[ $count_recomend_theme ]["href"]	= $theme[3];
					$array_recomend_theme[ $count_recomend_theme ]["slug"]	= $theme[4];
					$count_recomend_theme++;
				}
			}
		}

		$sql_version = $wpdb->get_var( "SELECT VERSION() AS version" );
	    $mysql_info = $wpdb->get_results( "SHOW VARIABLES LIKE 'sql_mode'" );
	    if ( is_array( $mysql_info) )
	    	$sql_mode = $mysql_info[0]->Value;
	    if ( empty( $sql_mode ) )
	    	$sql_mode = __( 'Not set', 'bestwebsoft' );
	    if ( ini_get( 'safe_mode' ) )
	    	$safe_mode = __( 'On', 'bestwebsoft' );
	    else
	    	$safe_mode = __( 'Off', 'bestwebsoft' );
	    if ( ini_get( 'allow_url_fopen' ) )
	    	$allow_url_fopen = __( 'On', 'bestwebsoft' );
	    else
	    	$allow_url_fopen = __( 'Off', 'bestwebsoft' );
	    if ( ini_get( 'upload_max_filesize' ) )
	    	$upload_max_filesize = ini_get( 'upload_max_filesize' );
	    else
	    	$upload_max_filesize = __( 'N/A', 'bestwebsoft' );
	    if ( ini_get('post_max_size') )
	    	$post_max_size = ini_get('post_max_size');
	    else
	    	$post_max_size = __( 'N/A', 'bestwebsoft' );
	    if ( ini_get( 'max_execution_time' ) )
	    	$max_execution_time = ini_get( 'max_execution_time' );
	    else
	    	$max_execution_time = __( 'N/A', 'bestwebsoft' );
	    if ( ini_get( 'memory_limit' ) )
	    	$memory_limit = ini_get( 'memory_limit' );
	    else
	    	$memory_limit = __( 'N/A', 'bestwebsoft' );
	    if ( function_exists( 'memory_get_usage' ) )
	    	$memory_usage = round( memory_get_usage() / 1024 / 1024, 2 ) . __( ' Mb', 'bestwebsoft' );
	    else
	    	$memory_usage = __( 'N/A', 'bestwebsoft' );
	    if ( is_callable( 'exif_read_data' ) )
	    	$exif_read_data = __( 'Yes', 'bestwebsoft' ) . " ( V" . substr( phpversion( 'exif' ), 0,4 ) . ")" ;
	    else
	    	$exif_read_data = __( 'No', 'bestwebsoft' );
	    if ( is_callable( 'iptcparse' ) )
	    	$iptcparse = __( 'Yes', 'bestwebsoft' );
	    else
	    	$iptcparse = __( 'No', 'bestwebsoft' );
	    if ( is_callable( 'xml_parser_create' ) )
	    	$xml_parser_create = __( 'Yes', 'bestwebsoft' );
	    else
	    	$xml_parser_create = __( 'No', 'bestwebsoft' );

		if ( function_exists( 'wp_get_theme' ) )
			$theme = wp_get_theme();
		else
			$theme = get_theme( get_current_theme() );

		if ( function_exists( 'is_multisite' ) ) {
			if ( is_multisite() ) {
				$multisite = __( 'Yes', 'bestwebsoft' );
			} else {
				$multisite = __( 'No', 'bestwebsoft' );
			}
		} else
			$multisite = __( 'N/A', 'bestwebsoft' );

		$site_url = get_option( 'siteurl' );
		$home_url = get_option( 'home' );
		$db_version = get_option( 'db_version' );
		$system_info = array(
			'system_info'		=> '',
			'active_plugins'	=> '',
			'inactive_plugins'	=> ''
		);
		$system_info['system_info'] = array(
	        __( 'Operating System', 'bestwebsoft' )				=> PHP_OS,
	        __( 'Server', 'bestwebsoft' )						=> $_SERVER["SERVER_SOFTWARE"],
	        __( 'Memory usage', 'bestwebsoft' )					=> $memory_usage,
	        __( 'MYSQL Version', 'bestwebsoft' )				=> $sql_version,
	        __( 'SQL Mode', 'bestwebsoft' )						=> $sql_mode,
	        __( 'PHP Version', 'bestwebsoft' )					=> PHP_VERSION,
	        __( 'PHP Safe Mode', 'bestwebsoft' )				=> $safe_mode,
	        __( 'PHP Allow URL fopen', 'bestwebsoft' )			=> $allow_url_fopen,
	        __( 'PHP Memory Limit', 'bestwebsoft' )				=> $memory_limit,
	        __( 'PHP Max Upload Size', 'bestwebsoft' )			=> $upload_max_filesize,
	        __( 'PHP Max Post Size', 'bestwebsoft' )			=> $post_max_size,
	        __( 'PHP Max Script Execute Time', 'bestwebsoft' )	=> $max_execution_time,
	        __( 'PHP Exif support', 'bestwebsoft' )				=> $exif_read_data,
	        __( 'PHP IPTC support', 'bestwebsoft' )				=> $iptcparse,
	        __( 'PHP XML support', 'bestwebsoft' )				=> $xml_parser_create,
			__( 'Site URL', 'bestwebsoft' )						=> $site_url,
			__( 'Home URL', 'bestwebsoft' )						=> $home_url,
			__( 'WordPress Version', 'bestwebsoft' )			=> $wp_version,
			__( 'WordPress DB Version', 'bestwebsoft' )			=> $db_version,
			__( 'Multisite', 'bestwebsoft' )					=> $multisite,
			__( 'Active Theme', 'bestwebsoft' )					=> $theme['Name'] . ' ' . $theme['Version']
		);
		foreach ( $all_plugins as $path => $plugin ) {
			if ( is_plugin_active( $path ) ) {
				$system_info['active_plugins'][ $plugin['Name'] ] = $plugin['Version'];
			} else {
				$system_info['inactive_plugins'][ $plugin['Name'] ] = $plugin['Version'];
			}
		} 

		if ( ( isset( $_REQUEST['bwsmn_form_submit'] ) && check_admin_referer( plugin_basename(__FILE__), 'bwsmn_nonce_submit' ) ) ||
			 ( isset( $_REQUEST['bwsmn_form_submit_custom_email'] ) && check_admin_referer( plugin_basename(__FILE__), 'bwsmn_nonce_submit_custom_email' ) ) ) {
			if ( isset( $_REQUEST['bwsmn_form_email'] ) ) {
				$bwsmn_form_email = trim( $_REQUEST['bwsmn_form_email'] );
				if ( $bwsmn_form_email == "" || !preg_match( "/^((?:[a-z0-9']+(?:[a-z0-9\-_\.']+)?@[a-z0-9]+(?:[a-z0-9\-\.]+)?\.[a-z]{2,5})[, ]*)+$/i", $bwsmn_form_email ) ) {
					$error = __( "Please enter a valid email address.", 'bestwebsoft' );
				} else {
					$email = $bwsmn_form_email;
					$bwsmn_form_email = '';
					$message = __( 'Email with system info is sent to ', 'bestwebsoft' ) . $email;			
				}
			} else {
				$email = 'plugin_system_status@bestwebsoft.com';
				$message = __( 'Thank you for contacting us.', 'bestwebsoft' );
			}

			if ( $error == '' ) {
				$headers  = 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
				$headers .= 'From: ' . get_option( 'admin_email' );
				$message_text = '<html><head><title>System Info From ' . $home_url . '</title></head><body>
				<h4>Environment</h4>
				<table>';
				foreach ( $system_info['system_info'] as $key => $value ) {
					$message_text .= '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';	
				}
				$message_text .= '</table>
				<h4>Active Plugins</h4>
				<table>';
				foreach ( $system_info['active_plugins'] as $key => $value ) {	
					$message_text .= '<tr><td scope="row">'. $key .'</td><td scope="row">'. $value .'</td></tr>';	
				}
				$message_text .= '</table>
				<h4>Inactive Plugins</h4>
				<table>';
				foreach ( $system_info['inactive_plugins'] as $key => $value ) {
					$message_text .= '<tr><td scope="row">'. $key .'</td><td scope="row">'. $value .'</td></tr>';
				}
				$message_text .= '</table></body></html>';
				$result = wp_mail( $email, 'System Info From ' . $home_url, $message_text, $headers );
				if ( $result != true )
					$error = __( "Sorry, email message could not be delivered.", 'bestwebsoft' );
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2>BestWebSoft</h2>
			<div class="updated fade" <?php if ( ! ( isset( $_REQUEST['bwsmn_form_submit'] ) || isset( $_REQUEST['bwsmn_form_submit_custom_email'] ) ) || $error != "" ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div class="error" <?php if ( "" == $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<h3 style="color: blue;"><?php _e( 'Pro plugins', 'bestwebsoft' ); ?></h3>
			<?php if ( 0 < $count_activate_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Activated plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_activate_pro as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin["title"]; ?></div> <p><a href="<?php echo $activate_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo $activate_plugin["url"]; ?>"><?php echo __( "Settings", 'bestwebsoft' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ( 0 < $count_install_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Installed plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_install_pro as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin["title"]; ?></div> <p><a href="<?php echo $install_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ( 0 < $count_recomend_pro ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Recommended plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_recomend_pro as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin["title"]; ?></div> <p><a href="<?php echo $recomend_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo $recomend_plugin["href"]; ?>" target="_blank"><?php echo __( "Purchase", 'bestwebsoft' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<br />
			<h3 style="color: green"><?php _e( 'Free plugins', 'bestwebsoft' ); ?></h3>
			<?php if ( 0 < $count_activate ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Activated plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin["title"]; ?></div> <p><a href="<?php echo $activate_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo $activate_plugin["url"]; ?>"><?php echo __( "Settings", 'bestwebsoft' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ( 0 < $count_install ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Installed plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_install as $install_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin["title"]; ?></div> <p><a href="<?php echo $install_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ( 0 < $count_recomend ) { ?>
			<div style="padding-left:15px;">
				<h4><?php _e( 'Recommended plugins', 'bestwebsoft' ); ?></h4>
				<?php foreach ( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin["title"]; ?></div> <p><a href="<?php echo $recomend_plugin["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo $recomend_plugin["href"]; ?>" target="_blank"><?php echo __( "Download", 'bestwebsoft' ); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin["slug"]; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin["title"] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'bestwebsoft' ) ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>	
			<br />
			<?php if ( $wp_version >= '3.4' ) { ?>	
				<h3 style="color: green"><?php _e( 'Free themes', 'bestwebsoft' ); ?></h3>
				<?php if ( 0 < $count_activate_theme ) { ?>
				<div style="padding-left:15px;">
					<h4><?php _e( 'Activated theme', 'bestwebsoft' ); ?></h4>
					<div style="float:left; width:200px;"><?php echo $array_activate_theme[0]["title"]; ?></div> <p><a href="<?php echo $array_activate_theme[0]["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo wp_customize_url(); ?>" title="<?php echo esc_attr( sprintf( __( 'Customize &#8220;%s&#8221;' ), $current_theme->display('Name') ) ); ?>"><?php _e( 'Customize' ); ?></a></p>
				</div>
				<?php } ?>
				<?php if ( 0 < $count_install_theme ) { ?>
				<div style="padding-left:15px;">
					<h4><?php _e( 'Installed themes', 'bestwebsoft' ); ?></h4>
					<?php foreach ( $array_install_theme as $install_theme ) { ?>
					<div style="float:left; width:200px;"><?php echo $install_theme["title"]; ?></div> <p><a href="<?php echo $install_theme["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a></p>
					<?php } ?>
				</div>
				<?php } ?>
				<?php if ( 0 < $count_recomend_theme ) { ?>
				<div style="padding-left:15px;">
					<h4><?php _e( 'Recommended themes', 'bestwebsoft' ); ?></h4>
					<?php foreach ( $array_recomend_theme as $recomend_theme ) { ?>
					<div style="float:left; width:200px;"><?php echo $recomend_theme["title"]; ?></div> <p><a href="<?php echo $recomend_theme["link"]; ?>" target="_blank"><?php echo __( "Read more", 'bestwebsoft' ); ?></a> <a href="<?php echo $recomend_theme["href"]; ?>" target="_blank"><?php echo __( "Download", 'bestwebsoft' ); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_theme["slug"]; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_theme["title"] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'bestwebsoft' ) ?></a></p>
					<?php } ?>
				</div>
				<?php } ?>	
				<br />
			<?php } ?>	
			<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via', 'bestwebsoft' ); ?> <a href="http://support.bestwebsoft.com">http://support.bestwebsoft.com</a></span>
			<div id="poststuff" class="bws_system_info_meta_box">
				<div class="postbox">
					<div class="handlediv" title="Click to toggle">
						<br>
					</div>
					<h3 class="hndle">
						<span><?php _e( 'System status', 'bestwebsoft' ); ?></span>
					</h3>
					<div class="inside">
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Environment', 'bestwebsoft' ); ?></th><td></td></tr></thead>
							<tbody>
							<?php foreach ( $system_info['system_info'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Active Plugins', 'bestwebsoft' ); ?></th><th></th></tr></thead>
							<tbody>
							<?php foreach ( $system_info['active_plugins'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<table class="bws_system_info">
							<thead><tr><th><?php _e( 'Inactive Plugins', 'bestwebsoft' ); ?></th><th></th></tr></thead>
							<tbody>
							<?php foreach ( $system_info['inactive_plugins'] as $key => $value ) { ?>	
								<tr>
									<td scope="row"><?php echo $key; ?></td>
									<td scope="row"><?php echo $value; ?></td>
								</tr>	
							<?php } ?>
							</tbody>
						</table>
						<div class="clear"></div>						
						<form method="post" action="admin.php?page=bws_plugins">
							<p>			
								<input type="hidden" name="bwsmn_form_submit" value="submit" />
								<input type="submit" class="button-primary" value="<?php _e( 'Send to support', 'bestwebsoft' ) ?>" />
								<?php wp_nonce_field( plugin_basename(__FILE__), 'bwsmn_nonce_submit' ); ?>		
							</p>		
						</form>				
						<form method="post" action="admin.php?page=bws_plugins">	
							<p>			
								<input type="hidden" name="bwsmn_form_submit_custom_email" value="submit" />						
								<input type="submit" class="button" value="<?php _e( 'Send to custom email &#187;', 'bestwebsoft' ) ?>" />
								<input type="text" value="<?php echo $bwsmn_form_email; ?>" name="bwsmn_form_email" />
								<?php wp_nonce_field( plugin_basename(__FILE__), 'bwsmn_nonce_submit_custom_email' ); ?>
							</p>				
						</form>						
					</div>
				</div>
			</div>			
		</div>
	<?php }
} ?>