<?php
/*
 * Filename: CWPController.class.php
 * This is the class file which will have all the control for this plugin.
 */
require_once 'CWPModel.class.php';
require_once 'views/CWPView.class.php';

class CWPController {
    
    public $cwpv; //instance variable for views
    public $cwpm; //instance variable for model
    public $nonceValue;
    
    function __construct(){
        
        $this->cwpv = new CWPView();
        $this->cwpm = new CWPModel();
        
        $this->nonceValue= 'cwpp#23151982';

        add_action('wp_ajax_save_poll', array(&$this, 'saveNewPoll'));
        add_action('wp_ajax_nopriv_save_poll', array(&$this, 'saveNewPoll'));
        add_action('wp_ajax_refresh_poll_list', array(&$this, 'refreshPollList'));
        add_action('wp_ajax_nopriv_refresh_poll_list', array(&$this, 'refreshPollList'));
        add_action('wp_ajax_editpoll', array(&$this, 'editPoll'));
        add_action('wp_ajax_nopriv_editpoll_list', array(&$this, 'editPoll'));
        add_action('wp_ajax_deletepoll', array(&$this, 'deletePoll'));
        add_action('wp_ajax_nopriv_deletepoll', array(&$this, 'deletePoll'));
        add_action('wp_ajax_editpoll', array(&$this, 'editPoll'));
        add_action('wp_ajax_nopriv_editpoll', array(&$this, 'editPoll'));
        add_action('wp_ajax_update_answer', array(&$this, 'updateAnswer'));
        add_action('wp_ajax_nopriv_update_answer', array(&$this, 'updateAnswer'));
        add_action('wp_ajax_delete_answer', array(&$this, 'deleteAnswer'));
        add_action('wp_ajax_nopriv_delete_answer', array(&$this, 'deleteAnswer'));
        add_action('wp_ajax_add_answer', array(&$this, 'addAnswer'));
        add_action('wp_ajax_nopriv_add_answer', array(&$this, 'addAnswer'));
        add_action('wp_ajax_save_changes', array(&$this, 'editPollSave'));
        add_action('wp_ajax_nopriv_save_changes', array(&$this, 'editPollSave'));
        add_action('wp_ajax_submit_vote', array(&$this, 'saveVote'));
        add_action('wp_ajax_nopriv_submit_vote', array(&$this, 'saveVote'));
        add_action('wp_ajax_view_poll_result', array(&$this, 'viewPollResult'));
        add_action('wp_ajax_nopriv_view_poll_result', array(&$this, 'viewPollResult'));
        add_action('wp_ajax_view_poll_stats', array(&$this, 'getPollStats'));
        add_action('wp_ajax_nopriv_view_poll_stats', array(&$this, 'getPollStats'));
        add_action('wp_ajax_view_poll_logs', array(&$this, 'getPollLogs'));
        add_action('wp_ajax_nopriv_view_poll_logs', array(&$this, 'getPollLogs'));
    }
    
    public function init(){
        
        $this->cwpv->cwp_admin_menu_init(); 
    }
      
    public function saveNewPoll(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $answers = array();
        $vars['name'] = $this->inputSantitize($_POST['poll_name']);
        $vars['question'] = $this->inputSantitize($_POST['poll_question']);
        $vars['answer_type'] = $this->inputSantitize($_POST['poll_answer_type']);
        $vars['no_of_answers'] = $this->inputSantitize($_POST['no_of_answers']);
        $vars['s_date'] = $this->inputSantitize($_POST['s_date']);
        $vars['e_date'] = $this->inputSantitize($_POST['e_date']);
        $vars['poll_type'] = $this->inputSantitize($_POST['poll_type']);
        
        for($i=1;$i<=50;$i++) {
            if(!empty($_POST['answer'.$i])) array_push($answers, $_POST['answer'.$i]);
        }
        $this->cwpm->addNewPollDB($vars, $answers);
        die();
    }
    
    public function getPollList(){
        
        $vars = $this->cwpm->getPollsFromDB();
        return $vars;
    }
    
    public function refreshPollList(){
        
        $result = $this->cwpv->refreshPollsTable();
        die();
    }
    
    public function deletePoll(){
        check_ajax_referer($this->nonceValue, 'nonce');
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        if(sizeof($poll[0])<1) return false;
        else $result = $this->cwpm->deletePollFromDB($this->inputSantitize($_POST['pollid']));
        $this->refreshPollList();
    }
    
    public function cwpp_options(){
        
        $cwpp_options = $this->cwpm->getCWPPOptions();
	return $cwpp_options;
    }
    
