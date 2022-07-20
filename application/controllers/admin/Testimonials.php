<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Testimonials extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("testimonials_model");	
		$this->lang->load("admin/testimonials",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List testimonials
	 * 
	 * @param int $offset
	 */
	function index($offset = 0)
	{										
		//controller name
		//======================================================		
		$section_name 	= strtolower(get_class());
		$data			= array();
		$where 			= " AND lang_id = ".$this->admin_default_lang_id." ";
		
		//delete all
		//==================================================================
		if(isset($_POST['DeleteSelected']))
		{			
			$aux = 0;
			if(isset($_POST["item"]) && count($_POST["item"]))
			{
				foreach($_POST["item"] as $key=>$testimonial_id)
				{
					$this->delete_testimonial($testimonial_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."testimonials");
			die();
		}				

		//SEARCH		
		//======================================================			
		$search_by = array	
						(	/*array	(	"field_name"	=> "nume",
										"field_label" 	=> $this->lang->line("testimonial_nume"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),*/
							array	(	"field_name"	=> "person_name",
										"field_label" 	=> $this->lang->line("testimonial_person_name"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),
							array	(	"field_name"	=> "status",
										"field_label" 	=> $this->lang->line("status"),
										"field_type"	=> "checkbox",
										"field_values"	=> array("1" => $this->lang->line("active"), "0" => $this->lang->line("inactive"))	
									),																																					
							);		
		
		//set search session
		if(isset($_POST["Search"]))		
		{
			$_SESSION[$section_name]["search_by"] = $_POST;
			header("Location: ".admin_url()."testimonials");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."testimonials");
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
		$sort_fields 			= array("t1.testimonial_id", "t1.order", "t1.active");
		$default_sort_field 	= "t1.testimonial_id"; 
		$default_sort_dir 		= "desc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->testimonials_model->get_testimonials($where,false,false,false,"count(t1.testimonial_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."testimonials/index";
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
		$testimonials = $this->testimonials_model->get_testimonials($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($testimonials as $key => $testimonial)
		{												
			/*
			//get number of items						
			$this->db->where("testimonial_id", $testimonial["testimonial_id"]);
			$this->db->from("testimonials_files");
			$items_number =  $this->db->count_all_results();
			$testimonials[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($testimonials)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($testimonials);
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
		$data['testimonials'] 			= $testimonials;		
		$data['body'] 				= 'admin/testimonials/list_testimonials';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add testimonial
	 */
	function add_testimonial()
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
			$this->form_validation->set_rules("person_name",		$this->lang->line("testimonial_person_name"),		"trim|required");
			$this->form_validation->set_rules("function",			$this->lang->line("testimonial_function"),			"trim");
			$this->form_validation->set_rules("company",			$this->lang->line("testimonial_company"),			"trim");			
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				//$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("testimonial_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("testimonial_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("testimonial_description").$label_language,"trim");
				//$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				//$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				//$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}							
			$this->form_validation->set_rules("active",			$this->lang->line("status"),	"trim|required");			
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
				$exist 		= $this->testimonials_model->get_testimonials($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("testimonial_exist");				
				else 
				{
					//values
					$values = array(	"person_name" 	=> $_POST["person_name"],
										"function" 		=> $_POST["function"],
										"company" 		=> $_POST["company"],
										"active" 		=> $_POST["active"],
										"add_date"		=> date("Y-m-d H:i:s"),
									);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["person_name"],"-");
						$add_date[$language["lang_id"]] 	= date("Y-m-d H:i:s");
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];											
					}									
					$details = array(   //"name" 			=> $_POST["name"],
										//"abstract" 		=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],
										//"meta_title" 		=> $_POST["meta_title"],
										//"meta_description" 	=> $_POST["meta_description"],
										//"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"add_date"			=> $add_date,
										"lang_id"			=> $lang_ids	
									);	

					//insert				
					$this->testimonials_model->add_testimonial($values, $details);
					
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
		$this->page_title = $this->lang->line("add_testimonial");
		
		//send data to view
		//=========================================================		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/testimonials/add_testimonial";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit testimonial
	 * 
	 * @param int $testimonial_id
	 */
	function edit_testimonial($testimonial_id = false)
	{					
		$data = array();	
		if($testimonial_id == false) die(); 								
			
		//get testimonial and testimonial_details
		//=========================================================		
		$testimonials = $this->testimonials_model->get_just_testimonials("AND testimonial_id = '".$testimonial_id."'");
		if(!$testimonials) die();		
		$testimonial = $testimonials[0];
		$array_testimonial_details  = $this->testimonials_model->get_just_testimonials_details("AND testimonial_id = '".$testimonial_id."'");
		foreach($array_testimonial_details as $array_testimonial_detail)				
			foreach($array_testimonial_detail as $field=>$value)			
				$testimonial_details[$field][$array_testimonial_detail["lang_id"]] = $value;
								
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
			$this->form_validation->set_rules("person_name",		$this->lang->line("testimonial_person_name"),		"trim|required");
			$this->form_validation->set_rules("function",			$this->lang->line("testimonial_function"),			"trim");
			$this->form_validation->set_rules("company",			$this->lang->line("testimonial_company"),			"trim");			
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				//$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("testimonial_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("testimonial_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("testimonial_description").$label_language,"trim");
				//$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				//$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				//$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}							
			$this->form_validation->set_rules("active",			$this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();		
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/*
				$where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id."
								 AND t2.testimonial_id 	!= '".$testimonial_id."' 
							   ";									
				$exist = $this->testimonials_model->get_testimonials($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("testimonial_exist");
				else
				{
					//values
					$values = array(	"person_name" 	=> $_POST["person_name"],
										"function" 		=> $_POST["function"],
										"company" 		=> $_POST["company"],
										"active"		=> $_POST["active"]		
									);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["person_name"],"-");						
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];										
					}									
					$details = array(   //"name" 			=> $_POST["name"],
										//"abstract" 		=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],										
										//"meta_title" 		=> $_POST["meta_title"],
										//"meta_description"=> $_POST["meta_description"],
										//"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"lang_id"			=> $lang_ids										
									);	

					//update				
					$this->testimonials_model->edit_testimonial($values, $details, $testimonial_id);
					
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
		$this->page_title = $this->lang->line("edit_testimonial");										
		
		//send data to view
		//=========================================================
		$data["testimonial"] 				= $testimonial;
		$data["testimonial_details"] 		= $testimonial_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/testimonials/edit_testimonial";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete testimonial
	 * 
	 * @param int $testimonial_id
	 */
	function delete_testimonial($testimonial_id = false, $no_redirect = false)
	{		
		if($testimonial_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $testimonial_id, true);	
		$this->delete_file("banner", $testimonial_id, true);					
		
		//delete testimonial
		//=========================================================
		$this->testimonials_model->delete_testimonial($testimonial_id);			
				
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
	 * @param int $testimonial_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_testimonial($testimonial_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->testimonials_model->edit_testimonial($values,false,$testimonial_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $testimonial_id
	 */
	function upload_file($type, $testimonial_id)
	{		
		$data = array();	
		if($testimonial_id == false) die();

		//get testimonial
		//=========================================================
		$testimonials 	= $this->testimonials_model->get_testimonials(" AND t1.testimonial_id = '".$testimonial_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$testimonials) die();
		$testimonial		= $testimonials[0];
		
		//upload form
		//=========================================================
		$this->lang->load("upload",$this->admin_default_lang);
		if(isset($_POST["Upload"]))
		{	
			//upload image
			//=========================================================
			if($type  == "image") 
			{																					
				//config upload file
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/testimonials/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($testimonial["url_key"])?$testimonial["url_key"]:"image_".$testimonial_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $testimonial_id, $no_redirect = true);	
						
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 600;
					$config["height"] 			= 6000;
					if($file_data["image_width"] <= $config["width"])
						$config["width"] 		= $file_data["image_width"]; 											
					//load image manupulation library
					$this->load->library("image_lib");
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();
					
					//create thumb					
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["create_thumb"] 	= TRUE;
					$config["thumb_marker"] 	= "_th";
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 300;
					$config["height"] 			= 3000;																														
					//load image manupulation library
					$this->load->library("image_lib");					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("testimonial_id", $testimonial_id);
					$this->db->update("testimonials",$values);	
										
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}
				else
				{
					//error message
					$data["error_message"] = $this->upload->display_errors("","");						
				}																
			}//end image
			
			//upload banner
			//=========================================================
			if($type  == "banner") 
			{																					
				//config upload file
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/testimonials/banners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($testimonial["url_key"])?$testimonial["url_key"]:"banner_".$testimonial_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $testimonial_id, $no_redirect = true);	
					
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 500;
					$config["height"] 			= 5000;
					if($file_data["image_width"] <= $config["width"])
						$config["width"] 		= $file_data["image_width"]; 											
					//load image manupulation library
					$this->load->library("image_lib");
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();										

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("testimonial_id", $testimonial_id);
					$this->db->update("testimonials",$values);	
										
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}
				else
				{
					//error message
					$data["error_message"] = $this->upload->display_errors("","");						
				}																
			}//end banner
			
		}//end post

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("upload_file");
		
		//send data to view
		//=========================================================
		$data["type"]	= $type;
		$data["testimonial"] = $testimonial;			
		$data["body"]  	= "admin/testimonials/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $testimonial_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $testimonial_id, $no_redirect = false)
	{
		if($testimonial_id == false) die();
		
		//get testimonial
		//=========================================================
		$testimonials 	= $this->testimonials_model->get_testimonials(" AND t1.testimonial_id = '".$testimonial_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$testimonials) die();
		$testimonial		= $testimonials[0];			
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $testimonial["image"];
			$file_path 	= base_path()."uploads/testimonials/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($testimonial["image"]);
			$file_path 	= base_path()."uploads/testimonials/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("testimonial_id" => $testimonial_id));
			$this->db->update("testimonials",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $testimonial["banner"];
			$file_path 	= base_path()."uploads/testimonials/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("testimonial_id" => $testimonial_id));
			$this->db->update("testimonials",array($type => ""));	
		}

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
