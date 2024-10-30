<?php
add_shortcode('link-exchange-list', 'link_exchange_shortcode_list_func');

function link_exchange_shortcode_list_func ($atts) {
	$args = array (
		'post_type' => 'link_exchange',
		'post_status' => 'publish'
	);
	$links_array = get_posts( $args );
	
	if(count($links_array) > 0) {
		$output = '
			<table class="link_exchange_links_table" border="0" cellspacing="15">
		';
	
		foreach( $links_array as $link ) : setup_postdata($link);
			$landing_page_url = get_post_meta($link->ID, 'link_exchange_landing_page_url', true);
			$site_banner_url = get_post_meta( $link->ID, 'link_exchange_site_banner_url', true );
	
			$output .= '
				<tr>
					<td>
		          		';
			
			if($site_banner_url != '') {
				$output .= '
		          		<img class="link_exchange_banner" src="'.$site_banner_url.'" /><br/>';
			}
			
			$output .= '<div class="link_exchange_title"><a href="'. $landing_page_url.'"><span><b>'.$link->post_title.'</b></span></a></div>
		        		<p>'.$link->post_content.'</p>
		        	</td>
		    </tr>';
			
		endforeach;
	
		$output .= '
			</table>
		';
	}
	else {
		$output = '<p>'.__('There are no links yet', 'link-exchange').'.</p>';
	}
	
	return $output;
}

?>