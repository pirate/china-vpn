<?php
//----------------------------------------------------------------------
//  Apocalypse Meow settings
//----------------------------------------------------------------------
//display a form so authorized WP users can configure Apocalypse Meow
//and save the settings
//
// @since 1.0.0



//--------------------------------------------------
//Check permissions

//let's make sure this page is being accessed through WP
if (!function_exists('current_user_can'))
	die('Sorry');
//and let's make sure the current user has sufficient permissions
elseif(!current_user_can('manage_options'))
	wp_die(__('You do not have sufficient permissions to access this page.'));



//we'll need this later
$meowdata = array();
global $wpdb;



//--------------------------------------------------
//Process submitted data!

if(getenv("REQUEST_METHOD") === 'POST')
{
	//AAAAAARRRRGH DIE MAGIC QUOTES!!!!  Haha.
	$_POST = stripslashes_deep($_POST);

	//validate form data...
	$meowdata['meow_protect_login'] = intval($_POST['meow_protect_login']) === 1;
	$meowdata['meow_fail_limit'] = (int) $_POST['meow_fail_limit'];
		//silently correct invalid choice
		if($meowdata['meow_fail_limit'] < 1)
			$meow['meow_fail_limit'] = 5;
	$meowdata['meow_fail_window'] = 60 * intval($_POST['meow_fail_window']);
		//silently correct invalid choice
		if($meowdata['meow_fail_window'] < 60)
			$meow['meow_fail_window'] = 43200;
		elseif($meowdata['meow_fail_window'] > 86400)
			$meow['meow_fail_window'] = 43200;
	$meowdata['meow_fail_reset_on_success'] = intval($_POST['meow_fail_reset_on_success']) === 1;
	$meowdata['meow_ip_exempt'] = meow_sanitize_ips(explode("\n", meow_newlines($_POST['meow_ip_exempt'])));
	$meowdata['meow_apocalypse_title'] = trim(strip_tags($_POST["meow_apocalypse_title"]));
	$meowdata['meow_apocalypse_content'] = trim(strip_tags($_POST['meow_apocalypse_content']));
	$meowdata['meow_clean_database'] = intval($_POST['meow_clean_database']) === 1;
	$meowdata['meow_data_expiration'] = (int) $_POST['meow_data_expiration'];
		//silently correct bad data
		if($meowdata['meow_data_expiration'] < 10)
			$meowdata['meow_data_expiration'] = 90;
	$meowdata['meow_store_ua'] = intval($_POST['meow_store_ua']) === 1;

	$meowdata['meow_password_alpha'] = in_array($_POST['meow_password_alpha'], array('optional','required','required-both')) ? $_POST['meow_password_alpha'] : 'optional';
	$meowdata['meow_password_numeric'] = in_array($_POST['meow_password_numeric'], array('optional','required')) ? $_POST['meow_password_numeric'] : 'optional';
	$meowdata['meow_password_symbol'] = in_array($_POST['meow_password_symbol'], array('optional','required')) ? $_POST['meow_password_symbol'] : 'optional';
	$meowdata['meow_password_length'] = (double) $_POST['meow_password_length'];
		//silently correct bad data
		if($meowdata['meow_password_length'] < 1)
			$meowdata['meow_password_length'] = 5;

	$meowdata['meow_remove_generator_tag'] = intval($_POST['meow_remove_generator_tag']) === 1;

	$meowdata['meow_disable_editor'] = intval($_POST['meow_disable_editor']) === 1;

	//bad nonce, don't save
	if(!wp_verify_nonce($_POST['_wpnonce'],'meow-settings'))
		echo '<div class="error fade"><p>Sorry the form had expired.  Please try again.</p></div>';
	else
	{
		//update settings!
		$changed = 0;
		foreach($meowdata AS $k=>$v)
		{
			if(true === update_option($k, $v))
				$changed++;
		}
		if($changed > 0)
			echo '<div class="updated fade"><p>Apocalypse Meow\'s settings have been successfully updated.</p></div>';

		//enable wp-content htaccess (only if it doesn't already exist)
		if(intval($_POST["meow_wpcontent_htaccess"]) === 1 && !meow_wpcontent_htaccess_exists())
		{
			if(false === meow_add_wpcontent_htaccess())
				echo '<div class="error fade"><p>WordPress could not automatically create <code>' . esc_html(MEOW_HTACCESS_FILE) . '</code>, the file containing the rules to prevent direct PHP script execution.  You\'ll have to roll up your sleeves and do it manually. Simply copy the following code into a text file named &quot;.htaccess&quot; and upload it to your wp-content/ directory:</p><p><code>' . nl2br(esc_html(MEOW_HTACCESS)) . '</code></p></div>';
			else
				echo '<div class="updated fade"><p>The file containing rules to prevent the direct execution of PHP scripts (<code>' . esc_html(MEOW_HTACCESS_FILE) . '</code>) has been successfully created!  Before grabbing yourself a celebratory beer:</p><ol><li>Try accessing the Apocalypse Meow settings page directly (you should get a 403 Forbidden error): <a href="' . esc_url(plugins_url('settings.php', __FILE__)) . '" target="_blank">' . plugins_url('settings.php', __FILE__) . '</a>  If instead you see &quot;Sorry&quot;, then your server is not recognizing the restriction (sorry!)</li><li>Take a thorough walkthrough of both the front- and backend of your site and make sure things still work as expected. If any plugins are caught by this trap, you\'ll need to replace them with better alternatives or live without this security lockdown.</li><li>That\'s it! Congratulations! :)</li></ol></div>';
		}
		//disable wp-content htaccess (only if it presently exists)
		elseif(intval($_POST["meow_wpcontent_htaccess"]) !== 1 && meow_wpcontent_htaccess_exists())
		{
			if(false === meow_remove_wpcontent_htaccess())
				echo '<div class="error fade"><p>WordPress was unable to delete <code>' . esc_html(MEOW_HTACCESS_FILE) . '</code>, the file containing the rules to prevent direct PHP script execution. Please manually delete this file.</div>';
			else
				echo '<div class="updated fade"><p>The rules preventing the direct execution of PHP scripts have been lifted.</p>';
		}

		//are we changing the admin username?
		if(username_exists('admin') && 'admin' !== ($tmp = trim(strtolower($_POST['meow_admin_user']))))
		{
			//new username is already in use
			if(username_exists($tmp))
				echo '<div class="error fade"><p>The username &quot;' . esc_html($tmp) . '&quot; already exists; you\'ll have to come up with something else.</p></div>';
			//new username is invalidly formatted
			elseif(!validate_username($tmp))
				echo '<div class="error fade"><p>The username &quot;' . esc_html($tmp) . '&quot; is not valid; try again.</p></div>';
			//let's save it!
			else
			{
				$current_user = wp_get_current_user();
				echo '<div class="updated fade"><p>Congratulations, the old &quot;admin&quot; user has been successfully changed to &quot;' . esc_html($tmp) . '&quot;.' . ($current_user->user_login === 'admin' ? ' Unfortunately you were logged in as that now nonexistent user, so you\'ll have to take a moment to <a href="' . esc_url(admin_url('options-general.php?page=meow-settings')) .  '">re-login</a> (as &quot;' . esc_html($tmp) . '&quot; this time).  :)' : '') . '</p></div>';
				$wpdb->update($wpdb->users, array('user_login'=>$tmp), array('user_login'=>'admin'), array('%s'), array('%s'));
				if($current_user->user_login === 'admin')
					die();
				$meow_changed_admin = true;
			}
		}
	}
}

