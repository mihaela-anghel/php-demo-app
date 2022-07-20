<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Qwertys extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("qwertys_model");	
		$this->lang->load("admin/qwertys",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List qwertys
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
				foreach($_POST["item"] as $key=>$qwerty_id)
				{
					$this->delete_qwerty($qwerty_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."qwertys");
			die();
		}				

		//SEARCH		
		//======================================================
		/*
		//create categories select 		
		$this->load->model('categories_model');	
		$this->load->library('tree');
		$orderby_ 	= " ORDER BY `order` asc ";	
		$where_ 	= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields_ 	= " t1.category_id, t1.parent_id, t2.name ";
		$categories = $this->categories_model->get_categories($where_,$orderby_,false,false,$fields_);
		//make tree		
		$tree							= new Tree();
		$tree->id_field_name		  	= "category_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $categories;
		$categories 					= $tree->create_tree(0);		
		unset($tree);			
		$categories_select = array();		
		foreach($categories as $category)			
			$categories_select[$category["category_id"]."-".$category["level"]] = $category["name"];
		*/		
		$search_by = array	
						(	array	(	"field_name"	=> "qwerty_id",
										"field_label" 	=> "ID",
										"field_type"	=> "input",
										"field_values"	=> array()	
									),
							/*array	(	"field_name"	=> "category_id",
										"field_label" 	=> "Category ID",
										"field_type"	=> "select",
										"field_values"	=> $categories_select	
									),*/																																										
							array	(	"field_name"	=> "status",
										"field_label" 	=> $this->lang->line("status"),
										"field_type"	=> "checkbox",
										"field_values"	=> array("1" => $this->lang->line("active"), "0" => $this->lang->line("inactive"))	
									),														
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("qwerty_name"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),																																		
							);		
		
		//set search session
		if(isset($_POST["Search"]))		
		{
			$_SESSION[$section_name]["search_by"] = $_POST;
			header("Location: ".admin_url()."qwertys");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."qwertys");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["qwerty_id"]) && !empty($search["qwerty_id"])) 
				$where .= " AND t1.qwerty_id = '".$search["qwerty_id"]."' ";
							
			/*if(isset($search["category_id"]) && !empty($search["category_id"]))
			{
				//$where .= " AND category_id = "".$search["category_id"]."" ";
				
				//get category childs
				//============================================================
				$all_childs_id  = array();
				$parametru 		= array(	"table_name"		=>	"categories",
											"id_field_name"		=>	"category_id",
											"id_field_value"	=>	$search["category_id"],
											"fields"			=>	"t2.category_id, name, url_key",	
				);
				$childrens		= $this->tree->get_all_children($parametru, $this->admin_default_lang_id);
				foreach($childrens as $children)
					array_push($all_childs_id, $children["category_id"]);
				unset($childrens);

				$where .= "AND category_id IN (".$search["category_id"].",".(count($all_childs_id)>0?implode(",",$all_childs_id):"0").")";
			}*/
				
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
									
			if(isset($search["name"]) && !empty($search["name"])) 
			{
				$where .= " AND LOWER(t2.name) LIKE '%".strtolower(mysql_real_escape_string($search["name"]))."%' ";										
			}									
		}																							
			
		//sort
		//======================================================				 	
		$sort_fields 			= array("t1.qwerty_id", "t1.order", "t1.active");
		$default_sort_field 	= "t1.qwerty_id"; 
		$default_sort_dir 		= "desc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->qwertys_model->get_qwertys($where,false,false,false,"count(t1.qwerty_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."qwertys/index";
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
		$qwertys = $this->qwertys_model->get_qwertys($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($qwertys as $key => $qwerty)
		{												
			/*
			//get number of items						
			$this->db->where("qwerty_id", $qwerty["qwerty_id"]);
			$this->db->from("qwertys_files");
			$items_number =  $this->db->count_all_results();
			$qwertys[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($qwertys)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($qwertys);
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
		$data['qwertys'] 			= $qwertys;		
		$data['body'] 				= 'admin/qwertys/list_qwertys';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add qwerty
	 */
	function add_qwerty()
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
			foreach($languages as $language)
			{				
				//if we have more than one language, then make a string with lang code label
				$label_language = "";
				if($show_label_language) 
					$label_language = " (".$language["code"].")";				
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("qwerty_name").$label_language,		"trim|required");
				$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("qwerty_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("qwerty_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
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
				$exist 		= $this->qwertys_model->get_qwertys($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("qwerty_exist");				
				else 
				{
					//values
					$values = array(	"active" 		=> $_POST["active"],
										"add_date"		=> date("Y-m-d H:i:s"),
									);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["name"][$language["lang_id"]],"-");
						$add_date[$language["lang_id"]] 	= date("Y-m-d H:i:s");
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];											
					}									
					$details = array(   "name" 				=> $_POST["name"],
										"abstract" 			=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],
										"meta_title" 		=> $_POST["meta_title"],
										"meta_description" 	=> $_POST["meta_description"],
										"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"add_date"			=> $add_date,
										"lang_id"			=> $lang_ids	
									);	

					//insert				
					$this->qwertys_model->add_qwerty($values, $details);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					header("Location: ".current_url());
					die();
				}														
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_qwerty");
		
		//send data to view
		//=========================================================		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/qwertys/add_qwerty";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit qwerty
	 * 
	 * @param int $qwerty_id
	 */
	function edit_qwerty($qwerty_id = false)
	{					
		$data = array();	
		if($qwerty_id == false) die(); 								
			
		//get qwerty and qwerty_details
		//=========================================================		
		$qwertys = $this->qwertys_model->get_just_qwertys("AND qwerty_id = '".$qwerty_id."'");
		if(!$qwertys) die();		
		$qwerty = $qwertys[0];
		$array_qwerty_details  = $this->qwertys_model->get_just_qwertys_details("AND qwerty_id = '".$qwerty_id."'");
		foreach($array_qwerty_details as $array_qwerty_detail)				
			foreach($array_qwerty_detail as $field=>$value)			
				$qwerty_details[$field][$array_qwerty_detail["lang_id"]] = $value;
								
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("qwerty_name").$label_language,		"trim|required");
				$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("qwerty_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("qwerty_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
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
								 AND t2.qwerty_id 	!= '".$qwerty_id."' 
							   ";									
				$exist = $this->qwertys_model->get_qwertys($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("qwerty_exist");
				else
				{
					//values
					$values = array(	"active"	=> $_POST["active"]		);
					
					//details				
					foreach($languages as $language)					
					{
						$url_key[$language["lang_id"]] 		= url_key($_POST["name"][$language["lang_id"]],"-");						
						$lang_ids[$language["lang_id"]]		= $language["lang_id"];										
					}									
					$details = array(   "name" 				=> $_POST["name"],
										"abstract" 			=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],										
										"meta_title" 		=> $_POST["meta_title"],
										"meta_description" 	=> $_POST["meta_description"],
										"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"lang_id"			=> $lang_ids										
									);	

					//update				
					$this->qwertys_model->edit_qwerty($values, $details, $qwerty_id);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					header("Location: ".current_url());
					die();
				}								 						
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_qwerty");										
		
		//send data to view
		//=========================================================
		$data["qwerty"] 				= $qwerty;
		$data["qwerty_details"] 		= $qwerty_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/qwertys/edit_qwerty";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete qwerty
	 * 
	 * @param int $qwerty_id
	 */
	function delete_qwerty($qwerty_id = false, $no_redirect = false)
	{		
		if($qwerty_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $qwerty_id, true);	
		$this->delete_file("banner", $qwerty_id, true);					
		
		//delete qwerty
		//=========================================================
		$this->qwertys_model->delete_qwerty($qwerty_id);			
				
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
	 * @param int $qwerty_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_qwerty($qwerty_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->qwertys_model->edit_qwerty($values,false,$qwerty_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $qwerty_id
	 */
	function upload_file($type, $qwerty_id)
	{		
		$data = array();	
		if($qwerty_id == false) die();

		//get qwerty
		//=========================================================
		$qwertys 	= $this->qwertys_model->get_qwertys(" AND t1.qwerty_id = '".$qwerty_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$qwertys) die();
		$qwerty		= $qwertys[0];
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/qwertys/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($qwerty["url_key"])?$qwerty["url_key"]:"image_".$qwerty_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $qwerty_id, $no_redirect = true);	
						
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
					
					//create thumb					
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["create_thumb"] 	= TRUE;
					$config["thumb_marker"] 	= "_th";
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 150;
					$config["height"] 			= 1500;																														
					//load image manupulation library
					$this->load->library("image_lib");					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("qwerty_id", $qwerty_id);
					$this->db->update("qwertys",$values);	
										
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/qwertys/banners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($qwerty["url_key"])?$qwerty["url_key"]:"banner_".$qwerty_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $qwerty_id, $no_redirect = true);	
					
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
					$this->db->where("qwerty_id", $qwerty_id);
					$this->db->update("qwertys",$values);	
										
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
		$data["qwerty"] = $qwerty;			
		$data["body"]  	= "admin/qwertys/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $qwerty_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $qwerty_id, $no_redirect = false)
	{
		if($qwerty_id == false) die();
		
		//get qwerty
		//=========================================================
		$qwertys 	= $this->qwertys_model->get_qwertys(" AND t1.qwerty_id = '".$qwerty_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$qwertys) die();
		$qwerty		= $qwertys[0];			
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $qwerty["image"];
			$file_path 	= base_path()."uploads/qwertys/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($qwerty["image"]);
			$file_path 	= base_path()."uploads/qwertys/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $qwerty["banner"];
			$file_path 	= base_path()."uploads/qwertys/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",array($type => ""));	
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
