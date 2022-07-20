<?php
session_start();

class CaptchaSecurityImages 
{
	var $font 		= 'captcha.ttf';
	var	$font_size 	= 16;
	function generateCode($characters) 
	{
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible 	= 'ABCDEFHIJKLMNOPQRSTUVWXYZ'; //abcdefghijklmnopqrstuvwxyz
		$code 		= '';
		$i 			= 0;
		while ($i < $characters) 
		{ 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function __construct($img_width='120',$img_height='40',$characters='6') {
		
		//echo "<pre>";	print_r($_SERVER);	echo "<pre>"; die();		
		$word 		= $this->generateCode($characters);
		$font_path 	= str_replace("captcha.php", $this->font, $_SERVER["SCRIPT_FILENAME"]); 
		//$_SERVER["DOCUMENT_ROOT"]."/rditruckservice/myclasses/captcha/".$this->font;

		// -----------------------------------
		// Determine angle and position
		// -----------------------------------

		$length	= strlen($word);
		$angle	= ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
		$x_axis	= rand(6, (360/$length)-16);
		$y_axis = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);

		// -----------------------------------
		// Create image
		// -----------------------------------

		// PHP.net recommends imagecreatetruecolor(), but it isn't always available
		if (function_exists('imagecreatetruecolor'))
		{
			$im = imagecreatetruecolor($img_width, $img_height);
		}
		else
		{
			$im = imagecreate($img_width, $img_height);
		}

		// -----------------------------------
		//  Assign colors
		// -----------------------------------

		$bg_color		= imagecolorallocate ($im, 255, 255, 255);
		$border_color	= imagecolorallocate ($im, 255, 255, 255);
		$text_color		= imagecolorallocate ($im, 105, 105, 105);
		$grid_color		= imagecolorallocate($im, 200, 200, 200);
		$shadow_color	= imagecolorallocate($im, 255, 240, 240);

		// -----------------------------------
		//  Create the rectangle
		// -----------------------------------

		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

		// -----------------------------------
		//  Create the spiral pattern
		// -----------------------------------

		$theta		= 1;
		$thetac		= 7;
		$radius		= 16;
		$circles	= 20;
		$points		= 32;

		for ($i = 0; $i < ($circles * $points) - 1; $i++)
		{
			$theta = $theta + $thetac;
			$rad = $radius * ($i / $points );
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta = $theta + $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta )) + $y_axis;
			imageline($im, $x, $y, $x1, $y1, $grid_color);
			$theta = $theta - $thetac;
		}

		// -----------------------------------
		//  Write the text
		// -----------------------------------

		$use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;

		if ($use_font == FALSE)
		{
			$font_size = 5;
			$x = rand(0, $img_width/($length/3));
			$y = 0;
		}
		else
		{
			$font_size	= $this->font_size;
			$x = rand(0, $img_width/($length/1.5));
			$y = $font_size+2;
		}

		for ($i = 0; $i < strlen($word); $i++)
		{
			if ($use_font == FALSE)
			{
				$y = rand(0 , $img_height/2);
				imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
				$x += ($font_size*2);
			}
			else
			{
				$y = rand($img_height/2, $img_height-3);
				imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
				$x += $font_size;
			}
		}


		// -----------------------------------
		//  Create the border
		// -----------------------------------

		imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);
		
		
		// -----------------------------------
		//  output captcha image to browser
		// -----------------------------------
		header('Content-Type: image/jpeg');
		imagejpeg($im);
		imagedestroy($im);
		$_SESSION['security_code'] = $word;
	}
}
$captcha = new CaptchaSecurityImages(140,57,6);
?>