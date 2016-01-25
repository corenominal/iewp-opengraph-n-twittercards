<?php
/**
 * Plugin Name: IEWP Open Graph and Twitter Cards 
 * Plugin URI: https://github.com/corenominal/iewp-opengraph-n-twittercards
 * Description: A WordPress plugin for providing Open Graph and Twitter Card metadata.
 * Author: Philip Newborough
 * Version: 0.0.1
 * Author URI: https://corenominal.org
 */

require_once( plugin_dir_path( __FILE__ ) . 'metadata.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin-page.php' );


function iewp_metadata_action_links( $actions, $plugin_file ) 
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file)
	{
		$settings = array('settings' => '<a href="options-general.php?page=iewp-metadata">' . __('Settings', 'General') . '</a>');
		//$site_link = array('support' => '<a href="http://corenominal.org" target="_blank">Support</a>');
	
		$actions = array_merge($settings, $actions);
		//$actions = array_merge($site_link, $actions);	
	}
	return $actions;
}
add_filter( 'plugin_action_links', 'iewp_metadata_action_links', 10, 5 );