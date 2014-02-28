<?php
/*
Plugin Name: Wordpress Poll
Plugin URI: http://www.vinojcardoza.com/blog/cardoza-wordpress-poll
Description: Wordpress Poll is completely ajax powered polling system. This poll plugin supports both single and multiple selection of answers.
Version: 35.0
Author: Vinoj Cardoza
Author URI: http://www.vinojcardoza.com
License: GPL2
*/
define('CWP_PGN_DIR', plugin_dir_url(__FILE__));

/*Calling all the required files*/
require_once 'app/CWPController.class.php';
require_once 'cardozawppollFrontEndFunctions.php';
require_once 'cardozawppolldb.php';

add_action('wp_head','cwppoll_ajaxurl');
add_action('wp_enqueue_scripts', 'enq_scripts');
add_action('admin_head','cwppoll_ajaxurl');
add_action('plugins_loaded', 'trigger_init');
add_action('wp_footer', 'poll_custom_css');

register_activation_hook  ( __FILE__, 'CWP_Install' );

/* To include the javascripts */
function enq_scripts(){
	/* To include the javascripts */
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('cwp-main', plugins_url('/public/js/CWPPoll.js', __FILE__), array('jquery', 'jquery-ui-core'));
	wp_enqueue_script('cwp-main-datepicker', plugins_url('/public/js/jquery.ui.datepicker.min.js', __FILE__), array('jquery', 'jquery-ui-core'));
	
	/* To include the stylesheets */	
	wp_enqueue_style('cwpcss', CWP_PGN_DIR.'public/css/CWPPoll.css');
	wp_enqueue_style('cwpcssjqui', CWP_PGN_DIR.'public/css/JqueryUi.css');	
}

function poll_custom_css(){
	print '<style type="text/css">';
	print get_option('poll_custom_css_code');
	print '</style>';
}

function cwppoll_ajaxurl() {
    $ajax_nonce = wp_create_nonce('cwpp#23151982');
    echo "<script type='text/javascript'>\n" .
        " var CwppPlgSettings = {\n" .
        "   ajaxurl : '" . admin_url( 'admin-ajax.php' ) . "',\n" .
        "   nonce : '" . $ajax_nonce . "'\n" .
        " };\n" .
        "</script>\n";
}

function trigger_init(){
     
    if(is_admin()){
        $cwp = new CWPController();
        $cwp->init();
    }
    load_plugin_textdomain('cardozapolldomain', false, dirname( plugin_basename(__FILE__)).'/languages');
    wp_register_sidebar_widget('CWP_WP_POLL', 'Wordpress Poll', 'widget_cardoza_wp_poll');
}


function widget_cardoza_wp_poll($args){
    
    $cwp = new CWPController();
    
    $option_value = $cwp->cwpp_options();
    
    $vars = array();
    $vars['option_value'] = $option_value;
    
    extract($args);
    echo $before_widget;
    echo $before_title;
    
    if(empty($option_value['title'])) $option_value['title'] = "Poll";
    echo $option_value['title'];
    
    if(!empty($option_value['no_of_polls'])) $no_of_polls_in_Widget = $option_value['no_of_polls'];
    else $no_of_polls_in_Widget = 1;
    
    echo $after_title;
    
    $polls = $cwp->retrievePoll();
    $count = 1;
    foreach($polls as $poll){
        $poll_end_date = $poll->end_date;
        $expiry_date = explode('/', $poll_end_date);
        $exp_time = mktime(0,0,0,$expiry_date[1], $expiry_date[0], $expiry_date[2]);
        if($count <= $no_of_polls_in_Widget){?>
            <div class="widget-poll" <?php if(!empty($option_value['poll_bg_color'])) echo 'style="background-color:#'.$option_value['poll_bg_color'].';"';?>>
                <div class="widget-poll-question"><?php print $poll->question;?></div>
                
                <form id="poll<?php print $poll->id;?>" method="post" action="">
                
                <?php
                
                $poll_answers = $cwp->getPollAnswers($poll->id);
                
                $vars['poll_answers'] = $poll_answers;
                $vars['total_votes'] = $poll->total_votes;
                $vars['poll_id'] = $poll->id;
                $vars['poll'] = $poll;
                $vars['exp_time'] = $exp_time;
                
                if($option_value['poll_access']=='loggedin'){
                        
                    if(is_user_logged_in()){
                        global $current_user;
                        get_currentuserinfo();
                        $loggedinuserid = $current_user->ID;
                        $status = $cwp->getPollLogged($poll->id, $loggedinuserid);
                        if(empty($status)){
                            showPollForm($vars);
                            toggleResultsVotes($poll->id, $vars);
                        }
                        else displayPollResults($vars);
                        
                    }
                    else displayPollResults($vars);
                }
                else{
                    $lock_by = $option_value['poll_lock'];
                    if(empty($lock_by)) $lock_by = 'cookies';
                    if($lock_by == 'cookies'){
                        if(isset($_COOKIE['cwppoll'.$poll->id])) displayPollResults($vars);
                        else{
                            showPollForm($vars);
                            toggleResultsVotes($poll->id, $vars);
                        }
                    }
                    elseif($lock_by == 'ipaddress'){    
                        $status = $cwp->getPollIPLogged($poll->id);
                        if(!empty($status)) displayPollResults($vars);
                        else{
                            showPollForm($vars);
                        	toggleResultsVotes($poll->id, $vars);
                        }
                    }
                }
                ?>
                </form>
            </div>
            
        <?php 
        }
        $count++;
    }
    previousPollsLink($vars);
    echo $after_widget;
}

