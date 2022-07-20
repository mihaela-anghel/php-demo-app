<?php
if(!defined("BASEPATH")) exit("No direct script access allowed");
session_start();

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Base_controller extends CI_Controller 
{
	var $page_meta_title 		= "Admin QWERTY";
	var $page_meta_keywords		= "";
	var $page_meta_description  = "";			
	var $page_header 			= "admin/template/header";
	var $page_footer 			= "admin/template/footer";	
	var $page_title 			= "";
	var $admin_default_lang 	= "en"; // must be the same with directory language name from languages directory
	var $admin_default_lang_id 	= 2;		
		
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
				
		//if is not logged in we will redirect to login
		//=========================================================
		if  (	!isset($_SESSION["admin_auth"]) && 
				(	
					(
						$this->uri->segment("2") != "login" &&
						$this->uri->segment("3") != ""
					) ||
					(
						$this->uri->segment("2") != "login" &&
						$this->uri->segment("3") == ""
					) ||
					$this->uri->segment("2") == ""
				)
			)		
		{	
			if($this->uri->rsegment('2') != 'view_diplama')
			{
				header("Location: ".admin_url()."login");
				die();
			}
		}	
		
		//load libraries
		//=========================================================		
		$this->load->library("global_admin");
			
		//set admin default language
		//=========================================================
		$admin_default_language = $this->global_admin->get_admin_default_language();		
		if($admin_default_language)
		{		
			$this->admin_default_lang	 = $admin_default_language["code"];
			$this->admin_default_lang_id = $admin_default_language["lang_id"];	

			//override config system language
			$this->config->set_item('language', $admin_default_language["international_name"]);
		}
		unset($admin_default_language);	
				
		//load languages file
		//=========================================================																	
		$this->lang->load("admin/general", $this->admin_default_lang);
		$this->lang->load("form_validation", $this->admin_default_lang);			
				 							
		//check if have access to section or rights(pages from this sections)
		//=========================================================		
		if(isset($_SESSION["admin_auth"]) && $_SESSION["admin_auth"]["admin_role"] != "webmaster")
		{
			if($this->uri->segment("3") == "" || $this->uri->segment("3") == "index")
			{
				$type 	= "section";
				$url 	= $this->uri->segment("2");
				if($url == "") 
					$url = "home";
			}
			else 
			{
				$type	= 'right';
				$url 	= $this->uri->segment("2")."/".$this->uri->segment("3");	
			}			
			if(!$this->global_admin->has_access($type, $url))
			{				
				echo '<h2>'.$this->lang->line("private_access").'</h2>';
				echo '<p><a href = "'.admin_url().'home" >'.$this->lang->line("back_to").' '.$this->lang->line("cms").'</a></p>';
				die();				
			}					
		}	
		
		//set page title (title of section)
		//=========================================================
		if($this->uri->segment("2") == "home" || $this->uri->segment("2") == "")
			$this->page_title = $this->lang->line("home");
		else	
			$this->page_title = $this->global_admin->get_section_name($this->uri->segment("2"));
			
		//set page title (get right_name from sections or from lang file)
		//=========================================================
		if($this->uri->segment("3") != "" && $this->uri->segment("3") == "index")
		{
			$right_name = $this->global_admin->get_right_name($this->uri->segment("3"));
			if($right_name)
				$this->page_title = $right_name;
			else if($this->lang->line($this->uri->segment("3")))
				$this->page_title = $this->lang->line($this->uri->segment("3"));
		}		
				

		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		foreach($languages as $language)
		{								
			add_column_if_not_exist("banners","name_".$language["code"],"VARCHAR(255) NOT NULL");
			add_column_if_not_exist("banners","description_".$language["code"],"TINYTEXT NOT NULL");
			add_column_if_not_exist("pages","active_".$language["code"],"ENUM(  '0',  '1' ) NOT NULL DEFAULT  '1'");
		}
		$this->admin_languages = $languages;
		
		//enable profiler
		//=========================================================
		//$this->output->enable_profiler(TRUE);
	}	

	/**
	 * Set access
	 * 
	 * @param string $class_name
	 * @return array
	 */
	protected function set_access($class_name)
	{
		//set access
		//=========================================================				
		$admin_access 	= array();		
		$class_methods  = get_class_methods($class_name);						
		foreach($class_methods as $key=>$value)		
			if($key >= 1 && $key+1 < count($class_methods))					
				$admin_access[$value] = $this->global_admin->has_access("right",strtolower($class_name)."/".$value);														
		return $admin_access;		
	}
}
