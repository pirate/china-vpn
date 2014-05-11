<?php
//----------------------------------------------------------------------
//  Apocalypse Meow mass password reset
//----------------------------------------------------------------------
//allow administrators to reset all user passwords en masse
//
// @since 1.6.0

global $wpdb;


//--------------------------------------------------
//Check permissions

//let's make sure this page is being accessed through WP
if (!function_exists('current_user_can'))
	die('Sorry');
//and let's make sure the current user has sufficient permissions
elseif(!current_user_can('manage_options'))
	wp_die(__('You do not have sufficient permissions to access this page.'));



?><style type="text/css">
	#meow-password-message {
		width: 100%;
		height: 200px;
	}

	.meow-password-progress-wrapper {
		background-color: #d54e21;
		height: 50px;
		transition: width .2s linear;
		min-width: 15px;
		width: 0%;
		display: block;
	}

</style>
<div class="wrap">

	<?php echo meow_get_header(); ?>

	<div class="metabox-holder has-right-sidebar">

		<div class="inner-sidebar">

			<!-- start list of pardons -->
			<div class="postbox">
				<h3 class="hndle">Settings</h3>
				<div class="inside meow-password-form-wrapper">
					<form class="meow-password-form">
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('m30wp@$$w0rd'); ?>" />
						<input type="hidden" name="page" id="meow-password-page" value="0" />
						<input type="hidden" name="action" value="meow_reset_passwords" />
						<input type="hidden" name="max" value="<?php echo $wpdb->get_var("SELECT MAX(`ID`) FROM `{$wpdb->prefix}users`"); ?>" />
						<p><strong>Message</strong></p>
						<label class="screen-reader-text" for="meow-password-message">Message</label>
						<textarea name="message" id="meow-password-message">Hi there!<?php echo "\n\n"; ?>Out of an abundance of caution, the site administrator has reset your account password. If you like your new randomly generated password, feel free to adopt it!  Otherwise if you would rather re-reset it, you may do so by clicking the profile link in the top/right corner after logging in.<?php echo "\n\n"; ?>Here is your new info:</textarea>
						<p class="description">The username, password, and site URL will be appended to the above message.</p>
						<p><input type="submit" id="meow-password-submit" value="Reset Passwords" /></p>
					</form>
				</div>
			</div>
			<!-- end list of pardons -->

		</div>
		<!--end sidebar-->

		<div id="post-body-content" class="has-sidebar">
			<div class="has-sidebar-content">

				<!-- start log-in jail -->
				<div class="postbox">
					<h3 class="hndle">Reset Passwords</h3>
					<div class="inside meow-password-info-wrapper">
						<p>Whether you believe your server might have suffered a security breach or you just want to keep your users on their toes, you can use the form at right to reset passwords en masse. Each user will get a nice, strong, unique password and a notification e-mail.</p>
					</div>
				</div>
				<!-- end log-in jail -->

			</div> <!-- /.has-sidebar-content -->
		</div><!-- /.has-sidebar-->
	</div><!-- /.has-right-sidebar -->


</div><!--.wrap-->

<script type="text/javascript">

//-------------------------------------------------
// Steal the form submission

jQuery('.meow-password-form').submit(function(){

	//disable the form fields to prevent midway messery
	jQuery('#meow-password-message').prop('readonly',true);
	jQuery('#meow-password-submit').prop('disabled',true);

	//start the progress bar
	jQuery('.meow-password-info-wrapper').html('<p>Mass password resets are underway. Please do not migrate away from this page until it completes.</p><div class="meow-password-progress-wrapper"></div>');

	//call our launcher wrapper
	meow_password_reset();

	//don't actually process it.
	return false;
});

//-------------------------------------------------
// Reset passwords

function meow_password_reset(){
	jQuery.post(ajaxurl, jQuery('.meow-password-form').serialize(), function(response){

		try {
			r = jQuery.parseJSON(response);
		}
		catch(e){
			jQuery('.meow-password-info-wrapper').append('<p>Curious. The server did not return a valid response: <code>' + response + '</code></p>');
			return;
		}

		if(r.success)
		{
			jQuery('.meow-password-progress-wrapper').css('width', (r.completed / r.total * 100) + '%');
			jQuery('#meow-password-page').val(r.page);

			//if we're done, say so
			if(r.completed == r.total)
				jQuery('.meow-password-info-wrapper').append('<p><strong>All done!</strong> You and everyone else should be getting password reset notices shortly.</p><p>P.S. It is normal to be logged out after completing this process. You will need to log in with your new password.</p>');
			//otherwise resubmit
			else
				meow_password_reset();
		}
		else
		{
			jQuery('.meow-password-info-wrapper').append('<p>An error has occurred. Please reload the page and try again.');
			return;
		}

	});
}

</script>