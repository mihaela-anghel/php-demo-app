<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Email_templates extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("email_templates_model");	
		$this->lang->load("admin/email_templates",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List email_templates
	 * 
	 * @param int $offset
	 */
	function index($offset = 0)
	{										
		//controller name
		//======================================================		
		$section_name 	= strtolower(get_class());
		$data			= array();
		$where 			= " AND lang_id = ".$this->admin_default_lang_id."
							AND active = '1' 
							";
		
		//delete all
		//==================================================================
		if(isset($_POST['DeleteSelected']))
		{			
			$aux = 0;
			if(isset($_POST["item"]) && count($_POST["item"]))
			{
				foreach($_POST["item"] as $key=>$email_template_id)
				{
					$this->delete_email_template($email_template_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."email_templates");
			die();
		}				

		//SEARCH		
		//======================================================			
		$search_by = array	
						(	/*array	(	"field_name"	=> "nume",
										"field_label" 	=> $this->lang->line("email_template_nume"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),*/
							array	(	"field_name"	=> "identifier",
										"field_label" 	=> $this->lang->line("email_template_identifier"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),
							/*array	(	"field_name"	=> "status",
										"field_label" 	=> $this->lang->line("status"),
										"field_type"	=> "checkbox",
										"field_values"	=> array("1" => $this->lang->line("active"), "0" => $this->lang->line("inactive"))	
									),*/																																					
							);		
		
		//set search session
		if(isset($_POST["Search"]))		
		{
			$_SESSION[$section_name]["search_by"] = $_POST;
			header("Location: ".admin_url()."email_templates");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."email_templates");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		

			if(isset($search["person_name"]) && !empty($search["person_name"])) 
			{
				$where .= " AND LOWER(t1.person_name) LIKE LOWER('%".$this->db->escape_like_str($search["person_name"])."%') ";										
			}

			if(isset($search["name"]) && !empty($search["name"])) 
			{
				$where .= " AND LOWER(t2.name) LIKE LOWER('%".$this->db->escape_like_str($search["name"])."%') ";										
			}
			
			if(isset($search["status"]) && !empty($search["status"])) 
			{	
				$where .= " AND ( ";
				foreach($search["status"] as $k => $value)	
				{			
					if(isset($search["status"][$k]))
					{					
						if($k > 0)
							$where .= " OR ";
						$where 	.= " active = '".$search["status"][$k]."' ";						
					}	
				}										
				$where .= " ) ";									
			}																						
		}																							
			
		//sort
		//======================================================				 	
		$sort_fields 			= array("t1.email_template_id", "t1.order", "t1.active");
		$default_sort_field 	= "t1.email_template_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->email_templates_model->get_email_templates($where,false,false,false,"count(t1.email_template_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."email_templates/index";
		$config["total_rows"]	= $total_rows;				
		if(isset($_SESSION[$section_name]["per_page"]))	
			$config["per_page"] = $_SESSION[$section_name]["per_page"];
		else											
			$config["per_page"] = $this->setting->item("default_admin_number_per_page");							
		$config["uri_segment"]	= 3;
		$config["cur_page"]		= $offset;		
		$config["first_link"]	= $this->lang->line("first");		
		$config["last_link"] 	= $this->lang->line("last");									
		$this->pagination->initialize($config);		
		$pagination = $this->pagination->create_links();	
			
		//get list
		//======================================================	
		$email_templates = $this->email_templates_model->get_email_templates($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($email_templates as $key => $email_template)
		{												
			/*
			//get number of items						
			$this->db->where("email_template_id", $email_template["email_template_id"]);
			$this->db->from("email_templates_files");
			$items_number =  $this->db->count_all_results();
			$email_templates[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($email_templates)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($email_templates);
		$display_total 		= 	$total_rows;		
		$results_displayed  = 	$this->lang->line("results")." ".$display_from." - ".$display_to." ".$this->lang->line('from')." ".$display_total;						
		
		//send data to view
		//======================================================
		$data["section_name"]		= $section_name;
		$data['search_by']			= $search_by;			
		$data['sort_label'] 		= $sort_label;		
		$data["per_page_select"]	= $this->global_admin->show_per_page_select($section_name,$config["per_page"]);			
		$data["results_displayed"] 	= $results_displayed;		 							
		$data["pagination"]			= $pagination;		
		$data['email_templates'] 			= $email_templates;		
		$data['body'] 				= 'admin/email_templates/list_email_templates';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add email_template
	 */
	function add_email_template()
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
			$this->load->library('form_validation');
			$this->form_validation->set_rules("identifier",		$this->lang->line("email_template_identifier"),		"trim|required");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("email_template_name").$label_language,		"trim|required");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("email_template_description").$label_language,"trim");													
			}							
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				/*
				$where_exist = " AND t2.name		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id." 
							   ";					
				$exist 		= $this->email_templates_model->get_email_templates($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("email_template_exist");				
				else 
				{
					//values
					$values = array(	"identifier" 		=> $_POST["identifier"],
										"add_date"		=> date("Y-m-d H:i:s"),
									);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["identifier"],"-");
						$add_date[$language["lang_id"]] 	= date("Y-m-d H:i:s");
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];											
					}									
					$details = array(   "name" 				=> $_POST["name"],
										"description" 		=> $_POST["description"],
										"url_key" 			=> $url_key,
										"add_date"			=> $add_date,
										"lang_id"			=> $lang_ids	
									);	

					//insert				
					$this->email_templates_model->add_email_template($values, $details);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					//header("Location: ".current_url());
					header("Location: ".admin_url().strtolower(get_class()));
					die();
				}														
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_email_template");
		
		//send data to view
		//=========================================================		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/email_templates/add_email_template";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit email_template
	 * 
	 * @param int $email_template_id
	 */
	function edit_email_template($email_template_id = false)
	{					
		$data = array();	
		if($email_template_id == false) die(); 								
			
		//get email_template and email_template_details
		//=========================================================		
		$email_templates = $this->email_templates_model->get_just_email_templates("AND email_template_id = '".$email_template_id."'");
		if(!$email_templates) die();		
		$email_template = $email_templates[0];
		$array_email_template_details  = $this->email_templates_model->get_just_email_templates_details("AND email_template_id = '".$email_template_id."'");
		foreach($array_email_template_details as $array_email_template_detail)				
			foreach($array_email_template_detail as $field=>$value)			
				$email_template_details[$field][$array_email_template_detail["lang_id"]] = $value;
								
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
			$this->load->library('form_validation');
			$this->form_validation->set_rules("identifier",		$this->lang->line("email_template_identifier"),		"trim|required");
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("email_template_name").$label_language,		"trim|required");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("email_template_description").$label_language,"trim");													
			}							
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();		
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/*
				$where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id."
								 AND t2.email_template_id 	!= '".$email_template_id."' 
							   ";									
				$exist = $this->email_templates_model->get_email_templates($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("email_template_exist");
				else
				{
					//values
					$values = array(	"identifier" 		=> $_POST["identifier"]											
									);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["identifier"],"-");						
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];										
					}									
					$details = array(   "name" 			=> $_POST["name"],
										"description" 		=> $_POST["description"],										
										"url_key" 			=> $url_key,
										"lang_id"			=> $lang_ids										
									);	

					//update				
					$this->email_templates_model->edit_email_template($values, $details, $email_template_id);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					//header("Location: ".current_url());
					header("Location: ".admin_url().strtolower(get_class()));
					die();
				}								 						
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_email_template");										
		
		//send data to view
		//=========================================================
		$data["email_template"] 				= $email_template;
		$data["email_template_details"] 		= $email_template_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/email_templates/edit_email_template";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete email_template
	 * 
	 * @param int $email_template_id
	 */
	function delete_email_template($email_template_id = false, $no_redirect = false)
	{		
		if($email_template_id == false) die(); 
		
		//delete email_template
		//=========================================================
		$this->email_templates_model->delete_email_template($email_template_id);			
				
		//redirect
		//=========================================================
		if(!$no_redirect)
		{			
			?><script type="text/javascript" language="javascript">
			window.history.go(-1);											
			</script><?php	
		}		
	}
	
	/**
	 * Change field value
	 * 
	 * @param int $email_template_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_email_template($email_template_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->email_templates_model->edit_email_template($values,false,$email_template_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
		
	
	/**
	 * Set search session
	 * 
	 * @param string $section			
	 * @param string $search_field		
	 * @param string $search_value
	 */
	function set_search_session($section, $search_field, $search_value)
	{			
		//set search session
		$_SESSION[$section]['search_by'][$search_field] = urldecode($search_value);			
		header("Location: ".admin_url().$section);			
	}
	
	/**
	 * Set session
	 * 
	 * @param string $section 		$section is name of controller
	 * @param string $type			$type can be "sort" or "per_page"
	 * @param string $parameters	$parameters contains multiples parameters separated by "-"	
	 */
	function set_session($section, $type, $parameters = false)
	{		
		// set sort session		
		if($type == "sort")
		{						
			$parameters = str_replace("___",".",$parameters);
			$parameters = explode("-",$parameters);			
			$field = $parameters[0];
			$order = $parameters[1];
			
			$_SESSION[$section]["sort_field"] = $field;						
			$_SESSION[$section]["sort_order"] = $order;
			
			?><script type="text/javascript" language="javascript">		
			window.history.go(-1);											
			</script><?php			
		}
			
		// set items_per_page session
		if($type == "per_page")
		{			
			$per_page = $parameters;						
			$_SESSION[$section]["per_page"] = $per_page;	
			header("Location: ".admin_url().$section);				
		}		
	}
}