//--------------------------------------------------
//Grab saved or default settings
else
{
	$options = array('meow_protect_login','meow_fail_limit','meow_fail_window','meow_fail_reset_on_success','meow_ip_exempt','meow_apocalypse_content','meow_apocalypse_title','meow_store_ua','meow_clean_database','meow_data_expiration','meow_password_alpha','meow_password_numeric','meow_password_symbol','meow_password_length','meow_remove_generator_tag','meow_disable_editor');
	foreach($options AS $option)
		$meowdata[$option] = meow_get_option($option);
}

//--------------------------------------------------
//Output the form!
?>
<style type="text/css">
	.form-table {
		clear: left!important;
	}
	.meow-hidden {
		display: none;
	}
</style>

<div class="wrap">

	<?php echo meow_get_header(); ?>

	<div class="metabox-holder has-right-sidebar">

		<form id="form-meow-settings" method="post" action="<?php echo esc_url(admin_url('options-general.php?page=meow-settings')); ?>">
		<?php wp_nonce_field('meow-settings'); ?>

		<div class="inner-sidebar">
			<!--start generator meta tag -->
			<div class="postbox">
				<h3 class="hndle">Remove the &quot;generator&quot; meta tag</h3>
				<div class="inside">
					<p>Most templates include the current WordPress version in the HTML &lt;head&gt;. While this information is largely innocuous (and discoverable elsewhere), it can help nogoodniks better target attacks against your site, particularly if you are running an out-of-date version of WordPress.</p>
					<p><label for="meow_remove_generator_tag">
						<input type="checkbox" name="meow_remove_generator_tag" id="meow_remove_generator_tag" value="1" <?php echo ($meowdata['meow_remove_generator_tag'] === true ? 'checked=checked' : ''); ?> />
						Check this box to remove the WP version information from your pages.
					</label></p>
				</div>
			</div>
			<!--end generator meta tag-->

			<?php
			//rather than confuse people on different types of servers,
			//let's restrict this to apache.  sure, some servers might
			//not admit they are apache, but obscurity has its cost!
			if(preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']))
			{
			?>
			<!--start wp-content .htaccess-->
			<div class="postbox">
				<h3 class="hndle">Prevent direct script execution</h3>
				<div class="inside">
					<p>WordPress themes and plugins are made up of PHP scripts that *should* only be executed indirectly through the WordPress engine. Untargetted attacks generally involve sending robots around to poke at these scripts directly, looking for security weaknesses. Disallowing direct access to PHP files in wp-content renders such searches moot.</p>
					<p><label for="meow_wpcontent_htaccess">
						<input type="checkbox" name="meow_wpcontent_htaccess" id="meow_wpcontent_htaccess" value="1" <?php echo (meow_wpcontent_htaccess_exists() ? 'checked=checked' : ''); ?> />
						Check this box to disable the direct execution of PHP scripts stored inside wp-content/.
					</label></p>
					<p class="description">Note: This might break things!  Some (lazy) plugins and themes foresake WP's engine and execute their scripts directly (and thus won't work if this option is enabled). If things break so badly you cannot even access this page to disable the option, simply delete <code><?php echo esc_html(MEOW_HTACCESS_FILE); ?></code> via FTP.</p>
				</div>
			</div>
			<!--end wp-content .htaccess-->
			<?php
			}//end if apache
			?>

			<?php
			//only show this section if relevant
			if(username_exists('admin') && $meow_changed_admin !== true) {
			?>
			<!--start admin user-->
			<div class="postbox">
				<h3 class="hndle">Admin who?</h3>
				<div class="inside">
					<p>The default WordPress username (&quot;admin&quot;) is in use and should be changed to greatly enhance your login security.  To do this now, type something else below.</p>
					<p><input type="text" name="meow_admin_user" id="meow_admin_user" value="admin" /></p>
					<?php
					//are there any scary stats to pull from this very blog?
					$attempts_admin = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow_log` WHERE `success`=0 AND `username`='admin'");
					if($attempts_admin > 0)
					{
						$attempts_total = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow_log` WHERE `success`=0");
						echo '<p class="description">' . round(100 * $attempts_admin / $attempts_total, 1) . '% of the (failed) attempts to gain access to your blog tried the username &quot;admin&quot;.</p>';
					}
					?>
				</div>
			</div>
			<!--end admin user-->
			<?php }	?>

			<!--start disable editor-->
			<div class="postbox">
				<h3 class="hndle">Disable theme/plugin editor</h3>
				<div class="inside">
					<p>WordPress comes with the ability to edit theme and plugin files directly through the browser.  Disable this to increase your file security.</p>
					<p><label for="meow_disable_editor">
						<input type="checkbox" name="meow_disable_editor" id="meow_disable_editor" value="1" <?php echo ($meowdata['meow_disable_editor'] === true ? 'checked=checked' : ''); ?> />
						Check this box to disable the file editor.
					</label></p>
					<p class="description">Note: This will have no effect if the <code>DISALLOW_FILE_EDIT</code> constant is already defined elsewhere (like wp-config.php or some such).</p>
				</div>
			</div>
			<!--end disable editor-->

			<!-- About Us -->
			<div class="postbox">
				<div class="inside">
					<a href="http://www.blobfolio.com/donate.html" title="Blobfolio, LLC" target="_blank"><img src="<?php echo esc_url(plugins_url('blobfolio.png', __FILE__)); ?>" class="logo" alt="Blobfolio logo"></a>

					<p>We hope you find this plugin useful.  If you do, you might be interested in our other plugins, which are also completely free (and useful).</p>
					<ul>
						<li><a href="http://wordpress.org/plugins/look-see-security-scanner/" target="_blank" title="Look-See Security Scanner">Look-See Security Scanner</a>: verify the integrity of a WP installation by scanning for unexpected or modified files.</li>
						<li><a href="http://wordpress.org/plugins/sockem-spambots/" target="_blank" title="Sock'Em SPAMbots">Sock'Em SPAMbots</a>: a more seamless approach to deflecting the vast majority of SPAM comments.</li>
					</ul>
				</div>
			</div><!--.postbox-->

		</div>
		<!--end sidebar-->

		<div id="post-body-content" class="has-sidebar">
			<div class="has-sidebar-content">

				<!--start log-in protection-->
				<div class="postbox">
					<h3 class="hndle">Log-in Protection</h3>

					<!--if we can log logins, show login logging options!-->
					<div class="inside">
						<p>Sometimes bad people use robots to cycle through zillions of possible log-in combinations.  If they magically guess a valid combination, your blog will magically become a Canadian pharmacy or Russian dating site, which is generally not desirable.  Luckily, we can mitigate the effectiveness of such an attack by limiting the number of failed log-in attempts allowed per person within a given time frame.</p>

						<?php
						//reverse proxies and intranets can strip visitor IP information before talking to PHP,
						//which is bad as the plugin can no longer tell who's who. rather than silently fail,
						//let's give the administrator a heads up!
						if(false === meow_get_IP())
						{
						?>
							<div class="error fade"><p>Warning: your server or WordPress configuration seems to be masking your IP address (<code><?php echo $_SERVER['REMOTE_ADDR']; ?></code>).  The log-in protection functions require access to the public IP address of each visitor.  If the IP exposed to PHP falls within private or reserved ranges, or if it matches the server's IP, no action can be taken.</p></div>
						<?php
						}//end IP error
						?>

						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row">Activate?</th>
									<td>
										<label for="meow_protect_login">
											<input type="checkbox" name="meow_protect_login" id="meow_protect_login" value="1" <?php echo ($meowdata['meow_protect_login'] === true ? 'checked=checked' : ''); ?> /> Enable log-in protection.
										</label>
									</td>
								</tr>
								<tr valign="top" class="meow-protect-login-only">
									<th scope="row">Limitations</th>
									<td>
										<input type="number" step="1" min="1" id="meow_fail_limit" name="meow_fail_limit" value="<?php echo $meowdata['meow_fail_limit']; ?>" class="small-text" />
										<label for="meow_fail_limit">The maximum number of failed log-in attempts.</label>
										<br />

										<input type="number" step="1" min="1" max="1440" id="meow_fail_window" name="meow_fail_window" value="<?php echo floor($meowdata['meow_fail_window']/60); ?>" class="small-text" />
										<label for="meow_fail_window">The time (in minutes) before a failed log-in attempt expires.</label>
										<br />

										<label for="meow_fail_reset_on_success"><input type="checkbox" name="meow_fail_reset_on_success" id="meow_fail_reset_on_success" value="1" <?php echo ($meowdata['meow_fail_reset_on_success'] === true ? 'checked=checked' : ''); ?> /> Reset fail count on successful log-in.</label>
									</td>
								</tr>
								<tr valign="top" class="meow-protect-login-only">
									<th scope="row">Exempt IP(s), one per line</th>
									<td>
										<textarea name="meow_ip_exempt" rows="5" cols="50"><?php echo esc_textarea(trim(implode("\n", $meowdata['meow_ip_exempt']))); ?></textarea>
										<p class="description">To avoid accidentally banning yourself, you might consider adding your IP address (<code><?php echo $_SERVER['REMOTE_ADDR']; ?></code>) to the above list.</p>
									</td>
								</tr>
								<tr valign="top" class="meow-protect-login-only">
									<th scope="row">Apocalypse Meow</th>
									<td>
										<input type="text" name="meow_apocalypse_title" id="meow_apocalypse_title" value="<?php echo esc_attr($meowdata['meow_apocalypse_title']); ?>" class="regular-text" />
										<p><textarea name="meow_apocalypse_content" id="meow_apocalypse_content" rows="5" cols="50"><?php echo esc_textarea($meowdata['meow_apocalypse_content']); ?></textarea></p>
										<p class="description">Note: Some servers may display a generic <code>403 Forbidden</code> page instead of the above message, but further log-in attempts are prevented either way.</p>
									</td>
								</tr>
								<tr valign="top" class="meow-protect-login-only">
									<th scope="row">Data settings</th>
									<td>
										<p><label for="meow_store_ua"><input type="checkbox" name="meow_store_ua" id="meow_store_ua" value="1" <?php echo ($meowdata['meow_store_ua'] === true ? 'checked=checked' : ''); ?> /> Check this box to save the &quot;user agent&quot; information <a href="#" id="show-ua-info">[?]</a> for each log-in attempt.</label></p>
										<p class="description">This information is easily forged and won't always be accurate, so if you don't find it all that interesting, uncheck the box to save the disk space.  :)</p>
									</td>
								</tr>
								<tr valign="top" class="meow-protect-login-only">
									<th scope="row">Database maintenance</th>
									<td id="td-meow-database-maintenance">
										<p><label for="meow_clean_database"><input type="checkbox" name="meow_clean_database" id="meow_clean_database" value="1" <?php echo ($meowdata['meow_clean_database'] === true ? 'checked=checked' : ''); ?> /> Check this box to enable database maintenance.</label></p>
										<p class="meow-clean-database-only">Automatically purge log-in data older than <input type="number" step="10" min="10" id="meow_data_expiration" name="meow_data_expiration" value="<?php echo $meowdata['meow_data_expiration']; ?>" class="small-text" /> days.</p>
										<p class="meow-clean-database-only description">Note: the maintenance routines are run after a successful log-in, so data might stick around longer than expected if you aren't frequently logging in.</p>
										<p><button id="meow-purge-data" type="button">Purge ALL Data</button></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div><!--.inside-->
				</div>
				<!--end log-in protection-->



				<!--start password requirements-->
				<div class="postbox">
					<h3 class="hndle">Password Requirements</h3>
					<div class="inside">
						<p>Most people use horribly insecure passwords.  Tweak the following settings to enforce halfway decent choices from your users.<br />
						<span class="description">Note: These requirments are only applied to new (or updated) passwords; they have no effect on current passwords.</span></p>

						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row">Letters...</th>
									<td>
										<p><input type="radio" name="meow_password_alpha" id="meow_password_alpha_optional" value="optional" <?php echo ($meowdata['meow_password_alpha'] === 'optional' ? 'checked=checked' : ''); ?> /> <label for="meow_password_alpha_optional">Letters are optional.</label><br />
										<input type="radio" name="meow_password_alpha" id="meow_password_alpha_required" value="required" <?php echo ($meowdata['meow_password_alpha'] === 'required' ? 'checked=checked' : ''); ?> /> <label for="meow_password_alpha_required">Passwords must contain at least one letter (case is unimportant).</label><br />
										<input type="radio" name="meow_password_alpha" id="meow_password_alpha_required_both" value="required-both" <?php echo ($meowdata['meow_password_alpha'] === 'required-both' ? 'checked=checked' : ''); ?> /> <label for="meow_password_alpha_required_both">Passwords must contain at least one uppercase letter and at least one lowercase letter.</label></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">Numbers...</th>
									<td>
										<p><input type="radio" name="meow_password_numeric" id="meow_password_numeric_optional" value="optional" <?php echo ($meowdata['meow_password_numeric'] === 'optional' ? 'checked=checked' : ''); ?> /> <label for="meow_password_numeric_optional">Numbers are optional.</label><br />
										<input type="radio" name="meow_password_numeric" id="meow_password_numeric_required" value="required" <?php echo ($meowdata['meow_password_numeric'] === 'required' ? 'checked=checked' : ''); ?> /> <label for="meow_password_numeric_required">Passwords must contain at least one number.</label></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">Symbols...</th>
									<td>
										<p><input type="radio" name="meow_password_symbol" id="meow_password_symbol_optional" value="optional" <?php echo ($meowdata['meow_password_symbol'] === 'optional' ? 'checked=checked' : ''); ?> /> <label for="meow_password_symbol_optional">Symbols are optional.</label><br />
										<input type="radio" name="meow_password_symbol" id="meow_password_symbol_required" value="required" <?php echo ($meowdata['meow_password_symbol'] === 'required' ? 'checked=checked' : ''); ?> /> <label for="meow_password_symbol_required">Passwords must contain at least one non-alphanumeric character, like a space or dash or something.</label></p>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row">Minimum password length</th>
									<td>
										<p><input type="number" step="1" min="5" id="meow_password_length" name="meow_password_length" value="<?php echo $meowdata['meow_password_length']; ?>" class="small-text" /></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--end password requirements-->



			</div><!-- /has-sidebar-content -->
		</div><!-- /has-sidebar -->


		<p class="submit"><input type="submit" name="submit" value="Save" /></p>
		</form>

	</div><!-- /metabox-holder has-right-sidebar -->
