<?php
class CWPViewUserLogs{
    function __construct() {?>
            <div id="tab7" class="tab-content">
                <h3><?php _e('Poll User Logs','cardozapolldomain');?></h3>
                <div id="all-polls">
                <table width="100%" style="background-color: #4A7194;color:#333;">
                    <thead style="background-color: #4A7194;color:#FFF;height:30px;">
                        <th>ID</th>
                        <th><?php _e('Name','cardozapolldomain');?></th>
                        <th><?php _e('Question','cardozapolldomain');?></th>
                        <th><?php _e('Answer type','cardozapolldomain');?></th>
                        <th><?php _e('Start date','cardozapolldomain');?></th>
                        <th><?php _e('End date','cardozapolldomain');?></th>
                        <th><?php _e('Status','cardozapolldomain');?></th>
                        <th><?php _e('Total votes','cardozapolldomain');?></th>
                        <th><?php _e('View User Logs','cardozapolldomain');?></th>
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
                                $timestamp = $controller->getStrToTime($poll->end_date);
                                $current_time = time();
                                if($current_time < $timestamp) _e('Open','cardozapolldomain');
                                else  _e('Closed','cardozapolldomain');
                                ?>
                                </td>
                                <td align="center"><?php print $poll->total_votes;?></td>
                                <td align="center"><input name="polluserlogs" type="button" onclick="javascript:userlogs(<?php echo $poll->id;?>)" value="<?php _e('View User Logs','cardozapolldomain');?>"/></td>
                            </tr>
                        <?php
                        $total_votes = $total_votes +  $poll->total_votes;
                        }
                        ?>
                    </form>
                </table>
            </div>
                <br />
                <div id="poll-logs"></div>
            </div>
        </div>
<?php }
}
?>
