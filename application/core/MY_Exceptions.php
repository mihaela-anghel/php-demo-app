<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class MY_Exceptions extends CI_Exceptions 
{ 	
    function show_404($page = '', $log_error = TRUE) 
    {        	
		$config =& get_config();
		
		/*Header( "HTTP/1.1 301 Moved Permanently" );
		Header( "Location: ".$config["base_url"] );
		die();*/
						
	    $error_page_url = $config["base_url"]."error_404"; 
        $content 		= false; 
 
        // See if we can get the contents via a couple of methods 
        if(function_exists('file_get_contents') && ini_get('allow_url_fopen')) 
        { 
            $content = @file_get_contents($error_page_url); 
        } 
        elseif(function_exists('curl_init')) 
        { 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $error_page_url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $content = curl_exec($ch); 
            curl_close($ch); 
        } 
 
        // If we managed to get some content show it otherwise use CI default 
        if(!empty($content)) 
        { 
            if($log_error)             
                log_message('error', '404 Page Not Found --> '.$page);             
 
            set_status_header(404); 
            echo $content; 
            exit; 
        } 
        else 
        {
            parent::show_404($page, $log_error); 
        }         
		die();
    } 
}
?>