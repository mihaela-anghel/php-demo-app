<?php

   # ========================================================================#
   #
   #  Author:    Jarrod Oberto
   #  Version:	 1.0
   #  Date:      17-Jan-10
   #  Purpose:   Resizes and saves image
   #  Requires : Requires PHP5, GD library.
   #  Usage Example:
   #                     include("../image_manipulation_old/classes/resize_class.php");
   #                     $resizeObj = new resize('images/cars/large/input.jpg');
   #                     $resizeObj -> resizeImage(150, 100, 0);
   #                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
   #
   #
   # ========================================================================#


		Class resize
		{
			// *** Class variables
			private $image;
		    private $width;
		    private $height;
			private $imageResized;
			private $imageWatermarked;

			function __construct($fileName)
			{
				// *** Open up the file
				$this->image = $this->openImage($fileName);

			    // *** Get width and height
			    $this->width  = imagesx($this->image);
			    $this->height = imagesy($this->image);
			}

			## --------------------------------------------------------

			private function openImage($file)
			{
				// *** Get extension
				$extension = explode(".",$file);
				$extension = end($extension);
				$extension = strtolower($extension);				

				switch($extension)
				{
					case 'jpg':
					case 'jpeg':
						$img = @imagecreatefromjpeg($file);
						break;
					case 'gif':
						$img = @imagecreatefromgif($file);
						break;
					case 'png':
						$img = @imagecreatefrompng($file);
						break;
					default:
						$img = false;
						break;
				}
				return $img;
			}

			## --------------------------------------------------------

			public function resizeImage($newWidth, $newHeight, $option="auto")
			{
				// *** Get optimal width and height - based on $option
				$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

				$optimalWidth  = $optionArray['optimalWidth'];				
				$optimalHeight = $optionArray['optimalHeight'];


				// *** Resample - create image canvas of x, y size
				$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
				imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
								
				
				// *** if option is 'crop', then crop too
				if ($option == 'crop') {
					$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
				}												
			}
			## --------------------------------------------------------

			public function backgroundImage($newWidth, $newHeight, $option="auto")
			{
				// *** Get optimal width and height - based on $option
				$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

				$optimalWidth  = $optionArray['optimalWidth'];				
				$optimalHeight = $optionArray['optimalHeight'];
				
				// set background
				$this->imageResized 	= imagecreatetruecolor($newWidth, $newHeight);								
				$color 					= imagecolorallocate($this->imageResized, 255, 255, 255);
				imagefilledrectangle($this->imageResized, 0, 0, $newWidth, $newHeight, $color);								
				imagecopyresampled($this->imageResized, $this->image, round(($newWidth-$optimalWidth)/2),round(($newHeight-$optimalHeight)/2), 0 ,0, $optimalWidth, $optimalHeight, $this->width, $this->height);																
			}

			## --------------------------------------------------------
			
			private function getDimensions($newWidth, $newHeight, $option)
			{

			   switch ($option)
				{
					case 'exact':
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
						break;
					case 'portrait':
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
						break;
					case 'landscape':
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
						break;
					case 'auto':
						$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
					case 'crop':
						$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
					case 'ratio':
						$optionArray = $this->getSizeByRatio($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;	
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getSizeByFixedHeight($newHeight)
			{
				$ratio = $this->width / $this->height;
				$newWidth = $newHeight * $ratio;
				return $newWidth;
			}

			private function getSizeByFixedWidth($newWidth)
			{
				$ratio = $this->height / $this->width;
				$newHeight = $newWidth * $ratio;
				return $newHeight;
			}

			private function getSizeByAuto($newWidth, $newHeight)
			{
				if ($this->height < $this->width)
				// *** Image to be resized is wider (landscape)
				{
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				}
				elseif ($this->height > $this->width)
				// *** Image to be resized is taller (portrait)
				{
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
				}
				else
				// *** Image to be resizerd is a square
				{
					if ($newHeight < $newWidth) {
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					} else if ($newHeight > $newWidth) {
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
					} else {
						// *** Sqaure being resized to a square
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
					}
				}

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}
			
			private function getSizeByRatio($new_width, $new_height)
			{
				if( $new_width <= $this->width || $new_height <=  $this->height)
				{			
					if( ( $this->width / $new_width ) > ( $this->height / $new_height ) )
					{
						$newwidth=$new_width;
						$newheight=($this->height/$this->width)*$new_width;
					}
					else
					{
						$newheight=$new_height;
						$newwidth=($this->width/$this->height)*$new_height;
					}
				}
				else
				{
					$newwidth	= $this->width;
					$newheight	= $this->height;
				}
				$output_width	= round($newwidth);
				$output_height	= round($newheight);
				
				return array('optimalWidth' => $output_width, 'optimalHeight' => $output_height);
			}


			## --------------------------------------------------------

			private function getOptimalCrop($newWidth, $newHeight)
			{

				$heightRatio = $this->height / $newHeight;
				$widthRatio  = $this->width /  $newWidth;

				if ($heightRatio < $widthRatio) {
					$optimalRatio = $heightRatio;
				} else {
					$optimalRatio = $widthRatio;
				}

				$optimalHeight = $this->height / $optimalRatio;
				$optimalWidth  = $this->width  / $optimalRatio;

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
			{
				// *** Find center - this will be used for the crop
				$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
				$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

				$crop = $this->imageResized;
				//imagedestroy($this->imageResized);

				// *** Now crop from center to exact requested size
				$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
				imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
			}
			
			## --------------------------------------------------------

			public function saveImage($savePath, $imageQuality="100")
			{
				// *** Get extension        		
				$extension = explode(".",$savePath);
				$extension = end($extension);
				$extension = strtolower($extension);
				
				switch($extension)
				{
					case 'jpg':
					case 'jpeg':
						if (imagetypes() & IMG_JPG) {
							imagejpeg($this->imageResized, $savePath, $imageQuality);
						}
						break;

					case 'gif':
						if (imagetypes() & IMG_GIF) {
							imagegif($this->imageResized, $savePath);
						}
						break;

					case 'png':
						// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($imageQuality/100) * 9);

						// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;
						
						if (imagetypes() & IMG_PNG) {
							 imagepng($this->imageResized, $savePath, $invertScaleQuality);
						}
						break;

					// ... etc

					default:
						// *** No extension - No save.
						break;
				}

				imagedestroy($this->imageResized);
			}
			
			public function watermarkImage($watermarkPath)
			{
				// Watermark Sourse    						
       			$extension = explode(".",$watermarkPath);
				$extension = end($extension);
				$extension = strtolower($extension);
				
				switch($extension)
				{
					case 'jpg':
					case 'jpeg':
						if (imagetypes() & IMG_JPG) {							
							$watermarkSourse = imagecreatefromjpeg($watermarkPath);
						}
						break;

					case 'gif':
						if (imagetypes() & IMG_GIF) {
							$watermarkSourse = imagecreatefromgif($watermarkPath);
						}
						break;

					case 'png':						
						if (imagetypes() & IMG_PNG) {
							 $watermarkSourse = imagecreatefrompng($watermarkPath);							 							
						}
						break;					
					default:
						// *** No extension - No save.
						break;
				}						
								
				// Image sourse
				if($this->imageResized)
					$imageSourse = $this->imageResized;	
				else
					$imageSourse = $this->image;					
				
				// Set the margins for the stamp and get the height/width of the stamp image	
				$wx = imagesx($watermarkSourse);
				$wy = imagesy($watermarkSourse);
					
				// Sourse image sizes						
				$ix = imagesx($imageSourse);
				$iy = imagesy($imageSourse);
												
				// Copy the stamp image onto our photo using the margin offsets and the photo width to calculate positioning of the stamp. 
				
				//center 
				imagecopy($imageSourse, $watermarkSourse, round(($ix - $wx)/2),  round(($iy - $wy)/2), 0, 0 , $wx, $wy);																				
								
				//top left 
				//imagecopy($imageSourse, $watermarkSourse, 0, 0, 0, 0 , $wx, $wy);																				
			}
			
			public function showImage($savePath, $imageQuality = 100)
			{																			
				if($this->imageResized)
					$resourse = $this->imageResized;
				else
					$resourse = $this->image;					
				
				// *** Get extension        		
				$extension = explode(".",$savePath);
				$extension = end($extension);
				$extension = strtolower($extension);
				
				switch($extension)
				{					
					case 'jpg':
					case 'jpeg':
						if (imagetypes() & IMG_JPG) {														
							
							header('Content-type: image/jpeg');
							imagejpeg($resourse, NULL, $imageQuality);
						}
						break;

					case 'gif':
						if (imagetypes() & IMG_GIF) {
							
							header('Content-type: image/gif');
							imagegif($resourse, NULL);
						}
						break;

					case 'png':
						// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($imageQuality/100) * 9);

						// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;
						
						if (imagetypes() & IMG_PNG) {
							
							 header('Content-type: image/png');	
							 imagepng($resourse, NULL, $invertScaleQuality);
						}
						break;

					// ... etc

					default:
						// *** No extension - No save.
						break;
				}
				
				if($this->imageResized)
					imagedestroy($this->imageResized);
				else
					imagedestroy($this->image);
				
				/*if($this->imageResized)
				{					
					//Output and free memory
					header('Content-type: image/png');
					imagepng($this->imageResized, NULL, 0);								
					imagedestroy($this->imageResized);
				}												
				else
				{					
					// Output and free memory
					header('Content-type: image/png');
					imagepng($this->image, NULL, 0);								
					imagedestroy($this->image);
				}*/
			}

			## --------------------------------------------------------

		}
?>
