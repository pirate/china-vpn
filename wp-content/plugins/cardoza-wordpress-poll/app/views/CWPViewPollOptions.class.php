<?php

/*
 * Filename : CWPViewPollOptions.class.php
 * This class will create the user interface for the poll options tab.
 */
class CWPViewPollOptions extends CWPView {

    function __construct() {
        
        $controller = new CWPController();
		if(isset($_POST['save-poll-options'])) $controller->savePollOptions();
        $opts = $controller->cwpp_options();
        ?>
		<form method="post" action="">
			<table>
				<tr>
					<td><h3><?php _e("Poll Options", "cardozapolldomain");?></h3></td>
					<td></td>
				</tr>
				<tr>
					<td><?php _e('Who is allowed to vote?','cardozapolldomain');?></td>
					<td>
						<select name="poll_access" id="poll-access" onchange="javascript:showanswers(this.value)">
		                    <option value="all"
		                    <?php
		                        if(!empty($opts['poll_access']) && $opts['poll_access']=='all') echo " selected";
		                    ?>        
		                    ><?php _e('Anyone can poll','cardozapolldomain');?></option>
		                    <option value="loggedin"
		                    <?php
		                        if(!empty($opts['poll_access']) && $opts['poll_access']=='loggedin') echo " selected";
		                    ?>
		                    ><?php _e('Only logged in users can poll','cardozapolldomain');?> </option>
		                </select>
					</td>
				</tr>
				<tr>
					<td><?php _e('Lock Poll by','cardozapolldomain');?></td>
					<td>
						<select name="poll_lock" id="poll-lock">
		                    <option value="cookies"
		                    <?php
		                        print $opts['poll_lock'];
		                        if(!empty($opts['poll_lock']) && $opts['poll_lock']=='cookies') echo " selected";
		                    ?>        
		                    ><?php _e('Cookies','cardozapolldomain');?></option>
		                    <option value="ipaddress"
		                    <?php
		                        if(!empty($opts['poll_lock']) && $opts['poll_lock']=='ipaddress') echo " selected";
		                    ?>
		                    ><?php _e('IP Address','cardozapolldomain');?></option>
		                </select>
					</td>
				</tr>
				<tr>
					<td><h3><?php _e('Poll style options','cardozapolldomain');?></h3></td>
					<td></td>
				</tr>
				<tr>
					<td><?php _e('Background colour','cardozapolldomain');?></td>
					<td>#<input id="poll-bg-color" name="poll_bg_color" type="text" 
							value="<?php if(!empty($opts['poll_bg_color'])) echo $opts['poll_bg_color'];?>" /></td>
				</tr>
				<tr>
					<td><?php _e('Colour','cardozapolldomain');?></td>
					<td>#<input id="poll-bar-color" name="poll_bar_color" type="text" 
							value="<?php if(!empty($opts['bar_color'])) echo $opts['bar_color'];?>" /></td>
				</tr>
				<tr>
					<td><?php _e('Height','cardozapolldomain');?></td>
					<td><input id="poll-bar-height" name="poll_bar_height" type="text" 
							value="<?php if(!empty($opts['bar_height'])) echo $opts['bar_height'];?>" /></td>
				</tr>
				<tr>
					<td><h3><?php _e('Poll archive options','cardozapolldomain');?></h3></td>
					<td></td>
				</tr>
				<tr>
					<td><?php _e('Polls to display in the older polls page','cardozapolldomain');?></td>
					<td>
						<select style="width:250px;" name="polls_to_display_archive" id="poll-access">
                    		<option value="open" <?php if(!empty($opts['polls_to_display_archive']) && $opts['polls_to_display_archive']=='open') echo " selected";?>><?php _e('Opened Polls Only','cardozapolldomain');?></option>
                    		<option value="closed" <?php if(!empty($opts['polls_to_display_archive']) && $opts['polls_to_display_archive']=='closed') echo " selected";?>><?php _e('Closed Polls Only','cardozapolldomain');?></option>
                    		<option value="open-closed" <?php if(!empty($opts['polls_to_display_archive']) && $opts['polls_to_display_archive']=='open-closed') echo " selected";?>><?php _e('Opened and Closed Polls','cardozapolldomain');?></option>
                		</select>
                	</td>
				</tr>
				<tr>
					<td><?php _e('No of polls to display per page in the older polls','cardozapolldomain');?></td>
					<td><select name="no_of_polls_to_display_archive" id="poll-access">
		                    <?php
		                        for($i=1; $i<=50; $i++){
		                            echo "<option value=".$i;
		                            if(!empty($opts['no_of_polls_to_display_archive']) && $opts['no_of_polls_to_display_archive']==$i) echo " selected";
		                            echo ">".$i."</option>";
		                        }
		                    ?>
		                </select>
		            </td>
				</tr>
				<tr>
					<td><?php _e('Poll archive URL','cardozapolldomain');?></td>
					<td><input id="archive-url" size="100" name="archive_url" type="text" 
                			value="<?php if(!empty($opts['archive_url'])) echo $opts['archive_url'];?>" /></td>
				</tr>
				<tr>
					<td></td>
					<td><input name="save-poll-options" type="submit" value="<?php _e('Save','cardozapolldomain');?>"/></td>
				</tr>
			</table>
		</form>
<?php
    }

}
?>