add_shortcode("cardoza_wp_poll_archive", "cwp_poll_archive");

//function to display the poll archive shortcode
function cwp_poll_archive($atts){
    
    $cwp = new CWPController();
    $option_value = $cwp->cwpp_options();
    $current_time = time();
    
    /* Object for Model (CWPModel.class.php) */
    $model_cwp = new CWPModel();
    
    /* To get all the polls */
    $all_polls = $model_cwp->getPollsFromDB();
    $archive_option = $option_value['polls_to_display_archive'];
    $polls_to_display = $option_value['no_of_polls_to_display_archive'];
    $archive_polls = array();
    
    foreach($all_polls as $poll)
    {
        $stimestamp = $cwp->getStrToTime($poll->start_date);
        $etimestamp = $cwp->getStrToTime($poll->end_date);
        if($archive_option == 'open')
        {
            if($current_time < $etimestamp && $current_time > $stimestamp) $archive_polls[] = $poll;
        }
        if($archive_option == 'closed')
        {
            if($current_time > $etimestamp) $archive_polls[] = $poll;
        }
        if($archive_option == 'open-closed')
        {
            if($current_time > $stimestamp) $archive_polls[] = $poll;
        }
    }
    
    $total_polls = sizeof($archive_polls);
    
    if(isset($_GET['poll_archive_page_no'])) $page_no = $_GET['poll_archive_page_no'];       
    else $page_no = 1;
    
    $start_from = ($page_no - 1) * $polls_to_display;
    $end_to = ($page_no * $polls_to_display) - 1;
    
    $i = 0;
    foreach($archive_polls as $poll)
    {
        if($i >= $start_from && $i <= $end_to) $polls[] = $poll;
        $i++;
    }
    
    if($page_no != 1) $previous_page = $page_no -1;
    else $previous_page = $page_no = 1;
    
    if($end_to < $total_polls-1) $next_page = $page_no+1;
    else $next_page = $page_no;
    
    $count = 1;
    $archive_url = $option_value['archive_url'];
    $url = explode('?', $archive_url);
    
    if(sizeof($url)>1){
        if($_GET['poll_archive_page_no']!=1) {?>
            <a style="margin-right:10px;" id="previous-page" href="<?php echo $option_value['archive_url'].'&poll_archive_page_no='.$previous_page;?>">Previous Page</a>
        <?php } ?>
        <a id="next-page" href="<?php echo $option_value['archive_url'].'&poll_archive_page_no='.$next_page;?>">Next Page</a>
        <?php   
    }
    else{
        if($_GET['poll_archive_page_no']!=1) {?>
        <a id="previous-page" href="<?php echo $option_value['archive_url'].'?poll_archive_page_no='.$previous_page;?>">Previous Page</a>
        <?php } ?>
        <a id="next-page" href="<?php echo $option_value['archive_url'].'?poll_archive_page_no='.$next_page;?>">Next Page</a>
    <?php 
    } 
    
    if(empty($polls)) print "<br>".__("Sorry! No polls found in the archive for this page", "cardozapolldomain");
    
    foreach($polls as $poll){
        $stimestamp = $cwp->getStrToTime($poll->start_date);
        $etimestamp = $cwp->getStrToTime($poll->end_date);
        ?>
        <br/>
        <strong>Poll id : </strong>#<?php print $poll->id." - ".date('F jS, Y', $stimestamp);?>&nbsp;to&nbsp;<?php print date('F jS, Y', $etimestamp);?>&nbsp;&nbsp;(<?php
            if($etimestamp > $current_time) echo __("Open", "cardozapolldomain");
            else echo __("Closed", "cardozapolldomain");
        ?>)
        <div class="widget-poll">
            <div class="widget-poll-question"><?php print $poll->question;?></div>
            <?php
            $poll_answers = $cwp->getPollAnswers($poll->id);         
            $vars['poll_answers'] = $poll_answers;
            $vars['total_votes'] = $poll->total_votes;
            $vars['option_value'] = $option_value;
            $vars['poll_id'] = $poll->id;
            $vars['poll'] = $poll;
            displayPollResults($vars);
            ?>
        </div>
    <?php 
    }
}

