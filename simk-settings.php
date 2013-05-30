<?php
if (!function_exists('is_admin')) {

    header('Status: 403 Forbidden');

    header('HTTP/1.1 403 Forbidden');

    exit();

}

if (!class_exists("Simple_Image_Manipulator_Kevart_Settings")) :

class Simple_Image_Manipulator_Kevart_Settings {

	function __construct() {	
		add_action('admin_init', array($this,'admin_init'), 20 );
		add_action('wp_ajax_save_my_image', array($this,'save_image_callback'),20);
		
	}

	function admin_init() {
		add_settings_section('simk_main', 'Manipulate Image Using Slider', array($this, 'render_simple_image_manipulator_inputs_form'), 'simk_settings_page');
	}

	function render_simple_image_manipulator_inputs_form() {
		
		$this->simk_display_donation_button();
		
		if(isset($_REQUEST['image']) && isset($_REQUEST['page']) && $_REQUEST['page'] == "simk_plugin"):
		$imagePath = $_REQUEST['image'];
		?>
        <div class="leftContainer">
		<style type="text/css">
			  #simk-frame > div.simk { padding: 10px !important; }
		</style>
        <script type="text/javascript">
			  var Settings = {
				imageResizeUrl: '<?php echo plugin_dir_url( __FILE__ ) ?>controller/image.php?size='
			  }
				
		</script>
        <?php wp_enqueue_script('sip_app', plugin_dir_url( __FILE__ ).'js/app.js');
			  
		list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$_REQUEST['image']);
		if($width < 200) {
			$widthSlider = 500;
		}elseif($width > 900){
			$widthSlider = 500;
		}else{
			$widthSlider = 500;
		}
		$iframe = "width:".($width + 100)."px;height:".($height + 100)."px;"; // Set Iframe size. // Added to hide scroll bars on iframe
		?>

        <div class="simk">
        <form name="saveImage" id="saveImage">
        <input type="hidden" name="size" id="size" value="<?php echo ($width); ?>">
        <input type="hidden" name="height" id="height" value="<?php echo ($height + 50); ?>">
        <p>
            <label for="amount" style="color:#000000;">Image Width in Pixels:&nbsp;</label>
            <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;" readonly/>
                   
        </p>
        
        <div id="slider-range-min" style="width:<?php echo $widthSlider; ?>px"></div><br>
        
        Grayscale:&nbsp;<input type="checkbox" name="filters[]" id="gray" value="gray" class="filters"> <br><br>
        Black & White:&nbsp;<input type="checkbox" name="filters[]" id="bandw" value="bandw" class="filters"> <br><br>
        Sepia:&nbsp;<input type="checkbox" name="filters[]" id="sepia" value="sepia" class="filters"> <br><br>
        Set Border Settings: size = <input type="number" id="bsize" name="bsize" value="2" /> &nbsp; color = #<input type="text" id="bcolor" name="bcolor" value="000000" />
        <br /><br />
        Add Border:&nbsp;<input type="checkbox" name="filters[]" id="add_border" value="border" class="filters">&nbsp;Please uncheck and recheck border after changing border settings
         <br><br>
        Add Reflection:&nbsp;<input type="checkbox" name="filters[]" id="add_reflection" value="reflection" class="filters"> <br><br>
        
        <input type="submit" name="submit" value="Save Image As" /><br><br>
        </form>
        <br />
        <div id="saveSuccess"></div>
        
        </div>
        </div> <!-- End Left Container -->
        
        <div class="rightContainer">

        <div id="loadContent"> <img src="<?php echo $imagePath; ?>" id="imageName"> </div>
        
        <iframe id="ImageFrame" src="" width="1000" height="850" frameborder="0" style="display:none;"></iframe>
        
        </div> <!-- End Right Container -->
		<?php
		
		endif;
		
		if(!isset($_REQUEST['image']) && isset($_REQUEST['page']) && $_REQUEST['page'] == "simk_plugin"):
		
			echo "<p><strong>Click on the Image to Manipulate</strong></p>";
			
			$query_images_args = array(
				'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
			);
			
