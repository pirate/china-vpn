<?php

/**
 * Display the update notice in the Page Builder interface
 */
function siteorigin_panels_update_notice(){
	$dismissed = get_option('siteorigin_panels_notice_dismissed');

	if(empty($dismissed) || $dismissed != SITEORIGIN_PANELS_VERSION) {
		wp_enqueue_script('siteorigin-panels-admin-notice', plugin_dir_url(SITEORIGIN_PANELS_BASE_FILE) . 'js/panels.admin.notice.min.js', array('jquery'), SITEORIGIN_PANELS_VERSION);

		?>
		<div class="updated">
			<p>
				<?php
				if( get_option('siteorigin_panels_initial_version') == SITEORIGIN_PANELS_VERSION ) {
					printf( __("You've successfully installed <strong>Page Builder</strong> version %s. ", 'siteorigin-panels'), SITEORIGIN_PANELS_VERSION );
				}
				else {
					printf( __("You've successfully updated <strong>Page Builder</strong> to version %s. ", 'siteorigin-panels'), SITEORIGIN_PANELS_VERSION );
				}

				printf(
					__('Please post on our <a href="%s" target="_blank">support forums</a> if you have any issues and sign up to <a href="%s" target="_blank">our newsletter</a> to stay up to date.', 'siteorigin-panels'),
					'http://siteorigin.com/threads/plugin-page-builder/',
					'http://siteorigin.com/page-builder/#newsletter'
				)
				?>
			</p>
			<p>
				<a href="http://siteorigin.com/threads/plugin-page-builder/" class="button button-secondary" target="_blank"><?php _e('Support Forums', 'siteorigin-panels') ?></a>
				<a href="http://siteorigin.com/page-builder/#newsletter" class="button button-secondary" target="_blank"><?php _e('Newsletter', 'siteorigin-panels') ?></a>
				<?php if(empty($dismissed)) : ?>
					<a href="<?php echo add_query_arg('action', 'siteorigin_panels_update_notice_dismiss', admin_url( 'admin-ajax.php') ) ?>" class="button button-primary" id="siteorigin-panels-dismiss"><?php _e('Dismiss', 'siteorigin-panels') ?></a>
				<?php endif; ?>
			</p>
		</div>
		<?php
		if( !empty($dismissed) && $dismissed != SITEORIGIN_PANELS_VERSION ) {
			// The user has already dismissed this message, so we'll show it once and update the dismissed version
			update_option('siteorigin_panels_notice_dismissed', SITEORIGIN_PANELS_VERSION);
		}
	}
}
add_action('siteorigin_panels_before_interface', 'siteorigin_panels_update_notice');

/**
 * This action handles dismissing the updated notice.
 */
function siteorigin_panels_update_notice_dismiss_action(){
	add_option('siteorigin_panels_notice_dismissed', SITEORIGIN_PANELS_VERSION, '', 'no');
	exit();
}
add_action('wp_ajax_siteorigin_panels_update_notice_dismiss', 'siteorigin_panels_update_notice_dismiss_action');