<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Error_404 extends Base_controller  
{			
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();	

		$this->page_right = "";
		$this->page_left  = "";
	}
	
	/**
	 * Main page
	 */
	function index()
	{																														
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							  	);
		$navigation[1] 	= array( 	'name' 	=>	"Error 404",
									'url'	=>	""
							 	 );					  
							  
		//meta tags	
		//===========================================================						
		$this->page_title 				= "Error 404";		
		$this->page_meta_title 			= $this->page_title;											
							  
		//send data to view
		//===========================================================
		//$data['navigation'] 		= $navigation;																						
		$data['body'] 				= "front/error_404";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);		
	}			
}
