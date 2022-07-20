<?php
if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once("Base_controller.php");

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Login extends Base_controller 
{			
	function __construct()
	{
		parent::__construct();		
		
		$this->load->model("admins_model");		
		$this->lang->load("admin/admins",$this->admin_default_lang);	
	}
	
	/**
	 * Login area
	 */
	function index()
	{			
		$data = array();
		
		if(isset($_SESSION["admin_auth"]))
		{	
			header("Location: ".admin_url()."home");
			die();
		}
				
		if(isset($_POST["AdminLogin"]))	
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_username",$this->lang->line("username"),"xss_clean|trim|required");
			$this->form_validation->set_rules("admin_password",$this->lang->line("password"),"xss_clean|trim|required|callback_check_admin_username_and_password");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
						
			//if form is valid
			//=========================================================
			if($form_is_valid)			
			{
				//get admin

				$where = " 	AND LOWER(admin_username) 	= '".strtolower($this->db->escape_str($_POST["admin_username"]))."' 
							AND admin_password 			= '".md5($_POST["admin_password"])."'
							AND active 					= '1'
							";
				   
				$entries = $this->admins_model->get_admins($where);						
		
				//set session				
				$_SESSION["admin_auth"]					 = $entries[0];				
				$_SESSION["admin_auth"]["admin_role"]	 = $this->admins_model->get_role($entries[0]["admin_id"]);					
				
				//redirect to home				
				header("Location: ".admin_url()."home");
				die();									
			}			
		}		

		//send data to view
		//=========================================================
		$this->load->view("admin/login",$data);	
	}
	
	/**
	 * Logout
	 */
	function logout()
	{
		//admin logout		
		if(isset($_SESSION["admin_auth"]))	
			unset($_SESSION["admin_auth"]);
		
		//redirect			
		header("Location: ".admin_url());
	}	
	
	/**
	 * Check if credentials are correct
	 * 
	 * @return bool
	 */
	function check_admin_username_and_password()
	{
		if(!isset($_POST["admin_username"]) || !isset($_POST["admin_password"])  )
		{
			return false;
			die();
		}	
			
		$where = " 	AND LOWER(admin_username) 	= '".strtolower($this->db->escape_str($_POST["admin_username"]))."' 
					AND admin_password 			= '".md5($_POST["admin_password"])."'
					AND active 					= '1'
					";
				   
		$entries = $this->admins_model->get_admins($where);
				
		if($entries)
			return true;
		else
		{				
			$this->form_validation->set_message("check_admin_username_and_password", $this->lang->line("admin_not_exist"));
			return false;
		}	
	}
}
