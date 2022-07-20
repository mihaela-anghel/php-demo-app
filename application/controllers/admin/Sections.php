<?php
if(!defined("BASEPATH")) exit("No direct script acces allowed");
require_once("Base_controller.php");

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Sections extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("sections_model");	
		$this->lang->load("admin/sections",$this->admin_default_lang);			
	}	
	
	/**
	 * List sections
	 */
	function index()
	{		
		$data = array();
		
		//set where and orderby for listing
		//=========================================================	
		if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
		{
			$where 		= " AND t2.lang_id = '".$this->admin_default_lang_id."'";								
		}
		else
		{
			$where 		= " AND admin_section_id IN (	SELECT admin_section_id 
														FROM admins_roles_sections_rights
														WHERE admin_role_id = ".$_SESSION["admin_auth"]["admin_role_id"]." 
													)
							AND t2.lang_id = '".$this->admin_default_lang_id."'						
							AND active = '1'						
							";									
		}	
		$orderby = " ORDER BY `order` ASC ";			
		
		//get sections for current admin role 
		//=========================================================		
		$sections = $this->sections_model->get_sections($where, $orderby);
		foreach($sections as $key=>$section)		
			$sections[$key]["rights"] = $this->sections_model->get_rights("AND t1.admin_section_id = '".$section["admin_section_id"]."' AND lang_id = '".$this->admin_default_lang_id."' ", "ORDER BY `order` ASC");			
				
		//send data to view
		//=========================================================
		$data["sections"] 	= $sections;		
		$data["body"] 		= "admin/sections/list_sections";
		$this->load->view("admin/template",$data);	
	}
	
	/**
	 * Add section
	 */
	function add_section()
	{		
		$data = array();
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		//determine whether to display the language on labels
		//=========================================================
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
			
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = '';
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("section_name[".$language["lang_id"]."]",		$this->lang->line("section_name").$label_language,	"trim|required");									
			}			
			$this->form_validation->set_rules("section_url",	$this->lang->line("section_url"),	"trim|required");
			$this->form_validation->set_rules("order",			$this->lang->line("order"),			"trim");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),		"trim|required");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$sections = $this->sections_model->get_sections("AND LOWER(admin_section_url) = '".$_POST["section_url"]."' ");				
				if($sections)
					$data["error_message"] = $this->lang->line("section_exist");
				else
				{
					//values
					$values = array(	"admin_section_url"  => $_POST["section_url"],										
										"active"			 => $_POST["active"],
										"order" 			 => $_POST["order"],
									);
					//details
					foreach($languages as $language)					
					{						
						$add_date[$language["lang_id"]] 	 = date("Y-m-d H:i:s");
						$lang_ids[$language["lang_id"]]		 = $language["lang_id"];																	
					}										
					$details = array(   "admin_section_name" => $_POST["section_name"],										
										"add_date"			 => $add_date,
										"lang_id"			 => $lang_ids	
									);				
					//insert
					$this->sections_model->add_section($values, $details);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_add");

					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}	
			}//end form valid									
		}//end form	
		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_section");
		
		//send data to view
		//=========================================================		
		$data["languages"] 				= $languages; 
		$data["show_label_language"] 	= $show_label_language;
		$data["body"] 					= 'admin/sections/add_section';
		$this->load->view("admin/template_iframe",$data);			
	}
	
	/**
	 * Edit section
	 * 
	 * @param int $section_id
	 */
	function edit_section($section_id = false)
	{		
		$data = array();		
		if($section_id == false) die(); 		
		
		//get section and section_details
		//=========================================================		
		$section = $this->sections_model->get_just_sections("AND admin_section_id = '".$section_id."'");
		if(!$section) die();		
		$section = $section[0];
		$array_section_details  = $this->sections_model->get_just_sections_details("AND admin_section_id = '".$section_id."'");
		foreach($array_section_details as $array_section_detail)				
			foreach($array_section_detail as $field=>$value)			
				$section_details[$field][$array_section_detail["lang_id"]] = $value;
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		//if we have more than one language, then display lang code label
		//=========================================================
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
			
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = '';
				if($show_label_language) 
					$label_language = ' (".$language["code"].")';				
				 
				$this->form_validation->set_rules("section_name[".$language["lang_id"]."]",		$this->lang->line("section_name").$label_language,	"trim|required");									
			}			
			$this->form_validation->set_rules("section_url",	$this->lang->line("section_url"),	"trim|required");
			$this->form_validation->set_rules("order",			$this->lang->line("order"),			"trim");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),		"trim|required");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$sections = $this->sections_model->get_sections("	AND LOWER(admin_section_url) = '".$_POST["section_url"]."' 
																	AND t1.admin_section_id != '".$section_id."' ");				
				if($sections)
					$data["error_message"] = $this->lang->line("section_exist");
				else
				{
					//values
					$values = array(	"admin_section_url"  => $_POST["section_url"],										
										"active"			 => $_POST["active"],
										"order" 			 => $_POST["order"],
									);
					//details
					foreach($languages as $language)					
					{						
						$add_date[$language["lang_id"]] 	 = date("Y-m-d h:i:s");
						$lang_ids[$language["lang_id"]]		 = $language["lang_id"];																												
					}										
					$details = array(   "admin_section_name" => $_POST["section_name"],																				
										"lang_id"			 => $lang_ids	
									);	
									
					//update
					$this->sections_model->edit_section($values, $details, $section_id);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_edit");

					//redirect					
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();					
				}	
			}						
		}		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_section");
		
		//send data to view
		//=========================================================
		$data["section"] 				= $section;			
		$data["section_details"] 		= $section_details;
		$data["languages"] 				= $languages; 
		$data["show_label_language"] 	= $show_label_language;
		$data["body"] 					= "admin/sections/edit_section";
		$this->load->view("admin/template_iframe",$data);		
	}
	
	/**
	 * Delete section
	 * 
	 * @param int $section_id
	 */
	function delete_section($section_id = false)
	{
		if($section_id == false) die();

		//delete section's rights
		//=========================================================					
		$rights = $this->sections_model->get_rights("AND t1.admin_section_id = '".$section_id."' AND lang_id = '".$this->admin_default_lang_id."'  ");		
		foreach($rights as $right)				
			$this->sections_model->delete_right($right["admin_right_id"]);
						
		//delete section
		//=========================================================
		$this->sections_model->delete_section($section_id);				

		//redirect	
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);												
		</script><?php		
	}
	
	/**
	 * Change field value
	 * 
	 * @param int $section_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_section($section_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->sections_model->edit_section($values,false,$section_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Add right to custom section
	 * 
	 * @param int $section_id
	 */
	function add_right($section_id = false)
	{		
		$data = array();
		if($section_id == false) die();
		
		//get section
		//=========================================================		
		$section = $this->sections_model->get_just_sections("AND admin_section_id = '".$section_id."'");
		if(!$section) die();		
		$section = $section[0];
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		//if we have more than one language, then display lang code label
		//=========================================================
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
			
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = '';
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("right_name[".$language["lang_id"]."]",		$this->lang->line("right_name").$label_language,	"trim|required");									
			}			
			$this->form_validation->set_rules("right_url",		$this->lang->line("right_url"),		"trim|required");
			$this->form_validation->set_rules("order",			$this->lang->line("order"),			"trim");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),		"trim|required");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$rights = $this->sections_model->get_rights("AND LOWER(admin_right_url) = '".$_POST["right_url"]."' AND admin_section_id = '".$section_id."'");				
				if($rights)
					$data["error_message"] = $this->lang->line("right_exist");
				else
				{
					//values
					$values = array(	"admin_section_id"	 => $section_id,
										"admin_right_url" 	 => $_POST["right_url"],										
										"active"			 => $_POST["active"],
										"order" 			 => $_POST["order"],
									);
					//details
					foreach($languages as $language)					
					{						
						$add_date[$language["lang_id"]] 	 = date("Y-m-d h:i:s");
						$lang_ids[$language["lang_id"]]		 = $language["lang_id"];																	
					}										
					$details = array(   "admin_right_name" => $_POST["right_name"],										
										"add_date"			 => $add_date,
										"lang_id"			 => $lang_ids	
									);				
					//insert
					$this->sections_model->add_right($values, $details);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_add");

					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}	
			}//end form valid									
		}//end form					
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_right");
		
		//send data to view
		//=========================================================
		$data["section"]				= $section;		
		$data["languages"] 				= $languages; 
		$data["show_label_language"] 	= $show_label_language;
		$data["body"] 					= 'admin/sections/add_right';
		$this->load->view("admin/template_iframe",$data);			
	}
	
	/**
	 * Edit right
	 * 
	 * @param int $right_id
	 */
	function edit_right($right_id = false)
	{		
		$data = array();		
		if($right_id == false) die();
		
		//get right and right_details
		//=========================================================		
		$right = $this->sections_model->get_just_rights("AND admin_right_id = '".$right_id."'");
		if(!$right) die();		
		$right = $right[0];
		$array_right_details  = $this->sections_model->get_just_rights_details("AND admin_right_id = '".$right_id."'");
		foreach($array_right_details as $array_right_detail)				
			foreach($array_right_detail as $field=>$value)			
				$right_details[$field][$array_right_detail["lang_id"]] = $value;
				
		//get section
		//=========================================================		
		$section = $this->sections_model->get_just_sections("AND admin_section_id = '".$right["admin_section_id"]."'");
		if(!$section) die();		
		$section = $section[0];				
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		//if we have more than one language, then display lang code label
		//=========================================================
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
			
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("right_name[".$language["lang_id"]."]",		$this->lang->line("right_name").$label_language,	"trim|required");									
			}			
			$this->form_validation->set_rules("right_url",		$this->lang->line("right_url"),		"trim|required");
			$this->form_validation->set_rules("order",			$this->lang->line("order"),			"trim");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),		"trim|required");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$rights = $this->sections_model->get_rights("	AND LOWER(admin_right_url) = '".$_POST["right_url"]."' 
																AND t1.admin_right_id != '".$right_id."'
																AND admin_section_id = '".$section["admin_section_id"]."'
																");				
				if($rights)
					$data["error_message"] = $this->lang->line("right_exist");
				else
				{
					//values
					$values = array(	"admin_right_url"  	 => $_POST["right_url"],										
										"active"			 => $_POST["active"],
										"order" 			 => $_POST["order"],
									);
					//details
					foreach($languages as $language)					
					{						
						$add_date[$language["lang_id"]] 	 = date("Y-m-d H:i:s");
						$lang_ids[$language["lang_id"]]		 = $language["lang_id"];																												
					}										
					$details = array(   "admin_right_name" 	=> $_POST["right_name"],																				
										"lang_id"			=> $lang_ids	
									);	
									
					//update
					$this->sections_model->edit_right($values, $details, $right_id);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_edit");

					//redirect					
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php	
					die();				
				}	
			}						
		}		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_right");
				
		//send data to view
		//=========================================================
		$data["right"] 				= $right;			
		$data["right_details"] 		= $right_details;
		$data["section"]			= $section;	
		$data["languages"] 			= $languages; 
		$data["show_label_language"]= $show_label_language;
		$data["body"] 				= "admin/sections/edit_right";
		$this->load->view("admin/template_iframe",$data);		
	}		
	
	/**
	 * Delete right
	 * 
	 * @param int $right_id
	 */
	function delete_right($right_id = false)
	{
		if($right_id == false) die(); 		
		$this->sections_model->delete_right($right_id);
		
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php
	}			
}
