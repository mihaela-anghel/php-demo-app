<?php
if(!defined("BASEPATH")) exit("No direct script acces allowed");
require_once("Base_controller.php");

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Settings extends Base_controller 
{						
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("settings_model");	
		$this->lang->load("admin/settings",$this->admin_default_lang);				
		
		//set access
		//=========================================================		
		$access = array();
		if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
			$access["add_setting"]	= true;				
		else
			$access["add_setting"]	= false;	
		$this->access = $access;
	}
	
	/**
	 * List settings
	 */
	function index()
	{																				
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" );		
		
		//get settings
		//=========================================================
		$settings 	= array(); 				
		$where 	  	= " AND is_multilanguage = '0' ";
		if(!$this->access['add_setting'])
			$where .= "AND active = '1'";
		$orderby  	= " ORDER BY `order` ASC ";
		$settings 	= $this->settings_model->get_just_settings($where,$orderby);
		
		//get settings details
		//=========================================================
		$settings_details		= array();
		$where 					= " AND is_multilanguage = '1' ";
		$orderby 				= " ORDER BY `order` ASC ";								
		$array_settings_details	= $this->settings_model->get_settings($where,$orderby);								
		$settings_ids 			= array();
		foreach($array_settings_details as $array_setting_detail)	
		{			
			if(!in_array($array_setting_detail["setting_id"],$settings_ids))
				array_push($settings_ids,$array_setting_detail["setting_id"]);						
		}
		foreach($settings_ids as $key=>$setting_id)
		{			
			$where 					= " AND t1.setting_id = ".$setting_id." ";						
			$array_settings_details	= $this->settings_model->get_settings($where);		
							
			foreach($array_settings_details as $array_setting_detail)				
				foreach($array_setting_detail as $field=>$value)
				{			
					if(in_array($field,array("setting_id","is_multilanguage","name","order","html_textarea","active")))
						$settings_details1[$field] = $value;
					else	
						$settings_details1[$field][$array_setting_detail["lang_id"]] = $value;
				}	
			$settings_details[$key] = $settings_details1;	
		}									
		
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
			foreach($settings as $setting)
			{	
				$this->form_validation->set_rules( "value[".$setting["setting_id"]."]", $setting["description"], "trim" );												  			
			}
			foreach($languages as $language)
			{
				//if multiple languages are active then display language code after field label
				if($show_label_language) 
					$label_language = " (".$language["code"].")";
				else
					$label_language = "";
				
				foreach($settings_details as $setting_detail)	
					$this->form_validation->set_rules(	"value_detail[".$setting_detail["setting_id"]."][".$language["lang_id"]."]",
														(isset($setting_detail["description"][$language["lang_id"]])?$setting_detail["description"][$language["lang_id"]]:"").$label_language,
														"trim"
													);					
			}																					
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();				
					
			//if form valid
			//=========================================================				
			if($form_is_valid)
			{																								
				//values
				if(isset($_POST["value"]))
				{
					foreach($_POST["value"] as $setting_id => $post_setting)
					{
						$values 	= array("value"	=> $post_setting);																																				
						$details 	= false;
	
						$this->settings_model->edit_setting($values, $details, $setting_id);
					}	
				}
				
				//details
				if(isset($_POST["value_detail"]))
				{
					foreach($_POST["value_detail"] as $setting_id => $post_setting_detail)
					{
						$values = false;
						
						foreach($languages as $language)					
							$lang_ids[$language["lang_id"]]	= $language["lang_id"];
						
						$details = array(   "value" 	=> $post_setting_detail,									    
											"lang_id"	=> $lang_ids										
										);
						$this->settings_model->edit_setting($values, $details, $setting_id);
					}	
				}
				
				//done message
				$_SESSION["done_message"] = $this->lang->line("done_message_edit");
									
				//redirect									
				header("Location: ".$this->config->item("admin_url")."settings");
				die();
														
			}//end form valid						
		}//end form			

		//send data to view
		//=========================================================	
		$data["settings"]			 	= $settings;
		$data["settings_details"] 		= $settings_details;
		$data["show_label_language"]	= $show_label_language;
		$data["languages"] 				= $languages;	
		$data["body"]					= "admin/settings/list_settings";
		$this->load->view("admin/template",$data);	
	}
	/**
	 * Add setting
	 */
	function add_setting()
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
			$this->form_validation->set_rules("name",$this->lang->line("setting"),"trim|required");
			$this->form_validation->set_rules("is_multilanguage",$this->lang->line("is_multilanguage"),"");						
			if($_POST["is_multilanguage"] == '0')
			{
				$this->form_validation->set_rules("description",$this->lang->line("description"),"trim|required");
				$this->form_validation->set_rules("value",$this->lang->line("value"),"trim|required");
			}
			elseif($_POST["is_multilanguage"] == '1')
			{
				foreach($languages as $language)
				{
					//if we have more than one language, then make a string with lang code label
					if($show_label_language) 
						$label_language = " (".$language["code"].")";
					else
						$label_language = "";
							
					$this->form_validation->set_rules("description_details[".$language["lang_id"]."]",$this->lang->line("description").$label_language,"trim|required");
					$this->form_validation->set_rules("value_details[".$language["lang_id"]."]",$this->lang->line("value").$label_language,"trim|required");										
				}	
			}
			$this->form_validation->set_rules("order",$this->lang->line("order"),"trim");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$where_exist 	= " AND name = '".$_POST["name"]."' ";				
				$exist 			= $this->settings_model->get_settings($where_exist);								
				if($exist)
					$data["error_message"] = $this->lang->line("setting_exists");
				else	
				{
					//values and details
					if($_POST["is_multilanguage"] == '0')	
					{
						$values = array("name" 				=> $_POST["name"],
										"is_multilanguage" 	=> $_POST["is_multilanguage"],			
										"description" 		=> $_POST["description"],
										"value" 			=> $_POST["value"],			
										"order" 			=> $_POST["order"]
										);
						$details = false;				
					}
					if($_POST["is_multilanguage"] == '1')	
					{
						$values = array("name" 				=> $_POST["name"],
										"is_multilanguage" 	=> $_POST["is_multilanguage"],														
										"order" 			=> $_POST["order"]
										);														
						
						foreach($languages as $language)					
						$lang_ids[$language["lang_id"]]	= $language["lang_id"];
											
						$details = array("value" 		=> $_POST["value_details"],									 		
										 "description" 	=> $_POST["description_details"],										
										 "lang_id"		=> $lang_ids	
										 );		
					}	

					//insert
					$this->settings_model->add_setting($values, $details);
					
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
		$this->page_title = $this->lang->line("add_setting");
		
		//send data to view
		//=========================================================		
		$data["languages"] 				= $languages; 
		$data["show_label_language"] 	= $show_label_language;
		$data["body"] 					= "admin/settings/add_setting";
		$this->load->view("admin/template_iframe",$data);			
	}
	
	/**
	 * Edit setting
	 * 
	 * @param int $setting_id
	 */
	function edit_setting($setting_id = false)
	{		
		$data = array();		
		if($setting_id == false) die(); 	
		
		//get setting and setting_details
		//=========================================================		
		$setting = $this->settings_model->get_just_settings("AND setting_id = '".$setting_id."'");
		if(!$setting) die();		
		$setting = $setting[0];
		$array_setting_details  = $this->settings_model->get_just_settings_details("AND setting_id = '".$setting_id."'");
		$setting_details = array();
		foreach($array_setting_details as $array_setting_detail)				
			foreach($array_setting_detail as $field=>$value)			
				$setting_details[$field][$array_setting_detail["lang_id"]] = $value;
						
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
		if(isset($_POST["Edit"]))
		{			
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("name",$this->lang->line("setting"),"trim|required");
			$this->form_validation->set_rules("is_multilanguage",$this->lang->line("is_multilanguage"),"");						
			if($_POST["is_multilanguage"] == '0')
			{
				$this->form_validation->set_rules("description",$this->lang->line("description"),"trim|required");
				$this->form_validation->set_rules("value",$this->lang->line("value"),"trim|required");
			}
			elseif($_POST["is_multilanguage"] == '1')
			{
				foreach($languages as $language)
				{
					//if we have more than one language, then make a string with lang code label
					if($show_label_language) 
						$label_language = " (".$language["code"].")";
					else
						$label_language = "";
							
					$this->form_validation->set_rules("description_details[".$language["lang_id"]."]",$this->lang->line("description").$label_language,"trim|required");
					$this->form_validation->set_rules("value_details[".$language["lang_id"]."]",$this->lang->line("value").$label_language,"trim|required");										
				}	
			}
			$this->form_validation->set_rules("order",$this->lang->line("order"),"trim");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================			
			if($form_is_valid)
			{
				$where_exist 	= " AND name = '".$_POST["name"]."' AND t1.setting_id != ".$setting_id." ";				
				$exist 			= $this->settings_model->get_settings($where_exist);								
				if($exist)
					$data["error_message"] = $this->lang->line("setting_exists");
				else	
				{
					//values and details
					if($_POST["is_multilanguage"] == '0')	
					{
						$values = array("name" 				=> $_POST["name"],
										"is_multilanguage" 	=> $_POST["is_multilanguage"],			
										"description" 		=> $_POST["description"],
										"value" 			=> $_POST["value"],			
										"order" 			=> $_POST["order"]
										);
						$details = false;				
					}
					if($_POST["is_multilanguage"] == '1')	
					{
						$values = array("name" 				=> $_POST["name"],
										"is_multilanguage" 	=> $_POST["is_multilanguage"],																								
										"description" 		=> "",
										"value" 			=> "",	
										"order" 			=> $_POST["order"],
										);														
						
						foreach($languages as $language)					
						$lang_ids[$language["lang_id"]]	= $language["lang_id"];
											
						$details = array("value" 		=> $_POST["value_details"],									 		
										 "description" 	=> $_POST["description_details"],										
										 "lang_id"		=> $lang_ids	
										 );		
					}	

					//update
					$this->settings_model->edit_setting($values, $details, $setting_id);
					
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
		$this->page_title = $this->lang->line("edit_setting");
		
		//send data to view
		//=========================================================
		$data["setting"] 				= $setting;			
		$data["setting_details"] 		= $setting_details;		
		$data["languages"] 				= $languages; 
		$data["show_label_language"] 	= $show_label_language;
		$data["body"] 					= "admin/settings/edit_setting";
		$this->load->view("admin/template_iframe",$data);			
	}
		
	/**
	 * Delete setting
	 * 
	 * @param int $setting_id
	 */
	function delete_setting($setting_id = false)
	{
		if($setting_id == false) die();
			
		//delete setting
		//=========================================================
		$this->settings_model->delete_setting($setting_id);				

		//redirect	
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);												
		</script><?php		
	}
		
	/**
	 * Change field value
	 * 
	 * @param int 		$setting_id
	 * @param string 	$field
	 * @param mixed 	$new_value
	 */
	function change_setting($setting_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->settings_model->edit_setting($values,false,$setting_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
}
