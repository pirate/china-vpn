<?php
//----------------------------------------------------------------------
//  Apocalypse Meow log-in jail
//----------------------------------------------------------------------
//a page for viewing currently banned users and managing pardons
//
// @since 1.4.0



//--------------------------------------------------
//Check permissions

//let's make sure this page is being accessed through WP
if (!function_exists('current_user_can'))
	die('Sorry');
//and let's make sure the current user has sufficient permissions
elseif(!current_user_can('manage_options'))
	wp_die(__('You do not have sufficient permissions to access this page.'));
//if log-in protection is disabled, this page is useless
elseif(!meow_get_option('meow_protect_login'))
{
	echo '<div class="wrap"><div class="error fade"><p>Log-in protection is disabled.</p></div>' . meow_get_header() . '</div>';
	exit;
}



//--------------------------------------------------
//Build data

global $wpdb;

//first let's figure out who we're supposed to ignore
$meow_ip_pardoned = meow_get_option('meow_ip_pardoned');
$meow_ip_exempt = meow_get_option('meow_ip_exempt');
$meow_ip_ignore = meow_sanitize_ips(array_merge($meow_ip_pardoned, $meow_ip_exempt));

//find the log-in protection settings
$meow_fail_limit = meow_get_option('meow_fail_limit');
$meow_fail_window = meow_get_option('meow_fail_window');
$meow_fail_reset_on_success = meow_get_option('meow_fail_reset_on_success');

//first pass: find probably banned people
$dbResult = $wpdb->get_results("SELECT `ip`, UNIX_TIMESTAMP() - MIN(`date`) AS `first_fail` FROM `{$wpdb->prefix}meow_log` WHERE UNIX_TIMESTAMP()-`date` <= $meow_fail_window AND `success`=0 " . (count($meow_ip_ignore) ? "AND NOT `ip` IN ('" . implode("','", $meow_ip_ignore) . "') " : '') . "GROUP BY `ip` ORDER BY `first_fail` ASC", ARRAY_A);
$meow_banned = array();
if($wpdb->num_rows)
{
	//save as IP=>(seconds since first failure)
	foreach($dbResult AS $Row)
		$meow_banned[$Row['ip']] = (int) $Row['first_fail'];
}

//if the fail count resets on success, we have another pass to do (but only if the first yielded results)
//I'm sorry, I can't think of an efficient way to do this in a single pass
if($meow_fail_reset_on_success && count($meow_banned))
{
	//are there any successful logins for these maybe-banned users within the window?
	$dbResult = $wpdb->get_results("SELECT DISTINCT `ip` FROM `{$wpdb->prefix}meow_log` WHERE UNIX_TIMESTAMP()-`date` <= $meow_fail_window AND `success`=0 AND `ip` IN ('" . implode("','", array_keys($meow_banned)) . "') ORDER BY `ip` ASC", ARRAY_A);
	if($wpdb->num_rows)
	{
		//revalidate each of these manually
		foreach($dbResult AS $Row)
		{
			$meow_last_successful = (int) $wpdb->get_var("SELECT MAX(`date`) FROM `{$wpdb->prefix}meow_log` WHERE `ip`='" . esc_sql($Row['ip']) . "' AND `success`=1");
			//this person isn't banned after all...
			if($meow_fail_limit > (int) $wpdb->get_var("SELECT COUNT(*) AS `count` FROM `{$wpdb->prefix}meow_log` WHERE `ip`='" . esc_sql($Row['ip']) . "' AND `success`=0 AND UNIX_TIMESTAMP()-`date` <= $meow_fail_window AND `date` > $meow_last_successful"))
				unset($meow_banned[$Row['ip']]);
		}
	}
}
?>
<style type="text/css">
	#meow-login-jail tr.meow-pardoned td {
		text-decoration: line-through;
	}
		#meow-login-jail tr.meow-pardoned .meow-pardon {
			display: none;
		}
	.widefat {
		clear: none!important;
	}
</style>
<div class="wrap">

	<?php echo meow_get_header(); ?>

	<div class="metabox-holder has-right-sidebar">

		<div class="inner-sidebar">

			<!-- start list of pardons -->
			<div class="postbox">
				<h3 class="hndle">Unclaimed Pardons</h3>
				<div class="inside">