add_shortcode("cardoza_wp_poll", "cwp_poll_id_display");

function cwp_poll_id_display($atts){
    ob_start();
    $cwp = new CWPController();
    $option_value = $cwp->cwpp_options();
    
    $vars = array();
    $vars['option_value'] = $option_value;
                
    $polls = $cwp->getPollList();
    
    foreach($polls as $poll){
        
        $stimestamp = $cwp->getStrToTime($poll->start_date);
        $etimestamp = $cwp->getStrToTime($poll->end_date);
        
        $current_time = time();
        
        if($poll->id == trim($atts['id'])){?>
            <div class="widget-poll">
                <div class="widget-poll-question"><?php print $poll->question;?></div>
                
                <form id="pollsc<?php print $poll->id;?>" method="post" action="">
                
                <?php
                
                $poll_answers = $cwp->getPollAnswers($poll->id);
                
                $vars['poll_answers'] = $poll_answers;
                $vars['total_votes'] = $poll->total_votes;
                $vars['poll'] = $poll;
                $vars['exp_time'] = $etimestamp;
                
                if($current_time>$stimestamp && $current_time < $etimestamp){
                    
                    if($option_value['poll_access']=='loggedin'){

                        if(is_user_logged_in()){
                                global $current_user;
                                get_currentuserinfo();
                                $loggedinuserid = $current_user->ID;
                                $status = $cwp->getPollLogged($poll->id, $loggedinuserid);
                                if(empty($status)){
                                    showPollFormSC($vars);
                                    toggleResultsVotes($poll->id, $vars);
                                }
                                else displayPollResults($vars);							
                        }
                        else displayPollResults($vars);
                    }

                    else{
                        $lock_by = $option_value['poll_lock'];
                        if(empty($lock_by)) $lock_by = 'cookies';
                        if($lock_by == 'cookies'){
                            if(isset($_COOKIE['cwppoll'.$poll->id])) displayPollResults($vars);
                            else{
                                showPollFormSC($vars);
                                toggleResultsVotes($poll->id, $vars);
                            }
                        }
                        elseif($lock_by == 'ipaddress'){
                            $status = $cwp->getPollIPLogged($poll->id);
                            if(empty($status)){
                                showPollFormSC($vars);
                                toggleResultsVotes($poll->id, $vars);
                            }
                            else displayPollResults($vars);
                        }
                    }
                    ?>
                </form>
            </div>
            
        <?php 
                }
        }
        $count++;
    }    
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}
add_action('plugins_loaded', 'cwp_db_update');

/* 
Function name: toggleResultsVotes
Arguments: Poll id and variables retrieved by the poll id.
This function will enable the View Result functionality 
in the widget and short code area. The users can see the results
without voting.
*/
function toggleResultsVotes($pollid, $vars){
    $pollid = htmlspecialchars(strip_tags($pollid));
	?>
	<center>
		<a class="showresultslink<?php echo $pollid;?>" href="javascript:showresults(<?php echo $pollid;?>);">
			<?php _e('View Result', 'cardozapolldomain');?>
		</a>
	</center>
	<div class="show-results<?php echo $pollid;?>" style="display:none;">
		<?php displayPollResults($vars);?>
		<center>
			<a class="showformlink" href="javascript:showforms(<?php echo $pollid;?>);">
				<?php _e('Vote', 'cardozapolldomain');?>
			</a>
		</center>
	</div>
	<?php
}
?>
