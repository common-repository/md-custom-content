<?php
/*
Plugin Name: MD Custom Conetent
Plugin URI: http://dungla.com/md_custom_content
Description: Custom content after post. Support all HTML tags 
Author: Mukesh Dak
Version: 1.0
Author URI: http://casanova.vn
*/

// Show Extra Menu in Admin Menu
add_action('admin_menu', 'mdcc_menu');

// What to do when the plugin is activated?
register_activation_hook(__FILE__,'mdcc_plugin_install');
// File and then Function

// What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'mdcc_plugin_remove' );
// File and then Function


// Delete all the settings added.
function mdcc_plugin_remove()
{
	delete_option('mdcc_html_code_before');
	delete_option('mdcc_html_code_after');
	delete_option('mdcc_position');
}


function mdcc_plugin_install()
{
	add_option('mdcc_html_code_before', 'This content will be shown before all post');
	add_option('mdcc_html_code_after', 'This content will be shown after all post');
	add_option('mdcc_position','both');
}

function mdcc_menu() // This Function will create a menu
{
	add_menu_page( 
		__('MD Custom Content',''), // Page Title
		__('Custom content',''), // Menu Title
		8,											  // Capability
		basename(__FILE__),							  // Menu Slug ( Should Be unique )
		'my_setting',							  // Function to execute.
		plugins_url(basename(dirname(__file__)).'/icons/star.png'),  // Menu icon
		101											// Menu Position                                               
	);
}

function my_setting(){
		if($_POST['status_submit']==1){			
			update_option('mdcc_html_code_before',(stripslashes($_POST['mdcc_html_code_before'])));
			update_option('mdcc_html_code_after', (stripslashes($_POST['mdcc_html_code_after'])));
			update_option('mdcc_position',trim($_POST['mdcc_position']));
			
			echo '<div id="message" class="updated fade"><p>Settings saving successful !</p></div>';
		}
		elseif($_POST['status_submit']==2)
		{	// Reset to default
			update_option('mdcc_html_code_before', 'This content will be shown before all posts');
			update_option('mdcc_html_code_after', 'This content will be shown after all posts');
			update_option('mdcc_position','both');
			echo '<div id="message" class="updated fade"><p>Settings reset successful !</p></div>';
		}

	?>
	<h2>Custom content after and before post Setting</h2>
	<form method="post" id="mdcc_options">

    	<input type="hidden" name="status_submit" id="status_submit" value="2"  />

		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
        	<tr valign="top"> 
				<td  scope="row" width="150">Display position</td> 
				<td scope="row">
					<select name="mdcc_position">
						<option value="before" <?php if(get_option('mdcc_position')=='before') echo "selected"; ?> />Only Before the post</option>
						<option value="after" <?php if(get_option('mdcc_position')=='after') echo "selected"; ?> />Only After the post</option>
						<option value="both" <?php if(get_option('mdcc_position')=='both') echo "selected"; ?> />Both before and after post</option>	
						<option value="one" <?php if(get_option('mdcc_position')=='none') echo "selected"; ?> />Do not Appent or Prepentd text</option>	
					</select>
				</td> 
			</tr>
            
            <tr valign="top"> 
				<td  scope="row">HTML Code Before Post:<br/><small>Put HTML code here</small></td> 
				<td scope="row">			
					<textarea name="mdcc_html_code_before" rows="7" cols="80"><?php echo (get_option('mdcc_html_code_before'));?></textarea>	
				</td> 
			</tr>
			<tr valign="top"> 
				<td  scope="row">HTML Code After Post:<br/><small>Put HTML code here</small></td> 
				<td scope="row">			
					<textarea name="mdcc_html_code_after" rows="7" cols="80"><?php echo (get_option('mdcc_html_code_after'));?></textarea>	
				</td> 
			</tr>
             <tr valign="top"> 
				<td  scope="row"></td> 
				<td scope="row">			
					<input type="button" name="save" onclick="document.getElementById('status_submit').value='1'; document.getElementById('mdcc_options').submit();" value="Save settings" class="button-primary" />
				</td> 
			</tr>
            <tr><td colspan="2"><br /><br /></td></tr>
            <tr valign="top"> 
				<td  scope="row"></td> 
				<td scope="row">			
					<input type="button" name="reset" onclick="document.getElementById('status_submit').value='2'; document.getElementById('mdcc_options').submit();" value="Reset to default setting" class="button" />
				</td> 
			</tr>
		</table>
        
	</form>	
	<?php
}

function custom_content_after_post($content){
	if (is_single()) 
	{ 
		if(get_option('mdcc_position')=="after"){
	    	$content .= (get_option('mdcc_html_code_after'));
		}
		elseif(get_option('mdcc_position')=="before"){
			$content = (get_option('mdcc_html_code_before')).$content;	
		}
		elseif(get_option('mdcc_position')=="both"){
			$content = (get_option('mdcc_html_code_before')).$content.(get_option('mdcc_html_code_after'));		
		}
	}
	return $content;	
}

add_filter( "the_content", "custom_content_after_post" );
?>