    public function saveWidgetOptions(){
        
        $vars['no_of_polls'] = $this->inputSantitize($_POST['no_of_polls']);
        $vars['poll_archive'] = $this->inputSantitize($_POST['poll-archive']);
        $vars['height'] = $this->inputSantitize($_POST['widget_height']);
        $vars['width'] = $this->inputSantitize($_POST['widget_width']);
        $vars['title'] = $this->inputSantitize($_POST['widget_title']);
        $this->cwpm->saveWidgetOptionsToDB($vars);
    }
    
    public function retrievePoll(){
        
        $open_polls = array();
        $polls = $this->cwpm->getNPollsFromDB();
        $current_time = time();
        foreach($polls as $poll){
                                   
            $stimestamp = $this->getStrToTime($poll->start_date);
            $etimestamp = $this->getStrToTime($poll->end_date);
            
            if($current_time>$stimestamp && $current_time < $etimestamp){
                array_push($open_polls, $poll);
            }
        }
        return $open_polls;
        die();
    }
    
    public function retrieveArchivePoll($no_of_polls, $page_no = null){
        
        $open_polls = array();
        $polls = $this->cwpm->getPollsArchiveFromDB($this->inputSantitize($no_of_polls), $this->inputSantitize($page_no));
        return $polls;
    }
    
    public function editPoll(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        if(sizeof($poll[0])<1) return false;
        else $this->cwpv->viewEditPoll();
        die();
    }
    
    public function getPollByID(){
        
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        return $poll;
    }
    
    public function updateAnswer(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $this->cwpm->updatePollAnswerByID($this->inputSantitize($_POST['answer']), $this->inputSantitize($_POST['answer_id']));
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        if(sizeof($poll[0])<1) return false;
        else $this->cwpv->viewEditPoll();
        die();
    }
    
    public function deleteAnswer(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $this->cwpm->deletePollAnswerByID($this->inputSantitize($_POST['answer_id']));
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        if(sizeof($poll[0])<1) return false;
        else $this->cwpv->viewEditPoll();
        die();
    }
    
    public function addAnswer(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $this->cwpm->addPollAnswerIntoDB($this->inputSantitize($_POST['pollid']), $this->inputSantitize($_POST['answer']));
        $poll = $this->cwpm->getPollByIDFromDB($this->inputSantitize($_POST['pollid']));
        if(sizeof($poll[0])<1) return false;
        else $this->cwpv->viewEditPoll();
        die();
    }
    
    public function editPollSave(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $vars = array();
        $vars['name'] = $this->inputSantitize($_POST['poll_name']);
        $vars['question'] = $this->inputSantitize($_POST['poll_question']);
        $vars['answer_type'] = $this->inputSantitize($_POST['poll_answer_type']);
        $vars['no_of_answers'] = $this->inputSantitize($_POST['no_of_answers']);
        $vars['s_date'] = $this->inputSantitize($_POST['s_date']);
        $vars['e_date'] = $this->inputSantitize($_POST['e_date']);
       
        $this->cwpm->saveChangesPollDB($vars, $this->inputSantitize($_POST['pollid']));
        die();
    }
    
    public function savePollOptions(){
        
        $vars['archive_url'] = $this->inputSantitize($_POST['archive_url']);
        $vars['no_of_polls_to_display_archive'] = $this->inputSantitize($_POST['no_of_polls_to_display_archive']);
        $vars['poll_access'] = $this->inputSantitize($_POST['poll_access']);
        $vars['poll_lock'] = $this->inputSantitize($_POST['poll_lock']);
        $vars['poll_bar_color'] = $this->inputSantitize($_POST['poll_bar_color']);
        $vars['poll_bar_height'] = $this->inputSantitize($_POST['poll_bar_height']);
        $vars['poll_bg_color'] = ($_POST['poll_bg_color']);
        $vars['polls_to_display_archive'] = $this->inputSantitize($_POST['polls_to_display_archive']);
        $this->cwpm->savePollOptionsToDB($vars);
    }
    
    public function getPollAnswers($pollid){
        
        $answers = $this->cwpm->getPollAnswersFromDB($this->inputSantitize($pollid));
        return $answers;
    }
    
