<?php
/*
Plugin Name: Apocalypse Meow
Plugin URI: http://wordpress.org/extend/plugins/apocalypse-meow/
Description: A simple, light-weight collection of tools to help protect wp-admin, including password strength requirements and brute-force log-in prevention.
Version: 1.4.5
Author: Blobfolio, LLC
Author URI: http://www.blobfolio.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Copyright © 2013  Blobfolio, LLC  (email: hello@blobfolio.com)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



//----------------------------------------------------------------------
//  Constants, globals, and variable handling
//----------------------------------------------------------------------

//--------------------------------------------------
//Set up some variables
//
// @since 1.3.4
//
// @param n/a
// @return true
function meow_init_variables() {

	//if this function has already been called, exit
	if(defined('MEOW_DB'))
		return true;

	//the database version
	define('MEOW_DB', '1.3.5');

	//the program version
	define('MEOW_VERSION', '1.4.4');

	//the kitten image
	define('MEOW_IMAGE', plugins_url('kitten.gif', __FILE__));

	//password validation errors
	global $meow_password_error;
	$meow_password_error = false;

	//htaccess contents for locked-down wp-content
	define('MEOW_HTACCESS', "<FilesMatch \.(?i:php)$>\nOrder allow,deny\nDeny from all\n</FilesMatch>");

	//htaccess filename for locked-down wp-content
	define('MEOW_HTACCESS_FILE', ABSPATH . 'wp-content/.htaccess');

	return true;
}
add_action('init','meow_init_variables');


//--------------------------------------------------
//A get_option wrapper that deals with defaults and
//bad data
//
// @since 1.1.0
//
// @param $option option_name
// @return option_value or false
function meow_get_option($option){

	switch($option)
	{
		//is log-in protection enabled?
		case 'meow_protect_login':
			return (bool) get_option('meow_protect_login', true);
		//the maximum number of log-in failures allowed
		case 'meow_fail_limit':
			$tmp = (int) get_option('meow_fail_limit', 5);
			//silently correct bad entries
			if($tmp < 1)
			{
				$tmp = 5;
				update_option('meow_fail_limit', 5);
			}
			return $tmp;
		//the window in which to look for log-in failures
		case 'meow_fail_window':
			$tmp = (int) get_option('meow_fail_window', 43200);
			if($tmp < 60 || $tmp > 86400)
			{
				$tmp = 43200;
				update_option('meow_fail_window', 43200);
			}
			return $tmp;
		//whether or not a successful log-in resets the fail count
		case 'meow_fail_reset_on_success':
			return (bool) get_option('meow_fail_reset_on_success', true);
		//an array of IP addresses to ignore
		case 'meow_ip_exempt':
			return meow_sanitize_ips(get_option('meow_ip_exempt', array()));
		//a list of IPs that have been temporarily pardoned
		case 'meow_ip_pardoned':
			return meow_sanitize_ips(get_option('meow_ip_pardoned', array()));
		//the apocalypse page title
		case 'meow_apocalypse_title':
			return trim(strip_tags(get_option('meow_apocalypse_title', 'Oops...')));
		//the apocalypse page content
		case 'meow_apocalypse_content':
			return strip_tags(get_option('meow_apocalypse_content', "You have exceeded the maximum number of log-in attempts.\nSorry."));
		//whether or not to store the UA string
		case 'meow_store_ua':
			return (bool) get_option('meow_store_ua', false);
		//whether or not to remove old log-in entries from the database
		case 'meow_clean_database':
			return (bool) get_option('meow_clean_database', true);
		//how long to keep old log-in entries in the database
		case 'meow_data_expiration':
			$tmp = (int) get_option('meow_data_expiration', 90);
			if($tmp < 10)
			{
				$tmp = 90;
				update_option('meow_data_expiration', 90);
			}
			return $tmp;
		//do passwords require letters?
		case 'meow_password_alpha':
			$tmp = get_option('meow_password_alpha','required');
			if(!in_array($tmp, array('optional','required','required-both')))
			{
				$tmp = 'required';
				update_option('meow_password_alpha', 'required');
			}
			return $tmp;
		//do passwords require numbers?
		case 'meow_password_numeric':
			$tmp = get_option('meow_password_numeric', 'required');
			if(!in_array($tmp, array('optional','required')))
			{
				$tmp = 'required';
				update_option('meow_password_numeric', 'required');
			}
			return $tmp;
		//do passwords require symbols?
		case 'meow_password_symbol':
			$tmp = get_option('meow_password_symbol', 'optional');
			if(!in_array($tmp, array('optional','required')))
			{
				$tmp = 'optional';
				update_option('meow_password_symbol', 'optional');
			}
			return $tmp;
		//minimum password length
		case 'meow_password_length':
			$tmp = (int) get_option('meow_password_length', 10);
			if($tmp < 1)
			{
				$tmp = 10;
				update_option('meow_password_length', 10);
			}
			return $tmp;
		//whether or not to remove the generator tag from <head>
		case 'meow_remove_generator_tag':
			return  (bool) get_option('meow_remove_generator_tag', true);
		//are we disabling the theme/plugin editor?
		case 'meow_disable_editor':
			return (bool) get_option('meow_disable_editor', false);
	}

	return get_option($option, false);
}

//----------------------------------------------------------------------  end variables



//----------------------------------------------------------------------
//  Apocalypse Meow WP backend
//----------------------------------------------------------------------
//functions relating to the wp-admin pages, e.g. settings

//--------------------------------------------------
//Create a Settings->Apocalypse Meow menu item
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_settings_menu(){
	add_options_page('Apocalypse Meow', 'Apocalypse Meow', 'manage_options', 'meow-settings', 'meow_settings');
	return true;
}
add_action('admin_menu', 'meow_settings_menu');

//--------------------------------------------------
//Create a plugin page link to the settings too.
//
//Not sure why it took so many releases to get
//around to this...
//
// @since 1.3.0
//
// @param $links
// @return $links + settings link
function meow_plugin_settings_link($links) {
  $links[] = '<a href="' . esc_url(admin_url('options-general.php?page=meow-settings')) . '">Settings</a>';
  return $links;
}
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'meow_plugin_settings_link' );

//--------------------------------------------------
//The Settings->Apocalypse Meow page
//
// this is an external file (settings.php)
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_settings(){
	require_once(dirname(__FILE__) . '/settings.php');
	return true;
}

//--------------------------------------------------
//Create a Users->Log-in History menu item
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_history_menu(){
	$page = add_users_page('Log-in History', 'Log-in History', 'manage_options', 'meow-history', 'meow_history');
	add_action('admin_print_scripts-' . $page, 'meow_enqueue_js_tablesorter');
	add_action( 'admin_print_styles-' . $page, 'meow_enqueue_css_tablesorter' );
	return true;
}
add_action('admin_menu', 'meow_history_menu');

//--------------------------------------------------
//The Users->Log-in History page
//
// this is an external file (history.php)
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_history(){
	require_once(dirname(__FILE__) . '/history.php');
	return true;
}

//--------------------------------------------------
//Create a Users->Log-in Statistics menu item
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_statistics_menu(){
	$page = add_submenu_page(null, 'Log-in Statistics', 'Log-in Statistics', 'manage_options', 'meow-statistics', 'meow_statistics');
	add_action('admin_print_scripts-' . $page, 'meow_enqueue_js_flot');
	return true;
}
add_action('admin_menu', 'meow_statistics_menu');

//--------------------------------------------------
//The Users->Log-in Statistics page
//
// this is an external file (statistics.php)
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_statistics(){
	require_once(dirname(__FILE__) . '/statistics.php');
	return true;
}

//--------------------------------------------------
//Create a Users->Log-in Jail menu item
//
// @since 1.4.0
//
// @param n/a
// @return true
function meow_jail_menu(){
	$page = add_users_page('Log-in Jail', 'Log-in Jail', 'manage_options', 'meow-jail', 'meow_jail');
	add_action('admin_print_scripts-' . $page, 'meow_enqueue_js_tablesorter');
	add_action( 'admin_print_styles-' . $page, 'meow_enqueue_css_tablesorter' );
	return true;
}
add_action('admin_menu', 'meow_jail_menu');

//--------------------------------------------------
//The Users->Log-in Jail page
//
// this is an external file (jail.php)
//
// @since 1.4.0
//
// @param n/a
// @return true
function meow_jail(){
	require_once(dirname(__FILE__) . '/jail.php');
	return true;
}

//--------------------------------------------------
//Set up some fancy URLs
//
// add a rewrite rule for the log-in history CSV export file.
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_init_rewrite() {
	add_rewrite_rule( '^meow/login_history\.csv$', 'index.php?meow_history=true', 'top' );
	return true;
}
add_action('init','meow_init_rewrite');

//--------------------------------------------------
//Whitelist our query_vars
//
// @since 1.0.0
//
// @param $query_vars
// @return $query_vars
function meow_query_vars( $query_vars )
{
	$query_vars[] = 'meow_history';
	return $query_vars;
}
add_action('query_vars','meow_query_vars' );

//--------------------------------------------------
//Handle the fancy URLs we've set up
//
// @since 1.0.0
//
// @param $wp
// @return true or n/a
function meow_parse_request( &$wp )
{
	//create a CSV dump of log-in history
	if(array_key_exists('meow_history',$wp->query_vars))
	{
		//this requires permission
		if(!current_user_can('manage_options'))
			wp_die(__('You do not have sufficient permissions to access this file.'));

		global $wpdb;

		//set content-type headers for CSV
		header("Content-disposition: attachment; filename=login_history.csv");
		header('Content-type: text/csv');

		//throw this in an output buffer so the file downloads all at once
		ob_start();

		//CSV headers
		echo '"DATE","STATUS","USER","IP","BROWSER"';

		//pull all records from the database
		$dbResult = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}meow_log` ORDER BY `date` ASC", ARRAY_A);
		if($wpdb->num_rows > 0)
		{
			foreach($dbResult AS $Row)
			{
				echo "\n\"" . implode('","', array(
					0 => date("Y-m-d H:i:s", $Row["date"]),
					1 => (intval($Row["success"]) === 1 ? 'success' : 'failure'),
					2 => str_replace('"', '\"', $Row["username"]),
					3 => $Row["ip"],
					4 => str_replace('"', '\"', $Row["ua"])
					)) . '"';
			}
		}

		//send the buffer contents out into the world!
		echo ob_get_clean();

		exit();
	}

	return true;
}
add_action('parse_request','meow_parse_request' );

//--------------------------------------------------
//Set up permalink rules on activation
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_activate_permalink(){
	//meow_init_rewrite registers the rewrite rule(s)
	meow_init_rewrite();
	flush_rewrite_rules();
	return true;
}
register_activation_hook(__FILE__, 'meow_activate_permalink');

//--------------------------------------------------
//Remove permalink rules on de-activation
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_deactivate_permalink(){
	flush_rewrite_rules();
	return true;
}
register_deactivation_hook( __FILE__, 'meow_deactivate_permalink');

//--------------------------------------------------
//Force deactivation if multi-site is enabled
//
// @since 1.3.4
//
// @param n/a
// @return true
function meow_deactivate_multisite(){
	if(is_multisite())
	{
		require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		deactivate_plugins(__FILE__);
		echo '<div class="error fade"><p>Apocalypse Meow is not compatible with WPMU and has been disabled. Sorry!</p></div>';
	}

	return true;
}
add_action('admin_init', 'meow_deactivate_multisite');

//--------------------------------------------------
//Register jquery.tablesorter.min.js for login
//history and jail
//
// @since 1.3.3
//
// @param n/a
// @return true
function meow_register_js_tablesorter(){
	wp_register_script('meow_js_tablesorter', plugins_url('jquery.tablesorter.min.js', __FILE__),  array('jquery'), MEOW_VERSION);
	return true;
}
add_action('admin_init','meow_register_js_tablesorter');

//--------------------------------------------------
//Enqueue jquery.tablesorter.min.js for login
//history and jail
//
// @since 1.3.3
//
// @param n/a
// @return true
function meow_enqueue_js_tablesorter(){
	wp_enqueue_script('meow_js_tablesorter');
	return true;
}

//--------------------------------------------------
//Register jquery.tablesorter.css for login history
//and jail
//
// @since 1.4.0
//
// @param n/a
// @return true
function meow_register_css_tablesorter(){
	wp_register_style('meow_css_tablesorter', plugins_url('jquery.tablesorter.css', __FILE__));
	return true;
}
add_action('admin_init','meow_register_css_tablesorter');

//--------------------------------------------------
//Enqueue jquery.tablesorter.css for login history
//and jail
//
// @since 1.4.0
//
// @param n/a
// @return true
function meow_enqueue_css_tablesorter(){
	wp_enqueue_style('meow_css_tablesorter');
	return true;
}

//--------------------------------------------------
//Register jquery.flot.min.js for login statistics
//
// @since 1.3.3
//
// @param n/a
// @return true
function meow_register_js_flot(){
	wp_register_script('meow_js_flot', plugins_url('jquery.flot.min.js', __FILE__),  array('jquery'), MEOW_VERSION);
	return true;
}
add_action('admin_init','meow_register_js_flot');

//--------------------------------------------------
//Enqueue jquery.flot.min.js for login statistics
//
// @since 1.3.3
//
// @param n/a
// @return true
function meow_enqueue_js_flot(){
	wp_enqueue_script('meow_js_flot');
	return true;
}

//--------------------------------------------------
//Generate HTML header for backend pages
//
// @since 1.3.3
//
// @param n/a
// @return html
function meow_get_header(){
	$pages = array('Settings'=>'options-general.php?page=meow-settings', 'Log-in History'=>'users.php?page=meow-history', 'Log-in Jail'=>'users.php?page=meow-jail', 'Statistics'=>'users.php?page=meow-statistics');

	$xout = '<img src="' . esc_url(MEOW_IMAGE) . '" alt="kitten" style="width: 42px; float:left; margin-right: 10px; height: 42px; border: 0;" />
	<h2>Apocalypse Meow</h2>

	<h3 class="nav-tab-wrapper">
		&nbsp;';

	foreach($pages AS $title=>$link)
		$xout .= '<a href="' . esc_url(admin_url($link)) . '" class="nav-tab' . (substr_count($_SERVER['REQUEST_URI'], $link) ? ' nav-tab-active' : '') . '" title="' . $title . '">' . $title . '</a>';

	$xout .= '</h3>';

	return $xout;
}

//--------------------------------------------------
//Manually clear log-in data
//
// @since 1.3.4
//
// @param n/a
// @return n/a
function meow_purge_data(){
	//verify ajax nonce
	if(check_ajax_referer( 'm30wpurg3', 'nonce', false) && current_user_can('manage_options'))
	{
		global $wpdb;

		//delete it all!
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow_log` WHERE 1");
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow_log_banned` WHERE 1");

		echo 1;
	}
	die();
}
add_action('wp_ajax_meow_purge_data', 'meow_purge_data');

//--------------------------------------------------
//Pardon a banned IP
//
// @since 1.4.0
//
// @param n/a
// @return n/a
function meow_login_pardon(){
	//verify ajax nonce
	if(check_ajax_referer( 'm30wpardon', 'nonce', false) && current_user_can('manage_options'))
	{
		//let's take a look at the IP being submitted
		$ip = $_POST['ip'];

		if(filter_var($ip, FILTER_VALIDATE_IP))
		{
			//add it to the pardon list, if applicable
			$pardoned = meow_get_option('meow_ip_pardoned');
			if(!in_array($ip, $pardoned))
			{
				$pardoned[] = $ip;
				update_option('meow_ip_pardoned', $pardoned);
			}

			echo 1;
		}
	}
	die();
}
add_action('wp_ajax_meow_login_pardon', 'meow_login_pardon');

//----------------------------------------------------------------------  end WP backend stuff



//----------------------------------------------------------------------
//  Log-in protection
//----------------------------------------------------------------------
//functions relating to the log-in protection section

//--------------------------------------------------
//Create/update a table for log-in logging
//
// {prefix}meow_log contains the following fields:
// `id` numeric primary key
// `ip` the logee's IP address
// `date` a timestamp
// `success` whether or not the log-in happend; 1 valid, 0 failed
// `ua` the logee's browser's reported user agent
// `username` the WP account being accessed
//
// {prefix}meow_log_banned contains the following fields:
// `id` numeric primary key
// `ip` the logee's IP address
// `date` a date
// `ua` the logee's browser's reported user agent
// `count` the number of displays grouped by ip/date/ua
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_SQL(){
	global $wpdb;

	//successful and failed log-in attempts
	$sql = "CREATE TABLE {$wpdb->prefix}meow_log (
  id bigint(15) NOT NULL AUTO_INCREMENT,
  ip varchar(39) NOT NULL,
  date int(15) NOT NULL,
  success tinyint(1) NOT NULL,
  ua varchar(250) NOT NULL,
  username varchar(50) NOT NULL,
  PRIMARY KEY  (id),
  KEY ip (ip),
  KEY date (date),
  KEY success (success),
  KEY ua (ua),
  KEY username (username)
);";

	//apocalypse screen triggers
	$sql .= "CREATE TABLE {$wpdb->prefix}meow_log_banned (
  id bigint(15) NOT NULL AUTO_INCREMENT,
  ip varchar(39) NOT NULL,
  date date NOT NULL,
  ua varchar(250) NOT NULL,
  count bigint(15) DEFAULT 1 NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY user (ip,date,ua),
  KEY ip (ip),
  KEY date (date),
  KEY ua (ua)
);";


	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	//let's make sure variables have been initialized
	if(!defined('MEOW_DB'))
		meow_init_variables();

	update_option("meow_db_version", MEOW_DB);

	return true;
}
register_activation_hook(__FILE__,'meow_SQL');

//--------------------------------------------------
//Check if a database update is required
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_db_update(){
	if(get_option('meow_db_version', '0.0.0') !== MEOW_DB)
	{
		meow_SQL();
		meow_migrate_banned();
	}

	return true;
}
add_action('init', 'meow_db_update');

//--------------------------------------------------
//Migrate any logged apocalypses from meow_log to
//meow_log_banned (the original storage method
//could take up way too  much space, given how dumb
//most log-in robots are)
//
// @since 1.3.5
//
// @param n/a
// @return true
function meow_migrate_banned(){
	global $wpdb;

	$dbResult = $wpdb->get_results("SELECT `ip`, DATE(FROM_UNIXTIME(`date`)) AS `date`, `ua` FROM `{$wpdb->prefix}meow_log` WHERE `success`=-1 ORDER BY `id` ASC", ARRAY_A);
	if($wpdb->num_rows > 0)
	{
		//generate insert values
		$inserts = array();
		foreach($dbResult AS $Row)
		{
			//insert them 500 at a time
			if(count($inserts) === 500)
			{
				$wpdb->query("INSERT INTO `{$wpdb->prefix}meow_log_banned` (`ip`,`date`,`ua`) VALUES " . implode(',', $inserts) . " ON DUPLICATE KEY UPDATE `count`=`count`+1");
				$inserts = array();
			}
			$inserts[] = "('" . esc_sql($Row["ip"]) . "','" . esc_sql($Row['date']) . "','" . esc_sql($Row["ua"]) . "')";
		}
		//insert whatever's left over
		$wpdb->query("INSERT INTO `{$wpdb->prefix}meow_log_banned` (`ip`,`date`,`ua`) VALUES " . implode(',', $inserts) . " ON DUPLICATE KEY UPDATE `count`=`count`+1");
		//remove from meow_log
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow_log` WHERE `success`=-1");
	}

	return true;
}

//--------------------------------------------------
//Get and/or validate an IP address
//
// if no IP is passed, REMOTE_ADDR is used.  IP is returned so long as
// it is a valid address (and not private/reserved), otherwise false
//
// @since 1.0.0
//
// @param $ip (optional) an IP address to validate; otherwise REMOTE_ADDR
// @return string IP or false
function meow_get_IP($ip=null){
	//if not supplied, let's use REMOTE_ADDR
	if(is_null($ip))
		$ip = $_SERVER['REMOTE_ADDR'];

	//return the ip, unless it is invalid
	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ? $ip : false;
}

//--------------------------------------------------
//Sanitize an array of IPs
//
// @since 1.0.0
//
// @param $ips an array of IPs or string containing a single array
// @return array valid IPs
function meow_sanitize_ips($ips){
	//even a single IP should be stuffed into an array
	if(!is_array($ips))
		$ips = array(0=>$ips);

	//store valid IPs
	$valid = array();

	if(count($ips))
	{
		foreach($ips AS $ip)
		{
			if(false !== meow_get_IP(trim($ip)))
				$valid[] = trim($ip);
		}
	}

	if(count($valid))
	{
		sort($valid);
		return array_unique($valid);
	}
	else
		return array();
}

//--------------------------------------------------
//Determine whether or not a log-in may proceed
//
// if log-in protection is enabled, we grab various user-defined limits
// and determine whether the logee may proceed or whether he/she should
// get the Apocalypse Meow screen instead.
//
// @since 1.0.0
//
// @param n/a
// @return true or output HTML and exit
function meow_check_IP(){
	global $wpdb;

	//if log-in protection is disabled, let's leave without wasting any more time
	if(!meow_get_option('meow_protect_login'))
		return true;

	//ignore the server's IP, and anything defined by the user
	$ignore = meow_get_option('meow_ip_exempt');
	if(filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP))
		$ignore[] = $_SERVER['SERVER_ADDR'];

	//further scrutinize only if the IP address is valid
	if(false !== ($ip = meow_get_IP()) && !in_array($ip, $ignore))
	{
		//check for pardons, first!
		$meow_ip_pardoned = meow_get_option('meow_ip_pardoned');
		if(false !== ($pardon_key = array_search($ip, $meow_ip_pardoned)))
			return true;

		//user settings
		$meow_fail_limit = meow_get_option('meow_fail_limit');
		$meow_fail_window = meow_get_option('meow_fail_window');
		$meow_fail_reset_on_success = meow_get_option('meow_fail_reset_on_success');

		//if the fail count resets on success, we'll only look at failures since the last successful log-in (if any)
		//default is 0, which is fine since all log-in attempts come after the Unix epoch.  :)
		$meow_last_successful = $meow_fail_reset_on_success ? (int) $wpdb->get_var("SELECT MAX(`date`) FROM `{$wpdb->prefix}meow_log` WHERE `ip`='" . esc_sql($ip) . "' AND `success`=1") : 0;

		//if the relevant failures are too great, trigger the apocalypse
		if($meow_fail_limit <= (int) $wpdb->get_var("SELECT COUNT(*) AS `count` FROM `{$wpdb->prefix}meow_log` WHERE `ip`='" . esc_sql($ip) . "' AND `success`=0 AND UNIX_TIMESTAMP()-`date` <= $meow_fail_window AND `date` > $meow_last_successful"))
		{
			//indicate in the logs that the apocalypse screen was shown:
			$ua = meow_get_option('meow_store_ua') ? esc_sql($_SERVER['HTTP_USER_AGENT']) : '';
			$wpdb->query("INSERT INTO `{$wpdb->prefix}meow_log_banned`(`ip`,`date`,`ua`) VALUES('" . esc_sql($ip) . "',CURDATE(),'$ua') ON DUPLICATE KEY UPDATE `count`=`count`+1");

			//this is where we get off
			wp_die(nl2br(meow_get_option('meow_apocalypse_content')), meow_get_option('meow_apocalypse_title'), array('response'=>403));
		}
	}

	return true;
}
add_action('login_init','meow_check_IP');

//--------------------------------------------------
//Log log-in attempts and successes
//
// @since 1.1.0
//
// @param $status -1 apocalypse; 0 fail; 1 success
// @param $username
// @return true
function meow_login_log($status=0, $username=''){
	global $wpdb;

	//get MySQL time (as this may not always be the same as PHP)
	$time = (int) $wpdb->get_var("SELECT UNIX_TIMESTAMP()");

	//storing user agent?
	$ua = meow_get_option('meow_store_ua') ? $_SERVER['HTTP_USER_AGENT'] : '';

	//this only works if we have a valid IP
	if(false !== ($ip = meow_get_IP()))
	{
		//check for and remove pardons, if any
		$meow_ip_pardoned = meow_get_option('meow_ip_pardoned');
		if(false !== ($pardon_key = array_search($ip, $meow_ip_pardoned)))
		{
			//pardons are only good once
			unset($meow_ip_pardoned[$pardon_key]);
			update_option('meow_ip_pardoned', $meow_ip_pardoned);
		}
		$wpdb->insert("{$wpdb->prefix}meow_log", array("ip"=>$ip, "ua"=>$ua, "date"=>$time, "success"=>$status, "username"=>$username), array('%s', '%s', '%d', '%d', '%s'));
	}


	return true;
}

//--------------------------------------------------
//Wrapper for meow_login_log on failure
//
// @since 1.1.0
//
// @param n/a
// @return true
function meow_login_error(){
	return meow_login_log(0, trim(strtolower(stripslashes_deep($_REQUEST["log"]))));
}
add_action('wp_login_failed','meow_login_error');

//--------------------------------------------------
//Wrapper for meow_login_log on success
//
// @since 1.1.0
//
// @param n/a
// @return true
function meow_login_success(){
	return meow_login_log(1, trim(strtolower(stripslashes_deep($_REQUEST["log"]))));
}
add_action('wp_login', 'meow_login_success');

//--------------------------------------------------
//Database maintenance
//
// purge old log-in logs after a successful log-in.
//
// @since 1.0.0
//
// @param n/a
// @return true
function meow_clean_database(){
	global $wpdb;

	//only purge old records if database maintenance is enabled
	if(meow_get_option('meow_clean_database'))
	{
		//get MySQL time (as this may not always be the same as PHP)
		$time = (int) $wpdb->get_var("SELECT UNIX_TIMESTAMP()");
		//clear old entries
		$meow_data_expiration = meow_get_option('meow_data_expiration');
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow_log` WHERE `date` < " . strtotime("-$meow_data_expiration days", $time));
		//and clear banned entries
		$wpdb->query("DELETE FROM `{$wpdb->prefix}meow_log_banned` WHERE `date` < '" . date("Y-m-d", strtotime("-$meow_data_expiration days", $time)) . "'");
	}

	return true;
}
add_action('wp_login','meow_clean_database');

//----------------------------------------------------------------------  end log-in protection



//----------------------------------------------------------------------
//  Password restrictions
//----------------------------------------------------------------------
//functions to ensure user passwords meet certain minimum safety
//standards

//--------------------------------------------------
//A wrapper function for meow_password_rules()
//
// @since 1.0.0
//
// @param $user WP user
// @param $pass1 password
// @param $pass2 password (again)
// @return true
function meow_password_rules_check($user, &$pass1, &$pass2){ meow_password_rules($pass1, $pass2); }
add_action('check_passwords','meow_password_rules_check', 10, 3);

//--------------------------------------------------
//Enforce additional rules against user password choices
//
// based on user settings, passwords might be required to include at
// least one number, lowercase character, uppercase character, and/or
// symbol, and hit a certain overall length.
//
// @since 1.0.0
//
// @param $pass1 password
// @param $pass2 password (again)
// @return true/false
function meow_password_rules(&$pass1, &$pass2)
{
	global $meow_password_error;

	//WP can handle password mismatch or empty password errors
	if($pass1 !== $pass2 || !strlen($pass1))
		return false;

	//needs a letter
	if(meow_get_option('meow_password_alpha') === 'required' && !preg_match('/[a-z]/i', $pass1))
	{
		$meow_password_error = __('The password must contain at least one letter.');
		return false;
	}
	//needs both upper- and lowercase letters
	elseif(meow_get_option('meow_password_alpha') === 'required-both' && (!preg_match('/[a-z]/', $pass1) || !preg_match('/[A-Z]/', $pass1)))
	{
		$meow_password_error = __('The password must contain at least one uppercase and one lowercase letter.');
		return false;
	}

	//needs a number
	if(meow_get_option('meow_password_numeric') === 'required' && !preg_match('/\d/', $pass1))
	{
		$meow_password_error = __('The password must contain at least one number.');
		return false;
	}

	//needs a symbol
	if(meow_get_option('meow_password_symbol') === 'required' && !preg_match('/[^a-z0-9]/i', $pass1))
	{
		$meow_password_error = __('The password must contain at least one non-alphanumeric symbol.');
		return false;
	}

	//check password length
	$meow_password_length = meow_get_option('meow_password_length');
	if(strlen($pass1) < $meow_password_length)
	{
		$meow_password_error = __("The password must be at least $meow_password_length characters long.");
		return false;
	}

	return true;
}
add_action('password_rules','meow_password_rules', 10, 2);

//--------------------------------------------------
//Report password errors
//
// @since 1.0.0
//
// @param $errors array of errors
// @return true
function meow_password_rules_error($errors)
{
	global $meow_password_error;

	if(false !== $meow_password_error)
		$errors->add( 'pass', $meow_password_error, array( 'form-field' => 'pass1' ) );

	return true;
}
add_action('user_profile_update_errors','meow_password_rules_error', 10, 1);
add_action('password_rules_error','meow_password_rules_error', 10, 1);

//----------------------------------------------------------------------  end password restrictions



//----------------------------------------------------------------------
//  Miscellaneous security things
//----------------------------------------------------------------------
//other odds and ends that made it into this plugin

//--------------------------------------------------
//Remove "generator" <meta> tag
//
// only add the filter if specified by user
//
// @since 1.0.0
//
// @param n/a
// @return string (empty)
function meow_remove_wp_version(){ return ''; }
if(meow_get_option('meow_remove_generator_tag'))
	add_filter('the_generator', 'meow_remove_wp_version');

//--------------------------------------------------
//Determine whether .htaccess exists in wp-content
//
// @since 1.2.0
//
// @param n/a
// @return true/false
function meow_wpcontent_htaccess_exists(){
	//if the file doesn't exist, return false
	if(!file_exists(MEOW_HTACCESS_FILE))
		return false;

	//try to read the file
	if(false === ($htcontent = @file_get_contents(MEOW_HTACCESS_FILE)))
		return false;

	//finally, are the contents as expected (give or take some new lines)
	return trim(meow_newlines($htcontent)) === MEOW_HTACCESS;
}

//--------------------------------------------------
//Add .htaccess to wp-content
//
// @since 1.2.0
//
// @param n/a
// @return true/false status
function meow_add_wpcontent_htaccess(){
	//if it already exists, we don't need to be here
	if(meow_wpcontent_htaccess_exists())
		return true;

	//try to write it
	@file_put_contents(MEOW_HTACCESS_FILE, MEOW_HTACCESS);

	//if the write worked, the file should exist, right?
	return meow_wpcontent_htaccess_exists();
}

//--------------------------------------------------
//Remove .htaccess from wp-content
//
// @since 1.2.0
//
// @param n/a
// @return true/false status
function meow_remove_wpcontent_htaccess(){
	//if the file doesn't exist, we're done
	if(!meow_wpcontent_htaccess_exists())
		return true;

	//try to delete it
	@unlink(MEOW_HTACCESS_FILE);

	//if the unlink worked, the file should be gone, right?
	return !meow_wpcontent_htaccess_exists();
}

//--------------------------------------------------
//Disable plugin/theme editor
//
// @since 1.3.4
//
// @param n/a
// @return true
function meow_disable_editor(){
	if(!defined('DISALLOW_FILE_EDIT'))
		define('DISALLOW_FILE_EDIT', true);

	return true;
}
if(meow_get_option('meow_disable_editor'))
	add_action('init','meow_disable_editor');

//--------------------------------------------------
//Use Linux-standard new lines only
//
// @since 1.3.4
//
// @param string
// @return updated string
function meow_newlines($str=''){
	$str = str_replace("\r\n", "\n", $str);
	$str = str_replace("\r", "\n", $str);
	return $str;
}

//----------------------------------------------------------------------  end misc security