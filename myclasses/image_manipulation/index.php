<?php
//r_200x300_option, w_200x300_option,  rw_200x300_option, b_200x300_option
//option: exact, portrait, landscape, auto, crop, ratio		

if(isset($_GET["image_name"]) && isset($_GET["folder"]) && isset($_GET["options"]))
{
	//get variables
	$image_name		= $_GET['image_name'];
	$folder 		= $_GET['folder'];
	$options 		= $_GET['options'];
	$options 		= explode("_",$options);
	
	//action
	$resize 		= false;
	$background		= false;
	$watermark 		= false;
	if(substr_count($options[0], 'r'))
		$resize 	= true;
	if(substr_count($options[0], 'b'))
		$background	= true;	
	if(substr_count($options[0], 'w'))
		$watermark 	= true;		
		
	//file path		
	define('BASEPATH','');
	require_once('../../application/config/config.php');
	$file_path 		= $config['base_path'].$folder.'/'.$image_name;
	
	//new size
	$new_sizes 		= explode("x",$options[1]);
	$new_width 		= $new_sizes["0"];
	$new_height 	= $new_sizes["1"];
	
	//resize option
	$option 		= $options[2];
		
	//resize
	include("resize-class.php");	
	$resizeObj = new resize($file_path);		
	
	//resize
	if($resize)
	{
		$resizeObj -> resizeImage($new_width, $new_height, $option);	
	}	
	
	//background
	if($background)
	{
		$resizeObj -> backgroundImage($new_width, $new_height, "ratio");		
	}	
	
	//watermark
	/*if($watermark)
	{
		if($new_width >= 203)
			$resizeObj -> watermarkImage("watermark.png");		
		else
			$resizeObj -> watermarkImage("watermark_th.png");
	}*/	
	//output	
	
	//$resizeObj -> saveImage('sample-resized.jpg', 100);		
	$resizeObj -> showImage($file_path, 100);
	
	unset($resizeObj);
}
?>
