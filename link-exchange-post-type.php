<?php

//------------------------------------------------------------------------------------//

/* CUSTOM POST TYPE */
add_action( 'init', 'create_link_exchange_post_type' );

function create_link_exchange_post_type() {
	$path = plugins_url('link-exchange');
	
	register_post_type( 'link_exchange',
		array(
			'labels' => array(
				'name' => __( 'Link Exchange', 'link-exchange' ),
				'all_items' => __('All Links', 'link-exchange'),
				'singular_name' => __( 'Link', 'link-exchange' ),
				'add_new_item' => __( 'Add New Link', 'link-exchange' ),
				'edit_item' => __( 'Edit Link', 'link-exchange' ),
				'search_items' => __( 'Search Links', 'link-exchange' ),
				'not_found' => __( 'No Links found', 'link-exchange' ),
				'not_found_in_trash' => __( 'No Links found in Trash', 'link-exchange' ),
			),
		'public' => true,
		'has_archive' => true,
		'show_ui' => true,
		'menu_icon' => $path . '/link-exchange.png'
		)
	);
}

//------------------------------------------------------------------------------------//

/* ADD META BOX */
add_action( 'add_meta_boxes', 'link_exchange_add_custom_box' );

function link_exchange_add_custom_box() {
    add_meta_box(
        'link_exchange_sectionid',
        __( 'Link Exchange Details', 'link_exchange_textdomain' ),
        'link_exchange_inner_custom_box',
        'link_exchange'
    );
}

/* CUSTOM BOX CONTENT */
function link_exchange_inner_custom_box( $post ) {
  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'link_exchange_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
	$user_name = get_post_meta( $post->ID, 'link_exchange_user_name', true );
	$user_email = get_post_meta( $post->ID, 'link_exchange_user_email', true );
		
  $landing_page_url = get_post_meta( $post->ID, 'link_exchange_landing_page_url', true );
  $site_banner_url = get_post_meta( $post->ID, 'link_exchange_site_banner_url', true );
  $reciprocal_link_url = get_post_meta( $post->ID, 'link_exchange_reciprocal_link_url', true );
  $notes = get_post_meta( $post->ID, 'link_exchange_notes', true );
  
  if($site_banner_url != ""){
  	list($width, $height, $type, $attr) = getimagesize($site_banner_url);
  }
  
  ?>
  <table cellpadding="5">
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_landing_page_url"><?php _e('User Name', 'link-exchange'); ?></label>
  		</td>
	  	<td>
	  		<input type="text" id="link_exchange_user_name" name="link_exchange_user_name" value="<?php echo esc_attr($user_name); ?>" size="50" disabled="disabled" />
	  	</td>
  	</tr>
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_user_email"><?php _e('User Email', 'link-exchange'); ?></label>
  		</td>
	  	<td>
	  		<input type="text" id="link_exchange_user_email" name="link_exchange_user_email" value="<?php echo esc_attr($user_email); ?>" size="50" disabled="disabled" />
	  	</td>
  	</tr>
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_landing_page_url"><?php _e('Suggested URL', 'link-exchange'); ?></label>
  		</td>
	  	<td>
	  		<input type="text" id="link_exchange_landing_page_url" name="link_exchange_landing_page_url" value="<?php echo esc_attr($landing_page_url); ?>" size="70" />
	  		<br/>
	  		<span style="font-style: italic; font-size: 80%;"><?php _e('This is a textbox so you can fix URLs (missing "http://" etc.)'); ?></span>
	  	</td>
  	</tr>
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_site_banner_url"><?php _e('Banner', 'link-exchange'); ?></label>
  		</td>
  		<td>
	  		<input type="text" id="link_exchange_site_banner_url" name="link_exchange_site_banner_url" value="<?php echo esc_attr($site_banner_url); ?>" size="70" />
  			<!--<a href="<?php echo esc_attr($site_banner_url); ?>" target="_blank"><?php _('Go'); ?></a><?php  echo ' ('.$width.'x'.$height.')'; ?>-->
	  		<br/>
	  		<span style="font-style: italic; font-size: 80%;"><?php _e('This is a textbox so you can fix URLs (missing "http://" etc.)'); ?></span>
  			<br/>
  			<img src="<?php echo esc_attr($site_banner_url); ?>" />
  		</td>
  	</tr>
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_reciprocal_link_url"><?php _e('Reciprocal URL', 'link-exchange'); ?></label>
  		</td>
  		<td>
	  		<input type="text" id="link_exchange_reciprocal_link_url" name="link_exchange_reciprocal_link_url" value="<?php echo esc_attr($reciprocal_link_url); ?>" size="70" />
  			<a href="<?php echo esc_attr($reciprocal_link_url); ?>" target="_blank"><?php _e('Check', 'link-exchange'); ?></a>
	  		<br/>
	  		<span style="font-style: italic; font-size: 80%;"><?php _e('This is a textbox so you can fix URLs (missing "http://" etc.)'); ?></span>
  		</td>
  	</tr>
  	<tr>
  		<td width="200" valign="top">
  			<label for="link_exchange_notes"><?php _e('Notes', 'link-exchange'); ?></label>
  		</td>
  		<td>
  			<textarea name="link_exchange_notes" cols="70"><?php echo esc_attr($notes); ?></textarea>
  		</td>
  	</tr>
  </table>
  <?php
}

//------------------------------------------------------------------------------------//

/* SAVE POST */
add_action( 'save_post', 'link_exchange_save_postdata' );

function link_exchange_save_postdata( $post_id ) {

  // First we need to check if the current user is authorised to do this action. 
	if ( 'link_exchange' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
        	return;
	}
	else {
		return;
	}

  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['link_exchange_noncename'] ) || ! wp_verify_nonce( $_POST['link_exchange_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Thirdly we can save the value to the database

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  
  //sanitize user input
  $landing_page_url = sanitize_text_field( $_POST['link_exchange_landing_page_url'] );
  $site_banner_url = sanitize_text_field( $_POST['link_exchange_site_banner_url'] );
  $reciprocal_link_url = sanitize_text_field( $_POST['link_exchange_reciprocal_link_url'] );
  $notes = sanitize_text_field( $_POST['link_exchange_notes'] );

  // Do something with $mydata 
  // either using 
  add_post_meta($post_ID, 'link_exchange_landing_page_url', $landing_page_url, true) or
  update_post_meta($post_ID, 'link_exchange_landing_page_url', $landing_page_url);

  add_post_meta($post_ID, 'link_exchange_site_banner_url', $site_banner_url, true) or
  update_post_meta($post_ID, 'link_exchange_site_banner_url', $site_banner_url);

  add_post_meta($post_ID, 'link_exchange_reciprocal_link_url', $reciprocal_link_url, true) or
  update_post_meta($post_ID, 'link_exchange_reciprocal_link_url', $reciprocal_link_url);

  add_post_meta($post_ID, 'link_exchange_notes', $notes, true) or
  update_post_meta($post_ID, 'link_exchange_notes', $notes);

  // or a custom table (see Further Reading section below)
}

?>