<?php
$image_path = "../../images/arrow_1.png";

if(!isset($_GET['color']))
	$color = "666666";
else
	$color = $_GET['color'];	
$color = preg_replace('/^#/','',$color);
if (strlen($color) == 3) $color = $color.$color;

if (preg_match('/^(?:[A-Fa-f0-9]{2}){3}$/',$color,$match))
{
    $match = str_split($match[0],2);
    foreach ($match as $k=>$m){ $match[$k] = intval($match[$k],16); }	
	
	header('Content-Type: image/png');
	$rgb = array($match[0],$match[1],$match[2]);

	/* Negative values, don't edit */
	$rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);
	$img = imagecreatefrompng($image_path);
	
	imagefilter($img, IMG_FILTER_NEGATE); 
	imagefilter($img, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
	imagefilter($img, IMG_FILTER_NEGATE); 
	
	imagealphablending( $img, false );
	imagesavealpha( $img, true );
	imagepng($img);
	imagedestroy($img);
}
?>