    public function saveVote(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $pollid = $this->inputSantitize($_POST['poll_id']);
        $expire = $this->inputSantitize($_POST['expiry']);
        $status = 0;
        $option_value = $this->cwpp_options();
        $vars['option_value'] = $option_value;
        $answerid = array();
        if($this->inputSantitize($_POST['answertype']) == 'one'){
            if(isset($_POST[$pollid])){
                $answerid[] = $this->inputSantitize($_POST[$pollid]);
                $status = 1;
            }
            
        }
        if($this->inputSantitize($_POST['answertype']) == 'multiple'){
            for($i=1; $i<=200; $i++){
                if(isset($_POST['option'.$i])){
                    $answerid[] = $_POST['option'.$i];
                    $status = 1;
                }
            }
        }
        
        if($status == 1){
            setcookie('cwppoll'.$pollid, "true", $expire, COOKIEPATH, COOKIE_DOMAIN,false,true);
            $this->cwpm->updatePollVote($pollid, $answerid);
            $answers = $this->cwpm->getPollAnswersFromDB($pollid);
            $polls = $this->cwpm->getPollByIDFromDB($pollid);
            $poll = $polls[0];
            $answer_type = $poll[0]->answer_type;
            
            $total = $poll[0]->total_votes;
            if($answer_type == "multiple") print "<b>Total Voters: </b>".$total."<br/>";
    		$total_votes = 0;
            foreach($answers as $answer){
                $total_votes = $total_votes + $answer->votes;
            }
            print "<b>".__('Total votes','cardozapolldomain').": </b>".$total_votes."<br/>";
            foreach($answers as $answer){
                
                $total = $poll[0]->total_votes;
                $votes = $answer->votes;
                if($total!=0) $width = ($votes/$total)*100;
                else $width = 0;
                
                if($poll[0]->poll_type == 'image_poll')
                    print "<div class='result-answer'><img src='".$answer->answer."' width='100' alt='".$answer->answer."'/> <br />(".$answer->votes.__(" votes", "cardozapolldomain").", ".round($width)."%)</div>";
                else
                    print "<div class='result-answer'>".$answer->answer." (".$answer->votes.__(" votes", "cardozapolldomain").", ".round($width)."%)</div>";
                ?>
                <br/>
                <div style="
                height:<?php if(!empty($option_value['bar_height'])) echo $option_value['bar_height'];
                else echo "10";?>px;
                width:<?php echo $width?>%;background-color:#<?php if(!empty($option_value['bar_color'])) echo $option_value['bar_color'];
                else echo "ECF1EF";?>"></div>
                <?php
            }    
        }
        
        die();
    }
       
    public function viewPollResult(){
        
        check_ajax_referer($this->nonceValue, 'nonce');
        $pollid = $this->inputSantitize($_POST['poll_id']);
        $polls = $this->cwpm->getPollByIDFromDB($pollid);
        $answers = $this->cwpm->getPollAnswersFromDB($pollid);
        $option_value = $this->cwpp_options();
        $poll = $polls[0];
        print "<b>".$poll[0]->question."</b><br/>";
        print "<b>".__("Total votes", "cardozapolldomain").": </b>".$poll[0]->total_votes."<br/>";
        foreach($answers as $answer){
                
                $total = $poll[0]->total_votes;
                $votes = $answer->votes;
                if($total!=0) $width = ($votes/$total)*100;
                else $width = 0;
                print "<div style='width:100%;float:left;'>".$answer->answer." (".$answer->votes.__(" votes", "cardozapolldomain").", ".intval($width)."%)</div>";
                ?>
                <hr style="float:left;height:10px;width:<?php echo $width;?>%;background-color:#4a7194;">
                <?php
            }?>
                <div id="clear">
        <?php
        die();
    }
    
    public function getStrToTime($date){
        
        $date = explode('/', $date);
        $month = $date[0];
        $day = $date[1];
        $year = $date[2];   
        $timestamp = mktime(0, 0, 0, $month, $day, $year); 
        return $timestamp;
    }
    
