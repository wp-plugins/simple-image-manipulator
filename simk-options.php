<?php

if (!function_exists('is_admin')) {

    header('Status: 403 Forbidden');

    header('HTTP/1.1 403 Forbidden');

    exit();

}

if (!class_exists("Simple_Image_Manipulator_Kevart_Options")) :

class Simple_Image_Manipulator_Kevart_Options {
	var $page = '';
	var $message = 0;

	function __construct() {
		add_action( 'admin_menu', array( $this, 'init' ) );
		
		if($_REQUEST['page'] == 'simk_plugin') {
		
			add_action( 'init', array($this,'load_scripts')); // Load Wp Admin JS and CSS files
		
		}
		
	}

	function init() {
		if ( ! current_user_can('update_plugins') )
			return;
		
		// Add a new submenu
		$this->page = $page =  
		add_options_page(__('Simple Image Manipulator', 'simk_plugin'), __('Simple Image Manipulator', 'simk_plugin'),
		 'administrator', 'simk_plugin', array($this,'simk_ex_page') );

	}

	function simk_ex_page() {

		$messages[1] = __('SIMK Plugin action taken.', 'simk_plugin');

		if ( isset($_GET['message']) && (int) $_GET['message'] ) {

			$message = $messages[$_GET['message']];
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);

		}
		$title = __('Simple Image Manipulator', 'simk_plugin');
		?>
		<div class="wrap">   
			<?php screen_icon(); ?>

			<h2><?php echo "Simple Image Manipulator"; ?></h2>

			<?php

				if ( !empty($message) ) : 

					echo '<div id="message" class="updated fade"><p>'.$message.'</p></div>';

				endif; 

				settings_fields('simple_image_manipulator_kevart_options'); 

				do_settings_sections('simk_settings_page');

			?>

		</div>

	<?php }

	function load_scripts(){
		//Load javascripts
		wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widjet');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('colorpicker_js', plugin_dir_url( __FILE__ ).'colorpicker/js/colorpicker.js');
	
		//Load CSS
		wp_enqueue_style('sip_style_all', plugin_dir_url( __FILE__ ).'css/all.css');
		wp_enqueue_style('sip_style_simk', plugin_dir_url( __FILE__ ).'css/simk.css');
		wp_enqueue_style('colorpicker_css', plugin_dir_url( __FILE__ ).'colorpicker/css/colorpicker.css');
	}

} // end Simple_Image_Manipulator_Kevart_Options class
endif; 



?>