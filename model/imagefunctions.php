<?php

/* File Name - imagefunctions.php
 * Developer - Kevart Patel
 * Plugin Site: http://kevartp.com
 * About this File - This file includes all the functions basically you need to play with images in PHP. Ofcourse you can add more fucntions to make it bigger and better ;)
 *                   , using below functions you can create thumbnail,resize, add grayscale and crop images. All the functions are used from PHP GD Lib.
 * 
 * 
 */
//ERROR REPORTING
ini_set('display_errors','Off');
ini_set('error_reporting',E_ALL);

class ImageFunctions{
 
   var $image;
   var $image_type;
 
   function load($filename) {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type, $filters,$compression=75, $permissions=null, $width) {
      if( $image_type == "IMAGETYPE_JPEG" ) { 
       	 if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagejpeg($this->image,$filename,$compression);
         return TRUE;
      } elseif( $image_type == "IMAGETYPE_GIF" ) {
         if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagegif($this->image,$filename);
         return TRUE;
      } elseif( $image_type == "IMAGETYPE_PNG" ) {
         if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagepng($this->image,$filename);
         return TRUE;
      }
      
	  if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type,$filters) { 
 
      if( $image_type == "IMAGETYPE_JPEG" ) {
		 if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagejpeg($this->image);
      } elseif( $image_type == "IMAGETYPE_GIF" ) {
		 if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagegif($this->image);
      } elseif( $image_type == "IMAGETYPE_PNG" ) {
		 if(in_array('gray',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);} // Do Grayscale
		 if(in_array('bandw',$filters)) {imagefilter($this->image, IMG_FILTER_GRAYSCALE);imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);} // Do Black & White
		 if(in_array('sepia',$filters)) {$this->sepia();} // Do Sepia
		 if(in_array('reflection',$filters)) {$this->addReflection(75, 10, false, '#fff', true);} // Add Reflection		 
		 if(in_array('border',$filters)) {$borderSettings = explode('-',$filters[20]);$this->addBorder($borderSettings[0],"#".$borderSettings[1]);} // Add Border
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagealphablending($new_image, false); 
      imagesavealpha($new_image, true); 
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   } 
   
  function sepia()
  {
      imagefilter($this->image, IMG_FILTER_GRAYSCALE);
      imagefilter($this->image, IMG_FILTER_BRIGHTNESS, -10);
      imagefilter($this->image, IMG_FILTER_CONTRAST, -20);
      imagefilter($this->image, IMG_FILTER_COLORIZE, 60, 30, -15);
    
  }
  
  function populateResizedImageOnBrowser($extension,$fileLocation,$width,$filters) 
  { 
	   header('Content-Type: image/'.strtolower($extension));
	   $this->load($fileLocation);
	   $this->resizeToWidth($width);
	   $this->output("IMAGETYPE_".$extension,$filters);
	   imagedestroy($this->image);
  }
  
  function saveResizedImageToDirectory($extension,$filename,$fileLocation,$saveLocation,$width,$filters,$save)
  {
	   $this->load($fileLocation);
	   $this->resizeToWidth($width);
	   $imageName = "width-".$width."-".$filename;
	   	 
	   if($this->save($saveLocation.$imageName,"IMAGETYPE_".strtoupper($extension),$filters)) { $save = true; }
	   imagedestroy($this->image);
	   
	   $output['imageResizedName'] = $imageName;
	   $output['saved'] = $save;   
	   return $output;
  }  
  
  function addBorder($thickness = 1, $rgbArray = array(255, 255, 255)) 
  { 
	  $rgbArray = $this->formatColor($rgbArray);
      $r = $rgbArray['r'];
      $g = $rgbArray['g'];
      $b = $rgbArray['b'];
      $x1 = 0; 
      $y1 = 0; 
      $x2 = imagesx($this->image) - 1; 
      $y2 = imagesy($this->image) - 1; 
      $rgbArray = imagecolorallocate($this->image, $r, $g, $b); 
      for($i = 0; $i < $thickness; $i++) { 
        imagerectangle($this->image, $x1++, $y1++, $x2--, $y2--, $rgbArray); 
      } 
  } 
  
