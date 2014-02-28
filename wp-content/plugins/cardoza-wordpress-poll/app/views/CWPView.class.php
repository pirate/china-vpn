<?php
/*
 * Filename: CWPView.class.php
 * This is the class file which will have all the markup for the user interface.
 */

require 'CWPViewCreatePoll.class.php';
require 'CWPViewCreateImagePoll.class.php';
require 'CWPViewPollOptions.class.php';
require 'CWPViewWidget.class.php';
require 'CWPViewManagePolls.class.php';
require 'CWPViewEditPoll.class.php';
require 'CWPViewStats.class.php';
require 'CWPViewUserLogs.class.php';
require 'CWPViewCustomCSS.class.php';


class CWPView {
    
    public $view_manage_poll;
    public $view_edit_poll;
    /* To initialize the admin menu in the wordpress cms backend */
    
    public function __construct(){
        $this->view_manage_poll = new CWPViewManagePolls();
        $this->view_edit_poll = new CWPViewEditPoll();
    }

    public function cwp_admin_menu_init() {

        add_action('admin_menu', array(&$this, 'cwp_admin_menu'));
    }

    /* Creates 'Cardoza Poll' menu in the wordpress cms backend */

    public function cwp_admin_menu() {

        $page = add_menu_page(__('Manage Polls'), __('Manage Polls'), 'manage_options', 'cwp_poll', array(&$this, 'poll_page'), CWP_PGN_DIR . 'public/css/images/poll.png');
		$subpage_poll_options = add_submenu_page('cwp_poll', __('Poll Options'), __('Poll Options'), 'manage_options', 'poll_options', array(&$this, 'poll_options'));
		$subpage_poll_widget_options = add_submenu_page('cwp_poll', __('Widget Options'), __('Widget Options'), 'manage_options', 'poll_widget_options', array(&$this, 'poll_widget_options'));
		$subpage_add_new_poll = add_submenu_page('cwp_poll', __('Add New Poll'), __('Add New Poll'), 'manage_options', 'add_new_poll', array(&$this, 'add_new_poll'));
		$subpage_add_new_image_poll = add_submenu_page('cwp_poll', __('Add New Image Poll'), __('Add New Image Poll'), 'manage_options', 'add_new_image_poll', array(&$this, 'add_new_image_poll'));
		$subpage_poll_statistics = add_submenu_page('cwp_poll', __('Poll Statistics'), __('Poll Statistics'), 'manage_options', 'poll_statistics', array(&$this, 'poll_statistics'));
		$subpage_poll_user_logs = add_submenu_page('cwp_poll', __('User Logs'), __('User Logs'), 'manage_options', 'poll_user_logs', array(&$this, 'poll_user_logs'));
		$subpage_poll_custom_css = add_submenu_page('cwp_poll', __('Custom CSS'), __('Custom CSS'), 'manage_options', 'poll_custom_css', array(&$this, 'poll_custom_css'));
		
		add_action('admin_footer-' . $page, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-' . $page, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_poll_options, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_poll_options, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_poll_widget_options, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_poll_widget_options, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_add_new_poll, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_add_new_poll, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_add_new_image_poll, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_add_new_image_poll, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_poll_statistics, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_poll_statistics, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_poll_user_logs, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_poll_user_logs, array(&$this, 'view_enq_scripts'));
		
		add_action('admin_footer-' . $subpage_poll_custom_css, array(&$this,'admin_poll_custom_css'));
		add_action('admin_print_scripts-'.$subpage_poll_custom_css, array(&$this, 'view_enq_scripts'));
		
    }

	public function admin_poll_custom_css(){
		print '<style type="text/css">';
		print get_option('poll_custom_css_code');
		print '</style>';
	}
	

	public function view_enq_scripts(){
		/* To include the javascripts */
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('cwp-main', CWP_PGN_DIR.'public/js/CWPPoll.js', array('jquery', 'jquery-ui-core'));
		wp_enqueue_script('cwp-main-datepicker', CWP_PGN_DIR.'public/js/jquery.ui.datepicker.min.js', array('jquery', 'jquery-ui-core'));
		
		/* To include the stylesheets */	
		wp_enqueue_style('cwpcss', CWP_PGN_DIR.'public/css/CWPPoll.css');
		wp_enqueue_style('cwpcssjqui', CWP_PGN_DIR.'public/css/JqueryUi.css');	
	}
	

    /* The actual admin interface starts form here */
    public function poll_page() {
        ?>
        <div class="wrap">
            <h2><?php _e("Wordpress Poll", "cardozapolldomain"); ?></h2>
            <h3>* <?php _e("Mandatory fields", "cardozapolldomain"); ?>.</h3>
            <div id="cwp-content">
                <?php 
            	$this->view_manage_poll->init();
        		?>
            </div>
        </div>
    <?php }

	public function poll_options(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$view_poll_options = new CWPViewPollOptions();
		print '</div>';
		print '</div>';
	}
	
	public function add_new_poll(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$view_create_poll = new CWPViewCreatePoll();
		print '</div>';
		print '</div>';
	}
	
	public function poll_widget_options(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$view_widget_options = new CWPViewWidget();
		print '</div>';
		print '</div>';
	}
	
	public function add_new_image_poll(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$view_create_image_poll = new CWPViewCreateImagePoll();
		print '</div>';
		print '</div>';
	}

	public function poll_statistics(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$view_stats = new CWPViewStats();
		print '</div>';
		print '</div>';
	}
	
	public function poll_user_logs(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$user_logs = new CWPViewUserLogs();
		print '</div>';
		print '</div>';
	}
	
	public function poll_custom_css(){
		print '<div class="wrap">';
        print '<h2>'.__("Wordpress Poll", "cardozapolldomain").'</h2>';
		print '<div id="cwp-content">';
		$custom_css = new CWPViewCustomCSS();
		print '</div>';
		print '</div>';
	}
    
    public function refreshPollsTable(){
        $this->view_manage_poll->init();
    }
    
    public function viewEditPoll(){
        $this->view_edit_poll->init();
    }
    
    public function viewAnswers(){
        $this->view_edit_poll->updatedAnswers();
    }
    
    
}
?>
