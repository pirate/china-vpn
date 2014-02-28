<?php
/*
 * Filename: CWPViewCreatePoll.class.php
 * This is the class file which will have all the markup for the 
 * user interface to create a new poll.
 */

class CWPViewCreatePoll{

    public function __construct() {?>
    
            <form id="create-poll" name="create_poll">
            	<table>
            		<tr>
            			<td><h3><?php _e('Add New Poll', 'cardozapolldomain');?></h3></td>
            			<td></td>
            		</tr>
            		<tr>
            			<td><?php _e('Poll Friendly Name','cardozapolldomain');?>*</td>
            			<td><input id="poll-name" name="poll_name" style="width:350px;" type="text" value="" /></td>
            		</tr>
            		<tr>
            			<td><?php _e('Poll Question','cardozapolldomain');?>*</td>
            			<td><input id="poll-question" name="poll_question" style="width:350px;" type="text" value="" /></td>
            		</tr>
            		<tr>
            			<td><h3><?php _e('Poll Answers','cardozapolldomain');?></h3></td>
            			<td></td>
            		</tr>
           	 	</table>
           	 	<input id="add-answer" 
                               onclick="javascript:appendAnswers()"
                               type="button" value="<?php _e('Add answer','cardozapolldomain');?>"/>
                <input id="remove-answer"  
                       onclick="javascript:removeAnswers()"
                       type="button" value="<?php _e('Remove Answer','cardozapolldomain');?>"/>
           	 	<table class="answers">
            		<tr>
            			<td><?php _e('Answer','cardozapolldomain');?> 1*</td>
            			<td><input id="ans1" name="answer1" type="text" value="" style="width:350px;" /></td>
            		</tr>
            		<tr>
            			<td><?php _e('Answer','cardozapolldomain');?> 2*</td>
            			<td><input id="ans2" name="answer2" type="text" value="" style="width:350px;" /></td>
            		</tr>
            	</table>
            	<table>
            		<tr>
            			<td><h3><?php _e('Poll Answer type','cardozapolldomain');?></h3></td>
            			<td></td>
            		</tr>
            		<tr>
            			<td><?php _e('Allow users to select','cardozapolldomain');?>* </td>
            			<td>
            				<select name="poll_answer_type" id="poll-answer-type" onchange="javascript:showanswers(this.value)">
		                        <option value="one"> <?php _e('Only one answer','cardozapolldomain');?></option>
		                        <option value="multiple"><?php _e('More than one answer','cardozapolldomain');?></option>
		                    </select>
            			</td>
            		</tr>
            		<tr id="nanswers" style="display:none">
                        <td><?php _e('No of answers to allow','cardozapolldomain');?>*</td>
                        <td><input id="no-of-answers" style="width:40px" name="no_of_answers" type="text" value="" /></td>
                    </tr>
            		<tr>
            			<td><h3><?php _e('Poll Start/End Date','cardozapolldomain');?></h3></td>
            			<td></td>
            		</tr>
            		<tr>
            			<td><?php _e('Start date','cardozapolldomain');?>*</td>
            			<td><input id="start_date" type="text" name="s_date" style="width:100px;"/> <b><?php _e('Format','cardozapolldomain');?>: </b> mm/dd/yyyy</td>
            		</tr>
            		<tr>
            			<td><?php _e('End date','cardozapolldomain');?>*</td>
            			<td><input id="end_date" type="text" name="e_date" style="width:100px;"/> <b><?php _e('Format','cardozapolldomain');?>: </b> mm/dd/yyyy</td>
            		</tr>
            		<tr height="50">
            			<td></td>
            			<td><input type="hidden" name="action" value="save_poll" />
		                    <input id="add-answer" 
		                           onclick="javascript:validateAddNewPollForm()"
		                           onblur="javascript:setBorderDefault('add-answer');" 
		                           onfocus="javascript:setBorder('add-answer');"
		                           type="button" value="<?php _e('Save','cardozapolldomain');?>" />
		                </td>
            		</tr>
            	</table>
            </form>
        </div> 
        <?php
    }

}
?>