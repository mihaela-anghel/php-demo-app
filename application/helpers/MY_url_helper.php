<?php
	function url_key($value=false,$separator='-')
	{
		if($separator=='-'):
			$delimiter="dash";			
		elseif($separator=='_'):
			$delimiter="underscore";	
		endif;
		
		$value = str_replace(array('ă','î','â','ș','ț','Ă','Î','Â','Ș','Ț'), array('a','i','a','s','t','A','I','A','S','T'), $value );
		$value = url_title($value,$delimiter,true);
		return $value;	
	}		
	if ( ! function_exists('base_path'))
	{
		function base_path()
		{
			$CI =& get_instance();
			return $CI->config->slash_item('base_path');
		}
	}
	if ( ! function_exists('admin_url'))
	{
		function admin_url()
		{
			$CI =& get_instance();
			return $CI->config->slash_item('admin_url');
		}
	}
	if ( ! function_exists('file_url'))
	{
		function file_url()
		{
			$CI =& get_instance();
			return $CI->config->slash_item('file_url');
		}
	}
	function current_url()
	{
		$CI =& get_instance();
		return str_replace("/index.php","",$CI->config->site_url($CI->uri->uri_string()));
	}
	
?>