<?php
if(count($meow_ip_pardoned))
	echo '<p>' . implode('<br>', $meow_ip_pardoned) . '</p>';
else
	echo '<p>There are no unclaimed pardons.</p>';
?>
				</div>
			</div>
			<!-- end list of pardons -->

		</div>
		<!--end sidebar-->

		<div id="post-body-content" class="has-sidebar">
			<div class="has-sidebar-content">

				<!-- start log-in jail -->
				<div class="postbox">
					<h3 class="hndle">Log-in Jail</h3>
					<div class="inside">

						<table id="meow-login-jail" class="widefat tablesorter">
							<thead>
								<tr>
									<th>#</th>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;IP</th>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;First Infraction</th>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;Sentence Expires</th>
									<th>&nbsp;&nbsp;&nbsp;&nbsp;Sentence Remaining</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
<?php

	//are there banned users?
	if(count($meow_banned))
	{
		//print each one to screen
		$num = 0;
		foreach($meow_banned AS $k=>$v)
		{
			//row #
			$num++;

			//calculate some dates
			$expires = date("Y-m-d H:i:s", strtotime("+" . ($meow_fail_window - $v) . ' seconds', current_time('timestamp')));
			$first_fail = date("Y-m-d H:i:s", strtotime("-$v seconds", current_time('timestamp')));

			//calculate the expiration relative to now in an easy-to-understand way
			$expires_relative = array();
			$s = $m = $h = 0;
			$s = $meow_fail_window - $v;
			$h = floor($s/60/60);
			$s -= $h * 60 * 60;
			$m = floor($s/60);
			$s -= $m * 60;
			if($h > 0)
				$expires_relative[] = "$h hour" . ($h > 1 ? 's' : '');
			if($m > 0)
				$expires_relative[] = "$m minute" . ($m > 1 ? 's' : '');
			if($s > 0)
				$expires_relative[] = (count($expires_relative) ? 'and ' : '') . "$s second" . ($s > 1 ? 's' : '');
			$expires_relative = implode(', ', $expires_relative);

			//print the row
			echo '<tr id="meow-jail-' . $num . '">
				<td class="meow-record-number"></td>
				<td>' . esc_html($k) . '</td>
				<td>' . esc_html($first_fail) . '</td>
				<td>' . esc_html($expires) . '</td>
				<td>' . esc_html($expires_relative) . '</td>
				<td><a href="#" class="meow-pardon" data-num="' . $num . '" data-ip="' . esc_attr($k) . '">pardon now</a></td>
				</tr>';
		}
	}
	//nope, crime is at an all-time low!
	else
		echo '<tr><td colspan="6">The Log-in Jail is currently empty!  Yay for world peace!</td></tr>';
?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- end log-in jail -->

			</div> <!-- /.has-sidebar-content -->
		</div><!-- /.has-sidebar-->
	</div><!-- /.has-right-sidebar -->


</div>
<script type="text/javascript">

	//make the jail table sortable
	jQuery("#meow-login-jail").tablesorter({sortList: [[1,1]], headers: { 0: { sorter: false}, 5: { sorter: false} }});

	//issue a pardon
	jQuery('.meow-pardon').click(function(e){

		e.preventDefault();

		var ip = jQuery(this).attr('data-ip');
		var num = jQuery(this).attr('data-num');

		if(confirm("Are you absolutely sure you want to issue a get-out-of-jail-free card to " + ip + "?\n\nNOTE: This will allow just one more attempt, so be sure they type the password in correctly this time.  :)") == true)
		{
			jQuery.post(ajaxurl, {action: 'meow_login_pardon', ip: ip, nonce: '<?php echo wp_create_nonce("m30wpardon"); ?>'}, function(data){
				if(parseInt(data) == 1)
				{
					alert('You\'re too kind! A pardon has been issued.');
					jQuery('#meow-jail-' + num).addClass('meow-pardoned');
				}
				else
				{
					alert("Oops.  Something went wrong!  Reload this page and try again.");
				}

				return false;
			});
		}
		else
			return false;

	});

	//dynamically number the rows
	function meow_number_records(){
		var num = 0;
		jQuery("#meow-login-jail .meow-record-number:visible").each(function(k,v){
			num++;
			jQuery(this).html(num);
		});
	}
	meow_number_records();

</script>