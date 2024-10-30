<?php
/*
// create custom plugin settings menu
add_action('admin_menu', 'create_link_exchange_settings_menu');

function create_link_exchange_settings_menu() {

    //create new options page
    add_options_page('Link Exchange', 'Link Exchange', 'manage_options', 'link_exchange_settings', 'link_exchange_settings_page');
    
    //call register settings function
    add_action('admin_init', 'register_link_exchange_settings');
}


function register_link_exchange_settings() {
    //register our settings
    register_setting('link_exchange_settings', 'link_exchange_parent_page_id');
}
*/


add_action('admin_menu', 'register_link_exchange_submenu_page');
function register_link_exchange_submenu_page() {
	add_submenu_page( 'edit.php?post_type=link_exchange', __('Link Exchange Settings'), __('Settings'), 'edit_posts', 'link_exchange_settings', 'link_exchange_settings_page' ); 
}

function link_exchange_settings_page() {
	if(isset($_POST['link_exchange_submit_settings'])) {
		  add_option('link_exchange_use_captcha', $_POST['link_exchange_use_captcha']) or update_option('link_exchange_use_captcha', $_POST['link_exchange_use_captcha']);
		  add_option('link_exchange_captcha_private_key', $_POST['link_exchange_captcha_private_key']) or update_option('link_exchange_captcha_private_key', $_POST['link_exchange_captcha_private_key']);
		  add_option('link_exchange_captcha_public_key', $_POST['link_exchange_captcha_public_key']) or update_option('link_exchange_captcha_public_key', $_POST['link_exchange_captcha_public_key']);
		  add_option('link_exchange_email_admin', $_POST['link_exchange_email_admin']) or update_option('link_exchange_email_admin', $_POST['link_exchange_email_admin']);
	}
?>
<div class="wrap">
	<div id="link-exchange-icon" class="icon32" style="background-image: url('<?php echo plugins_url('link-exchange').'/link-exchange-32.png'; ?>')"><br></div>
	<h2><?php _e('Link Exchange Settings', 'link-exchange'); ?></h2>
	<form method="post" action="<?php echo get_permalink(); ?>">
		<?php settings_fields('link_exchange_settings'); ?>
		<table class="form-table">
			<tr><th colspan="2"><b><?php _e('Privacy', 'link-exchange'); ?></b></th></tr>
			<tr>
				<td colspan="2">
					<?php _e('By enabling "Only Registered Users Can Apply" gives you more control on who is applying and avoids SPAM, but many don\' want to spend time registering.', 'link-exchange'); ?><br>
					<?php _e('Privacy is available in the', 'link-exchange'); ?> <a href="http://wppluginspool.com/downloads/link-exchange/">PRO</a>. <?php _e('version', 'link-exchange'); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Visibility', 'link-exchange'); ?>
				</th>
				<td>
					<input type="checkbox" name="link_exchange_privacy" value="private" disabled="disabled" <?php echo get_option('link_exchange_privacy') == 'private' ? 'checked="checked"' : ''; ?> /><span class="description"><?php echo ' '.__('Only Registered Users Can Apply', 'link-exchange'); ?></span>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><th colspan="2"><b>ReCaptcha</b></th></tr>
			<tr>
				<td colspan="2"><?php _e('This section applies only if you disable the "Only Registered Users Can Apply" option AND the user is not logged in.', 'link-exchange'); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Use Captcha', 'link-exchange'); ?>
				</th>
				<td>
					<input type="checkbox" name="link_exchange_use_captcha" value="yes" <?php echo get_option('link_exchange_use_captcha') == 'yes' ? 'checked="checked"' : ''; ?> /><span class="description"><?php echo ' '.__('Check if you want to use captcha validation in the Link Exchange form', 'link-exchange'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Your Private Key', 'link-exchange'); ?>
				</th>
				<td>
					<input type="text" name="link_exchange_captcha_private_key" value="<?php echo get_option('link_exchange_captcha_private_key'); ?>" style="width: 200px;" /><span class="description"><?php echo ' '.__('Click', 'link-exchange').' <a href="https://www.google.com/recaptcha/admin/create" target="_blank">'.__('here', 'link-exchange').'</a> '.__('to obtain keys', 'link-exchange'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Your Public Key', 'link-exchange'); ?>
				</th>
				<td>
					<input type="text" name="link_exchange_captcha_public_key" value="<?php echo get_option('link_exchange_captcha_public_key'); ?>" style="width: 200px;" /><span class="description"><?php echo ' '.__('Click', 'link-exchange').' <a href="https://www.google.com/recaptcha/admin/create" target="_blank">'.__('here', 'link-exchange').'</a> '.__('to obtain keys', 'link-exchange'); ?></span>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><th colspan="2"><b><?php _e('Style', 'link-exchange'); ?></b></th></tr>
			<tr>
				<td colspan="2"><?php _e('Styling is available in the', 'link-exchange'); ?> <a href="http://wppluginspool.com/downloads/link-exchange/">PRO</a> <?php _e('version', 'link-exchange'); ?>.</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Links Border', 'link-exchange'); ?>
				</th>
				<td>
					#<input type="text" name="link_exchange_link_border" disabled="disabled" value="CCCCCC" style="width: 200px;" /><span class="description"><?php _e(' CSS color code (Example: "F5F5F5" for light gray)'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Alternate Rows (even)', 'link-exchange'); ?>
				</th>
				<td>
					#<input type="text" name="link_exchange_alternate_rows_even" disabled="disabled" value="FFFFFF" style="width: 200px;" /><span class="description"><?php _e(' CSS color code (Example: "FFFFFF" for white)'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Alternate Rows (odd)', 'link-exchange'); ?>
				</th>
				<td>
					#<input type="text" name="link_exchange_alternate_rows_odd" disabled="disabled" value="FAFAFA" style="width: 200px;" /><span class="description"><?php _e(' CSS color code (Example: "F5F5F5" for light gray)'); ?></span>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><th colspan="2"><b><?php _e('Misc', 'link-exchange'); ?></b></th></tr>
			<tr valign="top">
				<th scope="row"> <?php _e('Notification', 'link-exchange'); ?>
				</th>
				<td>
					<input type="checkbox" name="link_exchange_email_admin" value="yes" <?php echo get_option('link_exchange_email_admin') == 'yes' ? 'checked="checked"' : ''; ?> /><span class="description"><?php echo ' '.__('Email admin on new Link Exchange requests', 'link-exchange'); ?></span>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" name="link_exchange_submit_settings" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
<?php
}

?>