<?php
/* 
Plugin Name: WP CPT Images
Plugin URI: 
Author URI: http://social-ink.net
Version: 1
Author: Yonatan Reinberg at Social Ink
Description: Associate images with custom post types (archives & single)
Copyright 2015 Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - https://www.social-ink.net
*/
//init vars
$cptplugin_path = WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__));
define('cptplugin_asset_path' , plugins_url('/assets/', __FILE__));
$cptplugin_url = plugins_url() . '/' . plugin_basename(dirname(__FILE__));
require_once('core/config.php');
require_once('core/plugin.php');
require_once('core/core.php');
//activation
register_activation_hook(__FILE__,'cptImages_install');
//js
 add_action( 'admin_init', 'addJS_ptImages' );
	function addJS_ptImages() {
		wp_register_script( 'cptImages_script', plugins_url('/assets/js/cptImages_script.js', __FILE__) );
	}	 
//admin menu
add_action('admin_menu', 'addAdmin_ptImages');
	function addAdmin_ptImages() {
		$adminpage = add_submenu_page( "tools.php", "Manage CPT Images", "Manage CPT Images", "manage_options", "cptImages", "adminPage_cptImages" );	
		add_action('admin_print_styles-' . $adminpage, 'ptImages_loadstyles');
	}  
		function adminPage_cptImages() {  
			 include(plugin_dir_path( __FILE__ ) . 'views/admin.php');  
		 }  		
		function ptImages_loadstyles() {
			//It will be called only on your plugin admin page, enqueue our script here
			wp_enqueue_script( 'cptImages_script' );
		}		 
//admin css
add_action('admin_head', 'ptImages_admin_register_head');
	function ptImages_admin_register_head() {  		
			$cptImages_css = cptplugin_asset_path . '/css/cptImages_admin.css';
			echo "<link rel='stylesheet' type='text/css' href='$cptImages_css' />\n";
		}	
?>