    public function getPollStats($vars = null){
        
        $poll_stats = $this->cwpm->pollStats();
        $current_time = time();
        $votes = array();
        $today = mktime(0,0,0,date('m'),date('d'),date('Y'));
        if(isset($_POST['arguments'])) $vars['arguments'] = $this->inputSantitize($_POST['arguments']);
        if(empty($vars)){
            if(!empty($poll_stats)){
                for($i=0;$i<7;$i++){
                    $vars['label'] = __('Last 7 days statistics', 'cardozapolldomain');
                    $from = $today - ((24*60*60)*$i);
                    if($i!=0) $to = $today - ((24*60*60)*($i-1));
                    else $to = time();
                    foreach($poll_stats as $stats){
                        if($stats->polledtime>$from && $stats->polledtime<$to){
                            if(array_key_exists($from, $votes)){
                                if(is_array($votes[$from])) $votes[$from] = $votes[$from]+1;
                                else $votes[$from] = $votes[$from]+1;           
                            }
                            else $votes[$from] = 1;
                        }
                    }
                }  
            }
            $vars['votes'] = $votes;
            return $vars;
        }
        else{
            if(!empty($poll_stats)){
                $days = $vars['arguments'];
                for($i=0;$i<$days;$i++){
                    $vars['label'] = 'Last '.$days.' days Statistics';
                    $from = $today - ((24*60*60)*$i);
                    if($i!=0) $to = $today - ((24*60*60)*($i-1));
                    else $to = time();
                    foreach($poll_stats as $stats){
                        if($stats->polledtime>$from && $stats->polledtime<$to){
                            if(array_key_exists($from, $votes)){
                                if(is_array($votes[$from])) $votes[$from] = $votes[$from]+1;
                                else $votes[$from] = $votes[$from]+1;           
                            }
                            else $votes[$from] = 1;
                        }
                    }
                }
                $label = $vars['label'];
                if(!empty($votes)){
                    $max_value = 0;

                    foreach($votes as $vote){
                        if($vote>$max_value) $max_value = $vote; 
                    }

                    if($max_value<10) $max_value = 10;

                    if(($max_value%10) != 0) $max_y_axis_value = ($max_value+10-($max_value%10));
                    else $max_y_axis_value = $max_value;
                    ?>
                    <div id="cwp-xaxis">
                        <h3><?php print $label;?></h3>
                    </div>
                    <div id="cwp-yaxis">
                        <?php 
                        for($i=0; $i<10; $i++){?>
                            <div class="cwp-yaxis-label">
                                <?php print $max_y_axis_value-(($max_y_axis_value/10)*$i);?>-
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div id="cwp-graph-content">
                        <?php
                            $bar_width = 545/sizeof($votes);
                            if($bar_width>5) $bar_width = $bar_width-1;
                            $bar_width = $bar_width;
                            $bar_height = (493/$max_y_axis_value);
                        ?>
                        <?php foreach($votes as $key=>$vote){?>
                                <div id="cwp-graph-bar" 
                                     style="width:<?php echo $bar_width;?>px;
                                            height:<?php echo $bar_height*$vote;?>px;
                                            margin-top:<?php echo 500-($bar_height*$vote);?>px;
                                            margin-left:<?php if($bar_width<5) echo '0';else echo '1';?>px" 
                                     title ="Date:<?php echo date('d/m/y',$key)." - ".$vote;?> Votes">
                                </div>
                        <?php }?>
                    </div>
                <?php
                }
            }
        }
        
        die();
    }
	
    public function getPollLogged($pollid, $userid){
        
        $status = $this->cwpm->getPollLoggedDetail($this->inputSantitize($pollid), $this->inputSantitize($userid));
        return $status;		
    }
    
    public function getPollIPLogged($pollid){
        
        $status = $this->cwpm->getPollIPStatus($this->inputSantitize($pollid));
        return $status;
    }
    
    public function getPollLogs(){
        
        $pollid = $this->inputSantitize($_POST['pollid']);
        $logs = $this->cwpm->getPollUserLogsByPollID($this->inputSantitize($pollid));
        $available = '';
        if(!empty($logs)){
        ?>
            <h3>User logs for Poll id #<?php echo $pollid;?></h3>
            <table width="100%" style="background-color: #4A7194;color:#333;">
                <thead style="background-color: #4A7194;color:#FFF;height:30px;">
                    <th><?php _e('User ID', 'cardozapolldomain');?></th>
                    <th><?php _e('User name', 'cardozapolldomain');?></th>
                    <th><?php _e('Polled time', 'cardozapolldomain');?></th>
                    <th><?php _e('IP Address', 'cardozapolldomain');?></th>
                    <th><?php _e('Answers', 'cardozapolldomain');?></th>
                </thead>
                        
            <?php
            foreach($logs as $log){
                if($log->userid > 0){
                    $available = 'yes';
                    if(!empty($log->answerid)) {
                        $getanswer = $this->cwpm->getsPollAnswerByID($log->answerid);
                        $answer = $getanswer[0]->answer;
                    }
                    else $answer = __('NULL', 'cardozapolldomain');
                    print '<tr style="background-color: #ECF1EF;height:20px;">';
                    $userinfo = get_userdata($log->userid);
                    print '<td align="center">'.$log->userid.'</td>';
                    print '<td>'.$userinfo->display_name.'</td>';
                    print '<td align="center">'.date('d-m-Y H:i:s', $log->polledtime).'</td>';
                    print '<td align="center">'.$log->ip_address.'</td>';
                    print '<td>'.$answer.'</td>';
                    print '</tr>';
                }
            }
            print '</table>';
        }
        if($available != 'yes') print '<p>'.__('No user logs found for this poll', 'cardozapolldomain').'</p>';
    }

	public function inputSantitize($string){
		$string = mysql_real_escape_string($string);
		$string = strip_tags($string);
		return $string;
	}
}

?>
