<?php
include_once('../config/config.php');
include_once('../model/imagefunctions.php');

//ERROR REPORTING
ini_set('display_errors','Off');
ini_set('error_reporting',E_ALL); 

$fileName = basename($_REQUEST['imageSrc']); ; // Get name of the file from the path

$SrcFilename = explode(".",$fileName); // explode filename into array to get extension

$fileExtension = strtoupper($SrcFilename[1]);

$filters = explode(',',$_REQUEST['filters']); // Convert Javascript Array into php array

if ($fileExtension == 'JPG'): $fileExtension = 'JPEG'; endif;

$imageAbsolutePath = $base_path.$_REQUEST['imageSrc']; // load path of an image

$image =  new ImageFunctions();

$image->populateResizedImageOnBrowser($fileExtension,$imageAbsolutePath,$_REQUEST['size'],$filters);

?>