			$query_images = new WP_Query( $query_images_args );
			$images = array();
			foreach ( $query_images->posts as $image) {
				$images[]= wp_get_attachment_url( $image->ID );
			}
			
			
			# If Images are there in Media Libaray
			if(count($images) > 0){
			
				echo "<ul class=\"simk-images-list\">";
				foreach ($images as $sip){
					$imgSrc = str_replace(get_site_url(),'',$sip);
					echo "<li><a href=\"options-general.php?page=simk_plugin&image=".urlencode($imgSrc)."\"><img src=\"".$sip."\" title=\"".basename($sip)."\"></a></li>";
					
				}
				echo "</ul>";
				
			}else{ 
			
				echo "<p>There are no Images in your Media Library. Please upload images using <a href=\"upload.php\">Media Library</a></p>";
			
			}
		
		endif;
		
	}
	
	function simk_display_donation_button(){

		$content .= '<p><strong>Howdy, Administrator! &nbsp;&nbsp;</strong> Thanks for using Simple Image Manipulator. <strong>SIM</strong> has always been free. If you value my work please consider a <a href="http://kevartp.com/support/" target="_blank" style="text-decoration: none;">small donation</a> to show your appreciation. Thanks!';
	
		$content .='<a href="http://kevartp.com/support/" target="_blank"><img title="Donation" src="'.plugin_dir_url( __FILE__ ).'images/paypal_donate_now.gif" alt="Donation" border="0" style="margin-left:20px;vertical-align:middle;"/></a></p>';
	
		echo $content;

	}
	
	function save_image_callback(){
		
		include_once('config/config.php');
		include_once('model/imagefunctions.php');
		
		$fileName = basename($_REQUEST['imageSrc']); // Get name of the file from the path 
		 
		$SrcFilename = explode(".",$fileName); // Explode name to get the extension
		
		$imageAbsolutePath = $base_path.$_REQUEST['imageSrc']; // load path of the image
		
		$savepath = $base_path."/wp-content/uploads/"; // save location for the image
		
		$imageFilters = $_POST['filters']; // Get filters
		
		$borderSettings = $_POST['bsize']."-".$_POST['bcolor']; // Set border settings
		
		$imageFilters[20] = $borderSettings; // Add border settings to filters Array
		
		$image =  new ImageFunctions(); // New ImageFunctions Object
		
		if ($SrcFilename[1] == 'jpg'): $SrcFilename[1] = 'jpeg'; endif;
		
		$saved = $image->saveResizedImageToDirectory($SrcFilename[1],$fileName,$imageAbsolutePath,$savepath,$_POST['size'],$imageFilters,$save = FALSE);
		
		if($saved['saved']) {
			 echo "<table><tr><td><div style=\"padding-top:15px;float:left;color: #5A5A5A;line-height: 1.5;\">Image <strong>".$saved['imageResizedName']."</strong> Saved Successfully! in your <a href=\"upload.php\">Media Library</a></div><br></td></tr> ";
			 
			 // Save file as an attachment to list saved file in Wp-Media-Library
			 
			 if(isset($saved['imageResizedName'])) {
				 
				$filetype   = wp_check_filetype(basename($saved['imageResizedName']), null);
				$title      = $saved['imageResizedName'];
				$ext        = strrchr($title, '.');
				$title      = ($ext !== false) ? substr($title, 0, -strlen($ext)) : $title;
				$attachment = array(
					'post_mime_type'    => $filetype['type'],
					'post_title'        => addslashes($title),
					'post_content'      => '',
					'post_status'       => 'inherit',
					'post_parent'       => $post->ID,
					'guid'				=> get_site_url().'/wp-content/uploads/'.$saved['imageResizedName']
				);
		
				$attach_key = 'document_file_id';
				$attach_id  = wp_insert_attachment($attachment, $saved['imageResizedName']);
				$existing_download = (int) get_post_meta($post->ID, $attach_key, true);
		
				if(is_numeric($existing_download)) {
					wp_delete_attachment($existing_download);
				}
		
				update_post_meta($post->ID, $attach_key, $attach_id);
			 }
			 
			 echo "<tr><td>
					<p>Donwload image on your loacal machine</p> 
					<p><a href=\"/wp-content/plugins/simple-image-manipulator-kevart/controller/download.php?filename=".$saved['imageResizedName']."&filepath=".$savepath.$saved['imageResizedName']."\" style=\"text-decoration:none;\">
				   <div class=\"download_button\" style=\"float:left;\">Download Image</div>
				   </a></p>
				   
				   </td></tr></table>";
		 }else {
			 echo "<div style=\"padding-top:15px;float:left;color: #5A5A5A;line-height: 1.5;\">Error: Could not save ".$saved['imageResizedName']."</div>";
		 }
		 
		 die();
	}

	
} // end class

endif;

?>