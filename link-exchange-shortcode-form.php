<?php

add_shortcode('link-exchange-form', 'link_exchange_shortcode_form_func');

function link_exchange_shortcode_form_func($atts) {
	return draw_link_exchange_form_public();
}

//------------------------------------------------------------------------------------//

function set_link_exchange_html_content_type()
{
	return 'text/html';
}

//------------------------------------------------------------------------------------//

function draw_link_exchange_form_public () {
	$output = '';
	
	if(get_option('link_exchange_use_captcha') == 'yes') {
		require_once('recaptchalib.php');
		
		$private_key = get_option('link_exchange_captcha_private_key');
		$public_key = get_option('link_exchange_captcha_public_key');
	}
	else{
			$private_key = null;
			$public_key = null;
	}
		
	//$user_ID = get_current_user_id();
	if ($_POST['link_exchange_submit']) {
		
		$resp = recaptcha_check_answer ($private_key,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$output = link_exchange_draw_the_public_form($public_key, __('Captcha validation failed'));
		}
		else {
			$site_name = $_POST['link_exchange_site_name'];
			$site_desc = $_POST['link_exchange_site_desc'];
			
			$new_post = array(
				'post_title' => $site_name,
				'post_content' => $site_desc,
			  	'post_status' => 'pending', 
			  	'post_type' => 'link_exchange',
			  	//'post_author' => $user_ID,
			);
			
			$new_post_id = wp_insert_post( $new_post );
			
			if($new_post_id > 0) {
				$user_name = sanitize_text_field( $_POST['link_exchange_user_name'] );
				$user_email = sanitize_text_field( $_POST['link_exchange_user_email'] );
				$landing_page_url = sanitize_text_field( $_POST['link_exchange_landing_page_url'] );
				$site_banner_url = sanitize_text_field( $_POST['link_exchange_site_banner_url'] );
				$reciprocal_link_url = sanitize_text_field( $_POST['link_exchange_reciprocal_link_url'] );
				$notes = sanitize_text_field( $_POST['link_exchange_notes'] );
				
				add_post_meta($new_post_id, 'link_exchange_user_name', $user_name, true);
				add_post_meta($new_post_id, 'link_exchange_user_email', $user_email, true);
				add_post_meta($new_post_id, 'link_exchange_landing_page_url', $landing_page_url, true);
				add_post_meta($new_post_id, 'link_exchange_site_banner_url', $site_banner_url, true);
				add_post_meta($new_post_id, 'link_exchange_reciprocal_link_url', $reciprocal_link_url, true);
				add_post_meta($new_post_id, 'link_exchange_notes', $notes, true);
				
				//----------------------------------------------//
				if(get_option('link_exchange_email_admin') == 'yes') {
					//email admin
					$user_info = get_userdata(1);
					$admin_username = $user_info->user_login;
					
					$to = get_bloginfo('admin_email');
					$subject = __('You Have a New Link Exchange Request', 'link-exchange');
					$admin_message = '<p>'.__("Hi ").$admin_username.',</p>'.
						__('a new Link Exchange URL has been submitted for your review: ', 'link-exchange').'<br/>'.
						get_edit_post_link( $new_post_id );
					$headers = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
					 
					add_filter( 'wp_mail_content_type', 'set_link_exchange_html_content_type' );
					
					wp_mail($to, $subject, $admin_message, $headers );				
				}
				
				//----------------------------------------------//
				//email applicant
				$to = $user_name;
				$subject = __('Your Link Exchange Request Has Been Received', 'link-exchange');
				$user_message = '<p>'.__("Hi ").$user_username.',</p>'.
					__('Thank you for suggesting your site with our Link Exchange program.', 'link-exchange').'<br/>'.
					__('We will review your application and if approved you will receive a notification.', 'link-exchange').'<br/><br/>'.
					'<i>'.get_bloginfo('name').'</i>';
				$headers = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
				
				add_filter( 'wp_mail_content_type', 'set_link_exchange_html_content_type' );
				
				wp_mail($to, $subject, $user_message, $headers );				
								
				//----------------------------------------------//
				
				$output	= '<p>'.__('Thank you. Your request will be reviewed. We\'ll get back to you as soon as possible.', 'link-exchange').'</p>';
			}
			else {
				$output	= '<p>'.__('An error occurred and we couldn\'t process your request.', 'link-exchange').'</p>';
			}
		}
	}
	else {
		$output = link_exchange_draw_the_public_form($public_key, null);
	}
	
	return $output;
}

