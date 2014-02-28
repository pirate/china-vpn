<?php

/*
 * Filename : CWPViewManagePolls
 * This is will create an interface to manage polls
 */

class CWPViewManagePolls{

    function init() {?>

        <div id="all-polls">
            <div id="tab1" class="tab-content">
                <div id="manage-polls">
                <input id="refresh-list" onclick="javascript:refreshPollList()"
                    type="button" value="<?php _e("Refresh Poll List", "cardozapolldomain");?>"/>
                <input id="edit-poll" class="inpt"
                    onblur="javascript:setBorderDefault('edit-poll');" 
                    onfocus="javascript:setBorder('edit-poll');" type="button" value="<?php _e("Edit a Poll", "cardozapolldomain");?>" onclick="javascript:editPoll()" />
                <input id="delete-poll" class="inpt"
                    onblur="javascript:setBorderDefault('delete-poll');" 
                    onfocus="javascript:setBorder('delete-poll');" type="button" value="<?php _e("Delete a Poll", "cardozapolldomain");?>" onclick="javascript:deletePoll()" />
                <br /><br />
                <div id="all-polls">
                <table width="100%" style="background-color: #4A7194;color:#333;">
                    <thead style="background-color: #4A7194;color:#FFF;height:30px;">
                        <th>ID</th>
                        <th><?php _e("Name", "cardozapolldomain");?></th>
                        <th><?php _e("Question", "cardozapolldomain");?></th>
                        <th><?php _e("Answer type", "cardozapolldomain");?></th>
                        <th><?php _e("Start date", "cardozapolldomain");?></th>
                        <th><?php _e("End date", "cardozapolldomain");?></th>
                        <th><?php _e("Status", "cardozapolldomain");?></th>
                        <th><?php _e("Total votes", "cardozapolldomain");?></th>
                        <th><?php _e("View Result", "cardozapolldomain");?></th>
                    </thead>
                    <form id="manage-poll">
                        <?php
                        $controller = new CWPController();
                        $polls = $controller->getPollList();
                        $total_votes = 0;
                        foreach($polls as $poll){?>

                            <tr style="background-color: #ECF1EF;height:40px;">
                                <td align="center"><?php print $poll->id;?></td>
                                <td style="padding-left:3px;"><?php print $poll->name;?></td>
                                <td style="padding-left:3px;"><?php print $poll->question;?></td>
                                <td style="padding-left:3px;"><?php print $poll->answer_type;?></td>
                                <td align="center"><?php print $poll->start_date;?></td>
                                <td align="center"><?php print $poll->end_date;?></td>
                                <td align="center">
                                <?php
                                $stimestamp = $controller->getStrToTime($poll->start_date);
                                $etimestamp = $controller->getStrToTime($poll->end_date);
                                $current_time = time();
                                if($current_time < $etimestamp && $current_time > $stimestamp) 
                                    echo __("Open", "cardozapolldomain");
                                elseif($current_time < $stimestamp) 
                                    echo __("Not yet opened", "cardozapolldomain");
                                else 
                                    echo __("Closed", "cardozapolldomain");
                                ?>
                                </td>
                                <td align="center"><?php print $poll->total_votes;?></td>
                                <td align="center"><input name="view_poll_results" type="button" onclick="javascript:viewPollResults(<?php print $poll->id;?>)" value="<?php _e("View Result", "cardozapolldomain");?>"/></td>
                            </tr>
                        <?php
                        $total_votes = $total_votes +  $poll->total_votes;
                        }
                        ?>
                    </form>
                </table>
                <br />
                <table>
                	<tr>
                		<td><b><?php _e("Total voters", "cardozapolldomain");?> :</b></td>
                		<td><?php print $total_votes;?></td>
                	</tr>
                </table>
            </div>
            </div>
        </div>
        </div>
<?php }
}
?>
