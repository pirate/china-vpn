<?php
/*
 * Filename: CWPViewCreatePoll.class.php
 * This is the class file which will have all the markup for the 
 * user interface to create a new poll.
 */
class CWPViewCreateImagePoll{

    public function __construct() {
       	?>
        <form id="image-create-poll" name="create_poll">
        	<table>
        		<tr>
        			<td><h3><?php _e('Add New Image Poll', 'cardozapolldomain');?></h3></td>
        			<td></td>
        		</tr>
        		<tr>
        			<td><?php _e('Poll Friendly Name', 'cardozapolldomain');?>*</td>
        			<td><input id="image-poll-name" name="poll_name" style="width:350px;" type="text" value="" /></td>
        		</tr>
        		<tr>
        			<td><?php _e('Poll Question', 'cardozapolldomain');?>*</td>
        			<td><input id="image-poll-question" name="poll_question" style="width:350px;" type="text" value="" /></td>
        		</tr>
        		<tr>
        			<td><h3><?php _e('Poll Answers', 'cardozapolldomain');?></h3></td>
        			<td></td>
        		</tr>
        	</table>
        	<input id="add-answer" onclick="javascript:appendAnswers()" type="button" value="<?php _e('Add answer', 'cardozapolldomain');?>"/>
                        <input id="remove-answer" 
                               onblur="javascript:setBorderDefault('remove-answer');" 
                               onfocus="javascript:setBorder('remove-answer');" 
                               onclick="javascript:removeAnswers()"
                               type="button" value="<?php _e('Remove Answer', 'cardozapolldomain');?>"/>
        	<table class="answers">
        		<tr>
        			<td><?php _e('Answer', 'cardozapolldomain');?> 1* </td>
        			<td><input id="ans1" name="answer1" type="text" value="" style="width:350px;" /></td>
        		</tr>
        		<tr>
        			<td><?php _e('Answer', 'cardozapolldomain');?> 2*</td>
        			<td><input id="ans2" name="answer2" type="text" value="" style="width:350px;" /></td>
        		</tr>
        	</table>
        	<table>
        		<tr>
        			<td><h3><?php _e('Poll Answer type', 'cardozapolldomain');?></h3></td>
        			<td></td>
        		</tr>
        		<tr>
        			<td><?php _e('Allow users to select', 'cardozapolldomain');?>*</td>
        			<td>
        				<select name="poll_answer_type" id="image-poll-answer-type"
	                            onblur="javascript:setBorderDefault('poll-answer-type');" 
	                            onchange="javascript:showanswers(this.value)"
	                            onfocus="javascript:setBorder('poll-answer-type');">
	                        <option value="one">Only one answer</option>
	                        <option value="multiple">More than one answer</option>
	                    </select>
                    </td>
        		</tr>
        		<tr id="nanswers" style="display:none">
        			<td><?php _e('No of answers to allow', 'cardozapolldomain');?>*</td>
        			<td><input id="image-no-of-answers" style="width:40px" name="no_of_answers" type="text" value="" /></td>
        		</tr>
        		<tr>
        			<td><h3><?php _e('Poll Start/End Date', 'cardozapolldomain');?></h3></td>
        			<td></td>
        		</tr>
        		<tr>
        			<td><?php _e('Start date', 'cardozapolldomain');?>*</td>
        			<td><input id="image_start_date" type="text" name="s_date" style="width:100px;"/> <b><?php _e('Format', 'cardozapolldomain');?>: </b> mm/dd/yyyy</td>
        		</tr>
        		<tr>
        			<td><?php _e('End date', 'cardozapolldomain');?>*</td>
        			<td><input id="image_end_date" type="text" name="e_date" style="width:100px;"/> <b><?php _e('Format', 'cardozapolldomain');?>: </b> mm/dd/yyyy</td>
        		</tr>
        		<tr height="50">
        			<td></td>
        			<td><input type="hidden" name="action" value="save_poll" />
                    <input type="hidden" name="poll_type" value="image_poll" />
                    <input id="add-answer" onclick="javascript:validateAddNewImagePollForm()"
                           type="button" value="<?php _e('Save', 'cardozapolldomain');?>" /></td>
        		</tr>
        	</table>
        </form>
        <?php
    }
}
?>