<?php
//----------------------------------------------------------------------
//  Apocalypse Meow uninstallation
//----------------------------------------------------------------------
//remove plugin data so as not to needlessly clutter a system that is
//no longer using Apocalypse Meow
//
// @since 1.3.4



//make sure WordPress is calling this page
if (!defined('WP_UNINSTALL_PLUGIN'))
	exit ();

//remove options
foreach(array('meow_db_version','meow_protect_login','meow_fail_limit','meow_fail_window','meow_fail_reset_on_success','meow_ip_exempt','meow_apocalypse_content','meow_apocalypse_title','meow_store_ua','meow_clean_database','meow_data_expiration','meow_password_alpha','meow_password_numeric','meow_password_symbol','meow_password_length','meow_remove_generator_tag','meow_disable_editor') AS $option)
	delete_option($option);

//try to remove the table... not all db uses will have this privilege
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}meow_log`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}meow_log_banned`");



return true;
?>