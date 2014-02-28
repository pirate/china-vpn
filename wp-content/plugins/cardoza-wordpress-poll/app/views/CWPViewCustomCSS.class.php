<?php
/*
 * Filename: CWPViewCreatePoll.class.php
 * This is the class file which will have all the markup for the 
 * user interface to create a new poll.
 */
class CWPViewCustomCSS{

    public function __construct() {
    	if(isset($_POST['save_css_code'])) update_option('poll_custom_css_code', ltrim($_POST['css_code']))
       	?>
        <form method="post" action="">
        	<table width="99%">
        		<tr>
        			<td><h3><?php _e('Put your custom CSS code here', 'cardozapolldomain');?></h3></td>
        		</tr>
        		<tr>
        			<td>
        				<textarea name="css_code" style="float: left;width: 99%;height: 400px;resize:none;"><?php print ltrim(get_option('poll_custom_css_code'));?></textarea>
					</td>
        		</tr>
        		<tr height="50">
        			<td><input type="submit" name="save_css_code" value="<?php _e('Save', 'cardozapolldomain');?>" /></td>
        		</tr>
        	</table>
        </form>
        <?php
    }
}
?>