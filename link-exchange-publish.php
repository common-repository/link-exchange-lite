<?php
add_action('save_post', 'link_exchange_publish_link');

function link_exchange_publish_link ($post_id) {
	
	if(get_post_type( $post_id ) == 'link_exchange' && get_post_status( $post_id ) == 'publish') {
		
		if(!get_post_meta($post_id, 'link_exchange_accepted_once', true)) {
			//first time publish for this link
					
			//----------------------------------------------//
			//email applicant
			$user_username = get_post_meta( $post_id, 'link_exchange_user_name', true );
			$to = get_post_meta($post_id, 'link_exchange_user_email', true);
			$subject = __('Your Link Exchange Request Has Been Approved', 'link-exchange');
			$user_message = '<p>'.__("Hi ").$user_username.',</p>'.
				__('Your Link Exchange request has been approved and your link is now published.', 'link-exchange').'<br/><br/>'.
				'<a href="'.get_bloginfo('url').'"><i>'.get_bloginfo('name').'</i></a>';
			$headers = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
			
			add_filter( 'wp_mail_content_type', 'set_link_exchange_html_content_type' );
			
			wp_mail($to, $subject, $user_message, $headers );
			
			add_post_meta($post_id, 'link_exchange_accepted_once', true, true);
							
			//----------------------------------------------//
					
		}
	}
}

add_action('save_post', 'link_exchange_unpublish_link');
//add_action('delete_post', 'link_exchange_unpublish_link');

function link_exchange_unpublish_link($post_id) {
	if(get_post_type( $post_id ) == 'link_exchange' && get_post_status( $post_id ) == 'trash') {
		
		if(!get_post_meta($post_id, 'link_exchange_rejected_once', true)) {
			//first time publish for this link
					
			//----------------------------------------------//
			//email applicant
			$user_username = get_post_meta( $post_id, 'link_exchange_user_name', true );
			$to = get_post_meta($post_id, 'link_exchange_user_email', true);
			$subject = __('Your Link Exchange Request Has Been Declined', 'link-exchange');
			$user_message = '<p>'.__("Hi ").$user_username.',</p>'.
				__('Unfortunaley your Link Exchange request has been declined.', 'link-exchange').'<br/><br/>'.
				'<a href="'.get_bloginfo('url').'"><i>'.get_bloginfo('name').'</i></a>';
			$headers = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['SERVER_NAME'].'>' . "\r\n";
			
			add_filter( 'wp_mail_content_type', 'set_link_exchange_html_content_type' );
			
			wp_mail($to, $subject, $user_message, $headers );
			
			add_post_meta($post_id, 'link_exchange_rejected_once', true, true);
							
			//----------------------------------------------//
					
		}
	}
}

?>