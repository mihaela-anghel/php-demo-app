<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Home extends Base_controller 
{			
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		$this->lang->load("admin/admins",$this->admin_default_lang);					
	}
		
	/**
	 * Main page
	 */
	function index()
	{						
		//send data to view
		//=========================================================
		$data["body"] = "admin/home";
		$this->load->view("admin/template",$data);	
	}	
	
	/**
	 * Generate Google XML sitemap
	 */
	function generate_google_xml_sitemap()
	{		
		$this->load->library('sitemap');
		$this->sitemap->sitemap_xml_generator();			
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		 			
	}
}