function link_exchange_draw_the_public_form($public_key, $recaptchaError) {
		$output = '
<form method="post" action="'. get_permalink() .'" class="link_exchange_form">
	<table class="link_exchange_form_table">
	<tr>
		<td><label for="link_exchange_user_name">'. __('Your Name', 'link-exchange') .'</label></td>
		<td><input type="text" name="link_exchange_user_name" id="link_exchange_user_name" class="text field required" value="" title="'.__('Required', 'link-exchange').'" /></td>
	</tr>
	<tr>
		<td><label for="link_exchange_user_email">'. __('Your Email', 'link-exchange') .'</label></td>
		<td><input type="text" name="link_exchange_user_email" class="text required email" value="" title="'.__('Valid Email Required', 'link-exchange').'" /></td>
	</tr>
	<tr>
		<td><label for="link_exchange_landing_page_url">'. __('Suggested URL', 'link-exchange') .'</label></td>
		<td>
			<input type="text" name="link_exchange_landing_page_url" class="text required url" value="" title="'.__('Valid URL Required (Starts with http://)', 'link-exchange').'" />
			<br/>
			<span class="description">'. __('The landing page on your site', 'link-exchange') .'</span>
		</td>
	</tr>
	<tr>
		<td><label for="link_exchange_site_name">'. __('Site Name', 'link-exchange') .'</label></td>
		<td><input type="text" name="link_exchange_site_name" class="text required" value="" title="'.__('Required', 'link-exchange').'" /></td>
	</tr>
	<tr>
		<td><label for="link_exchange_site_desc">'. __('Site Description', 'link-exchange') .'</label></td>
		<td><textarea name="link_exchange_site_desc" class="text required" title="'.__('Required', 'link-exchange').'"></textarea></td>
	</tr>
	<tr>
		<td><label for="link_exchange_site_banner_url">'. __('Site Banner URL', 'link-exchange') .'</label></td>
		<td>
			<input type="text" name="link_exchange_site_banner_url" class="text url" value="" title="'.__('Valid URL Required (Starts with http://)', 'link-exchange').'" />
			<br/>
			<span class="description">'. __('Where do I find your banner? It has to be a 468x68 banner...', 'link-exchange') .'</span>
		</td>
	</tr>
	<tr>
		<td><label for="link_exchange_reciprocal_link_url">'. __('Reciprocal Link URL', 'link-exchange') .'</label></td>
		<td>
			<input type="text" name="link_exchange_reciprocal_link_url" class="text required url" value="" title="'.__('Valid URL Required (Starts with http://)', 'link-exchange').'" />
			<br/>
			<span class="description">'. __('This is the URL where you have previously added a link to ', 'link-exchange'). get_bloginfo('name') .'</span>
		</td>
	</tr>
	<tr>
		<td><label for="link_exchange_notes">'. __('Notes', 'link-exchange') .'</label></td>
		<td>
			<textarea name="link_exchange_notes" class="text"></textarea>
			<br/>
			<span class="description">'. __('Additional notes for the webmaster. This won\'t be published', 'link-exchange') .'</span>
		</td>
	</tr>';
	
	if(get_option('link_exchange_use_captcha') == 'yes') {
		$output .= '
	<tr>
		<td><label for="captcha">'. __('Validation', 'link-exchange') .'</label></td>
		<td>'.recaptcha_get_html($public_key).'</td>
	</tr>';
	}
	
	$output .= '
	<tr>
		<td><label for=""></label></td>
		<td><input type="submit" name="link_exchange_submit" value="'. __('Submit', 'link-exchange') .'" /></td>
	</tr>
	</table>
</form>';

	return $output;
}
?>