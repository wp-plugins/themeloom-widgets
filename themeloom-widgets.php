<?php
/*
Plugin Name: ThemeLoom Widgets
Description: A set of really useful widgets for showing posts, pages, tweets and your flickr images. Designed for use with responsive themes.
Version: 1.3
License: GPLv2
Author: Tim Hyde
Author URI: http://livingos.com/
*/

/*  Copyright 2009-2013  Tim Hyde  (email : livingos@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

$ThemeLoomWidgets = new ThemeLoomWidgets(); 

class ThemeLoomWidgets
{
	/*
	 *	Contsructor
	 */
	public function __construct() 
	{
		
		
		// localization
		add_action('plugins_loaded', array($this, 'SetLanguage'),1);
		
		// activation function
		register_activation_hook(__FILE__,array($this,'Activate'));
		
		// Deactivation function
		register_deactivation_hook(__FILE__,array($this,'Deactivate'));
		
		// This function runs on each page load to check if our plugin has been updated and, if so, what SQL functions we need to run to upgrade our plugin's tables to the most recent version
		add_action('init', array($this, 'Update'),1);
		
		// add styles and sciripts
		add_action('wp_print_styles',array($this,'Styles'));
		//add_action('wp_print_scripts',array($this,'Scripts'));
		
		// add widgets
		add_action('widgets_init', array($this,'InitializeWidget'));
		
		//add theme support functions
		add_action( 'after_setup_theme',    array( $this, 'addFeaturedImageSupport' ), 11 );
		
		
		$this->errors = new WP_Error();
	}
	
	/*
	 *	Activation routine
	 */
	function Activate() 
	{
	
	}
	
	/*
	 *	Deactivation routine
	 */
	function Deactivate()
	{
		// Nothing to see here for now!
	}
	
	
	/*
	 *	Update routine
	 */
	function Update()
	{
	
	
	}
	
	
	/*
	 *	Load language text domain
	 */
	function SetLanguage() {
	
		$plugin_dir = basename(dirname(__FILE__));
		$locale = get_locale();
		$mofile = WP_PLUGIN_DIR . "/themeloom-widgets/languages/$locale.mo";

		if ( file_exists( $mofile ) )
      		load_textdomain( 'livingos', $mofile );
			
	}		

	
	/*
	 *	Styles and script for front end
	 */
	function Styles()
	{		
		
		wp_enqueue_style('ThemeLoomWidgets',WP_PLUGIN_URL.'/themeloom-widgets/css/widget.css');
	
	}

	
	/*
	 *	Add widgets
	 */
	function InitializeWidget()
	{
		// flickr widget
		if (file_exists(plugin_dir_path(__FILE__ ). 'flickr-widget.php')){
			require_once('flickr-widget.php'); 
			register_widget('ThemeLoomFlickrWidget'); 
			
		}
		
		// fb widget
		if (file_exists(plugin_dir_path(__FILE__ ). 'facebook-widget.php')){
			require_once('facebook-widget.php'); 
			register_widget('ThemeLoom_Facebook_Widget'); 
			
		}
		
		//show post and pages widgets
		if (file_exists(plugin_dir_path(__FILE__ ). 'show-widgets.php')){			
			require_once('show-widgets.php'); 
			
			register_widget("los_showposts_widget");
			register_widget("los_showpages_widget");
			register_widget("los_media_widget");
			
			
		}
		
		//twitter widget
		if (file_exists(plugin_dir_path(__FILE__ ). 'twitter-widget.php')){			
			require_once('twitter-widget.php');
			register_widget("los_twitter_widget");
			
		}
	}
	
	function addFeaturedImageSupport()
	{
		//add featured image support to pages
		$supportedTypes = get_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-thumbnails' );
		
	}
	


}

//debug
if(!function_exists('_log')){
  function _log( $message ) {
    if( WP_DEBUG === true ){
      if( is_array( $message ) || is_object( $message ) ){
        error_log( print_r( $message, true ) );
      } else {
        error_log( $message );
      }
    }
  }
}
?>