</div><!-- /wrap -->

<script type="text/javascript">

//show/hide fields based on condition
function meow_toggle_fields(meow_class, meow_condition){
	if(meow_condition)
		jQuery(meow_class + '.meow-hidden').removeClass('meow-hidden');
	else
		jQuery(meow_class).not('.meow-hidden').addClass('meow-hidden');
}

//show/hide login protection options
jQuery("#meow_protect_login").click(function(){
	meow_toggle_fields('.meow-protect-login-only', jQuery(this).is(':checked'));
});
meow_toggle_fields('.meow-protect-login-only', jQuery('#meow_protect_login').is(':checked'));

//show/hide database maintenance options
jQuery("#meow_clean_database").click(function(){
	meow_toggle_fields('.meow-clean-database-only', jQuery(this).is(':checked'));
});
meow_toggle_fields('.meow-clean-database-only', jQuery('#meow_clean_database').is(':checked'));

//show the u/a info
jQuery("#show-ua-info").click(function(e){
	e.preventDefault();
	alert("Software used to access your web site will usually identify information about itself, including its vendor, version, operating system, and so on.  Your User Agent is reported as:\n\n<?php echo esc_js($_SERVER['HTTP_USER_AGENT']); ?>");
});

//manually purge data
jQuery("#meow-purge-data").click(function(e){
	e.preventDefault();
	if(confirm("Are you absolutely, positively sure you want to clear ALL the log-in data (history, stats, blocked people, etc.)?  WARNING: This cannot be undone!") == true)
	{
		jQuery("#meow-purge-data").attr('disabled','disabled');
		jQuery.post(ajaxurl, {action: 'meow_purge_data', nonce: '<?php echo wp_create_nonce("m30wpurg3"); ?>'}, function(data){
			if(parseInt(data) == 1)
			{
				jQuery("#meow-purge-data").remove();
				jQuery('<p />')
					.text('The data was successfully cleared.')
					.appendTo('#td-meow-database-maintenance');
			}
			else
			{
				jQuery("#meow-purge-data").removeAttr('disabled');
				alert("Oops.  Something went wrong!  Reload this page then try again.");
			}

			return false;
		});
	}
	else
		return false;
});

//let's warn people that they might get a blank white screen
//after PHP deletes the .htaccess file.
var htaccess_warned=<?php echo meow_wpcontent_htaccess_exists() ? 0 : 1 ?>;
jQuery('#meow_wpcontent_htaccess').click(function(){
	//only warn once
	if(htaccess_warned === 1)
		return;

	//a simple alert will do
	alert("Just a heads up: it is normal to receive a blank page immediately after disabling this option. It is a one-time thing, nothing to worry about.");

	//and don't bother them again
	htaccess_warned = 1;
});

</script>