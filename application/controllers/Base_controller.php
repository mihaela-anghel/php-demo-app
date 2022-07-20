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
	var $page_meta_title 		= "";
	var $page_meta_keywords		= "";
	var $page_meta_description  = "";			
	var $page_header 			= "front/template/header";
	var $page_footer 			= "front/template/footer";	
	var $page_right 			= "front/template/right";
	var $page_left 				= "";
	var $page_title 			= "";
	var $default_lang 			= "en"; // must be the same with directory language name from languages directory
	var $default_lang_id 		= 1;	
	var $default_lang_url		= "en/";
	var $logo_title				= "";
		
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();	
		
		//load libraries
		//=========================================================
		$this->load->library("global_front");
		$this->load->library("locations");		
		$this->load->library('user_agent');		
					
		//set default language
		//=========================================================
		$default_language = $this->global_front->get_default_language();		
		if($default_language)
		{		
			$this->default_lang	 	= $default_language["code"];
			$this->default_lang_id 	= $default_language["lang_id"];				
			$this->default_lang_url	= $default_language["code"]."/";

			//override config system language
			$this->config->set_item('language', $default_language["international_name"]);
		}
		unset($default_language);	
		
		//get all global variables (common in the all pages)
		//======================================================
		$this->global_variables = $this->global_front->get_global_variables($this->default_lang_id);
		if(isset($this->global_variables["current_competition"]))
			$this->current_competition = $this->global_variables["current_competition"];
				
		//load languages file
		//=========================================================																			
		$this->lang->load("general", $this->default_lang);
		$this->lang->load("form_validation", $this->default_lang);
		$this->lang->load('users',$this->default_lang);
		
		// check if user is logged in or not by cookie
		//======================================================		
		if(!isset($_SESSION['auth']) && $this->uri->rsegment(2) != "login_by_cookie")
		{			
			$this->load->helper('cookie');
			if(get_cookie('auth_user_id'))
			{								
				$_SESSION["redirect_url"] = current_url();
								
				header('Location: '.base_url().$this->default_lang_url.'account/login_by_cookie');								
				die();															
			}
		} 
		
		//update session auth
		//======================================================
		if(isset($_SESSION['auth'])) 
		{
			$this->load->model('users_model');	
			$where 	 = " AND active = '1' AND user_id = '".$_SESSION['auth']["user_id"]."' ";
			$users = $this->users_model->get_users($where);
			if($users)
				$_SESSION['auth'] = $users[0];
			else
				unset($_SESSION['auth']);			 		
		}
		
		//set default meta tags		
		//=====================================================
		$this->load->model("pages_model");
		$where 	= " AND lang_id = ".$this->default_lang_id." AND active = '1' AND section = 'home' ";		
		$pages 	= $this->pages_model->get_pages($where);
		if($pages)
		{
			$page 							= $pages[0];
			$this->page_meta_title 			= $page["meta_title"];
			$this->logo_title 				= $this->page_meta_title;			
		}	
								
		//enable profiler
		//=========================================================
		//$this->output->enable_profiler(TRUE);
	}

	
}
