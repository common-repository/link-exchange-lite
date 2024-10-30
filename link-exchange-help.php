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


add_action('admin_menu', 'register_link_exchange_submenu_help_page');
function register_link_exchange_submenu_help_page() {
	add_submenu_page( 'edit.php?post_type=link_exchange', __('Link Exchange Help', 'link-exchange'), __('Help'), 'edit_posts', 'link_exchange_help', 'link_exchange_help_page' ); 
}

function link_exchange_help_page() {
?>
<div class="wrap">
	<div id="link-exchange-icon" class="icon32" style="background-image: url('<?php echo plugins_url('link-exchange').'/link-exchange-32.png'; ?>')"><br></div>
	<h2><?php _e('Link Exchange Help', 'link-exchange'); ?></h2>
	<h3><?php _e('How to Use the Link Exchange Plugin', 'link-exchange'); ?></h3>
	<p>
		<?php _e('Link Exchange provides you with two shortcodes:'); ?>
		<ol>
			<li><b>[link-exchange-form]</b>: <?php _e('displays the application form where the visitors can submit their site for your review', 'link-exchange'); ?></li>
			<li><b>[link-exchange-list]</b>: <?php _e('displays a list of approved links', 'link-exchange'); ?></li>
		</ol>
	</p>
	<h3><?php _e('Link Exchange Rules', 'link-exchange'); ?></h3>
	<?php _e('Link Exchange is designed so that applicants must first add a link to your site so that you can verify it and accept their request.', 'link-exchange'); ?>
	<ol>
		<li><?php _e('Only registered users can apply, or anybody, depending on your settings', 'link-exchange'); ?></li>
		<li><?php _e('When somone applies, the site admin receives an email notification (that can be turned off)', 'link-exchange'); ?></li>
		<li><?php _e('New applications are set to "Pending"', 'link-exchange'); ?></li>
		<li><?php _e('To approve an application you need to publish it', 'link-exchange'); ?></li>
		<li><?php _e('When you publish a link, the applicant receives an email notification', 'link-exchange'); ?></li>
		<li><?php _e('When you trash a link, the applicant receives an email notification', 'link-exchange'); ?></li>
	</ol>
</div>
<?php
}

?>