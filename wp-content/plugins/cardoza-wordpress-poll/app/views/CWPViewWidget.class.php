<?php

/*
 * Filename : CWPViewWidget.class.php
 * This will display the options of widget for poll
 */
class CWPViewWidget{
    
    function __construct() {
        $controller = new CWPController();
		if(isset($_POST['save-widget-opt'])) $controller->saveWidgetOptions();
        $opts = $controller->cwpp_options();
        ?>
        <form method="post" action="">
        	<table>
        		<tr>
        			<td><h3><?php _e('Widget options','cardozapolldomain');?></h3></td>
        			<td></td>
        		</tr>
        		<tr>
        			<td><?php _e('Title','cardozapolldomain');?></td>
        			<td><input id="widget-title" style="width: 300px;" name="widget_title" type="text" value="<?php if(!empty($opts['title'])) print $opts['title'];?>" /></td>
        		</tr>
        		<tr>
        			<td><?php _e('Display Older Polls Link','cardozapolldomain');?>*</td>
        			<td>
        				<select name="poll-archive" style="width:75px;">
		                    <option value="yes"
		                    <?php
		                        if(!empty($opts['archive']) && $opts['archive']=='yes') echo " selected";
		                    ?>
		                    ><?php _e('Yes','cardozapolldomain');?></option>
		                    <option value="no"
		                    <?php
		                        if(!empty($opts['archive']) && $opts['archive']=='no') echo " selected";
		                    ?>
		                    ><?php _e('No','cardozapolldomain');?></option>
		                </select>
        			</td>
        		</tr>
        		<tr>
        			<td><?php _e('Select the latest number of polls to be displayed','cardozapolldomain');?>*</td>
        			<td>
        				<select name="no_of_polls" style="width:75px;">
		                    <?php
		                        for($i=1; $i<=10; $i++){
		                            echo "<option value=".$i;
		                            if(!empty($opts['no_of_polls']) && $opts['no_of_polls']==$i) echo " selected";
		                            echo ">".$i."</option>";
		                        }
		                    ?>
		                </select>
        			</td>
        		</tr>
        		<tr>
        			<td><?php _e('Width','cardozapolldomain');?></td>
        			<td><input id="widget-width" style="width: 50px;" name="widget_width" type="text" value="<?php if(!empty($opts['width'])) print $opts['width'];?>" />px</td>
        		</tr>
        		<tr>
        			<td><?php _e('Height','cardozapolldomain');?></td>
        			<td><input id="widget-height" style="width: 50px;" name="widget_height" type="text" value="<?php if(!empty($opts['height'])) print $opts['height'];?>" />px</td>
        		</tr>
        		<tr height="50">
        			<td></td>
        			<td><input name="save-widget-opt" type="submit" value="<?php _e('Save','cardozapolldomain');?>"/></td>
        		</tr>
        	</table>
		</form>
<?php
    }
}
?>
