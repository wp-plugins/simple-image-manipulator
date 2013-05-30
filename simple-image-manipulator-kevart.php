<?php

/*

Plugin Name: Simple Image Manipulator Plugin

Plugin URI: http://www.kevartp.com/

Description: The Simple Image Manipulator Plugin is an free wordpress pluigin to Manipulate images on Wordpress

Version: 1.0

Author: Kevart P.

Author URI: http://kevartp.com/

*/


/*  Copyright 2013  Simple Image Manipulator.

This program is free software; you can redistribute it and/or modify

it under the terms of the GNU General Public License as published by

the Free Software Foundation; either version 2 of the License, or

(at your option) any later version.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

GNU General Public License for more details.



You should have received a copy of the GNU General Public License

along with this program; if not, write to the Free Software

Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if (!function_exists('is_admin')) {

    header('Status: 403 Forbidden');

    header('HTTP/1.1 403 Forbidden');

    exit();

}

// Pre-2.6 compatibility

if ( ! defined( 'WP_CONTENT_URL' ) )

      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );

if ( ! defined( 'WP_CONTENT_DIR' ) )

      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );


define( 'SIMK_DIR', WP_PLUGIN_DIR . '/simple-image-manipulator' );

define( 'SIMK_URL', WP_PLUGIN_URL . '/simple-image-manipulator' );

if (!class_exists("Simple_Image_Manipulator_Kevart")) :

class Simple_Image_Manipulator_Kevart {

	var $settings, $options_page;

	function __construct() {	

		if (is_admin()) {

			if (!class_exists("Simple_Image_Manipulator_Kevart_Settings"))

				require(SIMK_DIR . '/simk-settings.php');

			$this->settings = new Simple_Image_Manipulator_Kevart_Settings();	


			if (!class_exists("Simple_Image_Manipulator_Kevart_Options"))

				require(SIMK_DIR . '/simk-options.php');

			$this->options_page = new Simple_Image_Manipulator_Kevart_Options();	

		}

		add_action('init', array($this,'init') );
		add_action('admin_init', array($this,'admin_init') );
		add_action('admin_menu', array($this,'admin_menu') );

		register_activation_hook( __FILE__, array($this,'activate') );
		register_deactivation_hook( __FILE__, array($this,'deactivate') );

	}

	function network_propagate($pfunction, $networkwide) {

		global $wpdb;

		if (function_exists('is_multisite') && is_multisite()) {
			if ($networkwide) {
				$old_blog = $wpdb->blogid;
				$blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					call_user_func($pfunction, $networkwide);
				}
				switch_to_blog($old_blog);
				return;
			}	
		} 
		call_user_func($pfunction, $networkwide);

	}

	function activate($networkwide) {
		$this->network_propagate(array($this, '_activate'), $networkwide);
	}

	function deactivate($networkwide) {
		$this->network_propagate(array($this, '_deactivate'), $networkwide);
	}

	function _activate() {}

	function _deactivate() {}

	function init() {
		load_plugin_textdomain( 'simk_plugin', SIMK_DIR . '/lang', basename( dirname( __FILE__ ) ) . '/lang' );
	}

	function admin_init() {	}

	function admin_menu() {	}

	function print_example($str, $print_info=TRUE) {

		if (!$print_info) return;

		__($str . "<br/><br/>\n", 'simk_plugin' );

	}

	function javascript_redirect($location) {
		?>
		  <script type="text/javascript">
		  <!--
		  window.location= <?php echo "'" . $location . "'"; ?>;
		  //-->
		  </script>
		<?php
		exit;
	}

} // end class
endif;

global $simk_plugin;

if (class_exists("Simple_Image_Manipulator_Kevart") && !$simk_plugin) {
    $simk_plugin = new Simple_Image_Manipulator_Kevart();	
}	

?>