<?php
if ( ! function_exists('is_empty_folder'))
{
	function is_empty_folder($directory = "")
	{
		return (count(scandir($directory))<= 2);	
	}
}

function copy_directory($src, $dst) 
{ 
    //copy directory with all subdir and files
	$dir = opendir($src);
	if(!file_exists($dst)) 
    	mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) 
    { 
        if (( $file != '.' ) && ( $file != '..' )) 
        { 
            if ( is_dir($src . '/' . $file) ) 
            { 
                copy_directory($src . '/' . $file,$dst . '/' . $file); 
            } 
            else 
            { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}
?>