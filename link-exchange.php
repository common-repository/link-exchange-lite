<?php
/*
Link Exchange WordPress Plugin
Copyright (C) 2013 Cristian Merli

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
 Plugin Name: Link Exchange
 Plugin URI: http://wppluginspool.com/downloads/link-exchange/
 Description: Webmasters that want to exchange links with your site can submit a request form after adding a link to your site on their site first. Then you can evaluate the request and accept it, and a link to their site will automatically appear in a page that you choose.
 Version: 1.0.0
 Author: Cristian Merli
 Author URI: http://wppluginspool.com/
 */

add_action('plugins_loaded', 'link_exchange_init');

function link_exchange_init () {
	load_plugin_textdomain('link-exchange', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}
 
/* SETTINGS PAGE */
require_once ('link-exchange-settings.php');

/* HELP PAGE */
require_once ('link-exchange-help.php');

/* SETTINGS PAGE */
require_once ('link-exchange-settings.php');

//* CUSTOM POST TYPE */
require_once ('link-exchange-post-type.php');

/* SHORTCODE-FORM */
require_once ('link-exchange-shortcode-form.php');

/* SHORTCODE-LIST */
require_once ('link-exchange-shortcode-list.php');

/* PUBLISH/ACCEPT APPLICATION */
require_once ('link-exchange-publish.php');

//------------------------------------------------------------------------------------//

/* STYLESHEET */
add_action('wp_enqueue_scripts', 'link_exchange_style_func');

function link_exchange_style_func() {
    $path = plugins_url('link-exchange');
    
    //for multisite upload limit filter
    if (is_multisite()) {
        require_once ABSPATH.'/wp-admin/includes/ms.php';
    }
    
    require_once ABSPATH.'/wp-admin/includes/template.php';
	
	wp_enqueue_style('custom-style-global', $path.'/link-exchange-style.css');
	wp_enqueue_style('custom-style-easy', $path.'/easy-framework/easy.css');
}

//------------------------------------------------------------------------------------//

// Add hook for admin <head></head>
//add_action('admin_head', 'my_custom_js');

// Add hook for front-end <head></head>
add_action('wp_head', 'link_exchange_js');

function link_exchange_js () {
    $path = plugins_url('link-exchange');
	
    echo '<script type="text/javascript" src="'.$path.'/easy-framework/easy.js"></script>';
    echo '<script type="text/javascript" src="'.$path.'/easy-framework/main.js"></script>';

}

add_action( 'admin_head', 'link_exchange_admin_inline_style' );

function link_exchange_admin_inline_style() {
    ?>
    <style type="text/css" media="screen">
		#icon-edit.icon32-posts-link_exchange {
            background: url(<?php echo plugins_url('link-exchange') ?>/link-exchange-32.png) no-repeat;
        }

		#icon-edit.icon32-posts-link_exchange:hover .wp-menu-image, #icon-edit.icon32-posts-link_exchange.wp-has-current-submenu .wp-menu-image {
            background-position: 6px 7px!important;
        }
    </style>
<?php 
}


?>