  function formatColor($value) 
  {
    $rgbArray = array();
    if (is_array($value)) {
      if (key($value) == 0 && count($value) == 3) {
        $rgbArray['r'] = $value[0];
        $rgbArray['g'] = $value[1];
        $rgbArray['b'] = $value[2];
        
      } else {
        $rgbArray = $value; 
      }
    } else if (strtolower($value) == 'transparent') {
      
      $rgbArray = array(
        'r' => 255,
        'g' => 255,
        'b' => 255,
        'a' => 127
      );
      
    } else {
      $rgbArray = $this -> hex2dec($value);
    }
    return $rgbArray;
  }
  
  function hex2dec($hex) 
  {
    $color = str_replace('#', '', $hex);

    if (strlen($color) == 3) {
      $color = $color . $color;
    }

    $rgb = array(
      'r' => hexdec(substr($color, 0, 2)),
      'g' => hexdec(substr($color, 2, 2)),
      'b' => hexdec(substr($color, 4, 2)),
      'a' => 0
    );
    return $rgb;
  }   
 
  function addReflection($reflectionHeight = 50, $startingTransparency = 30, $inside = false, $bgColor = '#fff', $stretch=false, $divider = 0)
  {   
    $rgbArray = $this->formatColor($bgColor);
    $r = $rgbArray['r'];
    $g = $rgbArray['g'];
    $b = $rgbArray['b'];          

    $im = $this->image;
    $li = imagecreatetruecolor($this->getWidth(), 1);
      
    $bgc = imagecolorallocate($li, $r, $g, $b);
    imagefilledrectangle($li, 0, 0, $this->getWidth(), 1, $bgc);
      
    $bg = imagecreatetruecolor($this->getWidth(), $reflectionHeight);
    $wh = imagecolorallocate($im, 255, 255, 255);

    $im = imagerotate($im, -180, $wh);
    imagecopyresampled($bg, $im, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth(), $this->getHeight());

    $im = $bg;
    
    $bg = imagecreatetruecolor($this->getWidth(), $reflectionHeight);
      
    for ($x = 0; $x < $this->getWidth(); $x++) {
      imagecopy($bg, $im, $x, 0, $this->getWidth()-$x -1, 0, 1, $reflectionHeight);
    } 
    $im = $bg;
    
    $transaprencyAmount = $this->invertTransparency($startingTransparency, 100);
    
    if ($stretch) {
      $step = 100/($reflectionHeight + $startingTransparency);
    } else{
      $step = 100/$reflectionHeight;
    }
    for($i=0; $i<=$reflectionHeight; $i++){
      
      if($startingTransparency>100) $startingTransparency = 100;
      if($startingTransparency< 1) $startingTransparency = 1;
      imagecopymerge($bg, $li, 0, $i, 0, 0, $this->getWidth(), 1, $startingTransparency);
      $startingTransparency+=$step;
    }
        
    imagecopymerge($im, $li, 0, 0, 0, 0, $this->getWidth(), $divider, 100); 

    $x = imagesx($im);
    $y = imagesy($im);
    
    if ($inside) {

      $final = imagecreatetruecolor($this->getWidth(), $this->getHeight());   
      imagecopymerge ($final, $this->image, 0, 0, 0, $reflectionHeight, $this->getWidth(), $this->getHeight() - $reflectionHeight, 100);
      imagecopymerge ($final, $im, 0, $this->getHeight() - $reflectionHeight, 0, 0, $x, $y, 100);

    } else {
    
      $final = imagecreatetruecolor($this->getWidth(), $this->getHeight() + $y);    
      imagecopymerge ($final, $this->image, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), 100);
      imagecopymerge ($final, $im, 0, $this->getHeight(), 0, 0, $x, $y, 100);      
    } 
    
    $this->image = $final;   
    
    imagedestroy($li);
    imagedestroy($im);  
  }  
 
  function invertTransparency($value, $originalMax, $invert=true)
  {
    if ($value > $originalMax) {
      $value = $originalMax;
    }
    
    if ($value < 0) {
      $value = 0;
    }
    
    if ($invert) {
      return $originalMax - (($value/100) * $originalMax);  
    } else {  
      return ($value/100) * $originalMax;   
    }
  }
 
}
?>