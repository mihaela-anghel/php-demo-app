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
		$this->load->library('tree');

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
			
		//sort
		//======================================================				 	
		$sort_fields 			= array("t1.qwerty_id", "t2.name", "t1.order", "t1.active");
		$default_sort_field 	= "t1.qwerty_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//get list
		//======================================================	
		$qwertys = $this->qwertys_model->get_qwertys($where,$orderby);
		
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "qwerty_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $qwertys;
		$qwertys 						= $tree->create_tree(0);		
		unset($tree);
		
		//get extra info
		//======================================================	
		foreach($qwertys as $key => $qwerty)
		{
			//get number of images		
			$where 							= "AND qwerty_id = ".$qwerty['qwerty_id']." ";			
			$images 						= $this->qwertys_model->get_images($where,false,false,false,"count(*) as numrows");
			$qwertys[$key]['images_number']	= $images[0]["numrows"];

			//get number of files		
			$where 							= "AND qwerty_id = ".$qwerty['qwerty_id']." ";			
			$files 							= $this->qwertys_model->get_files($where,false,false,false,"count(*) as numrows");
			$qwertys[$key]['files_number']	= $files[0]["numrows"];
						
			/*
			//get number of items						
			$this->db->where('qwerty_id', $qwerty['qwerty_id']);
			$this->db->from('qwertys_files');
			$items_number =  $this->db->count_all_results();
			$qwertys[$key]['items_number'] = $items_number;
			*/			
		}
		
		//send data to view
		//====================================================== 
		$data["section_name"]	= $section_name;				
		$data['sort_label'] 	= $sort_label;		
		$data['qwertys'] 		= $qwertys;		
		$data['body'] 			= 'admin/qwertys/list_qwertys';
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
			$this->form_validation->set_rules("parent_id",		$this->lang->line("qwerty"),	"trim|required");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				$where_exist = " AND t2.name		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t1.parent_id 	= ".$_POST["parent_id"]."
								 AND t2.lang_id 	= ".$this->admin_default_lang_id." 
							   ";					
				$exist 		= $this->qwertys_model->get_qwertys($where_exist);
				
				if($exist)				
					$data["error_message"] = $this->lang->line("qwerty_exist");				
				else 
				{
					//values
					$values = array(	"active" 		=> $_POST["active"],
										"parent_id"		=> $_POST["parent_id"],
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
		
		//get qwertys
		//=========================================================
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";
		$orderby 	= " ORDER BY `order` ASC ";			
		$qwertys 	= $this->qwertys_model->get_qwertys($where,$orderby);
				
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "qwerty_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $qwertys;
		$qwertys_tree					= $tree->create_tree(0);
		unset($tree);

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_qwerty");
		
		//send data to view
		//=========================================================
		$data["qwertys_tree"]			= $qwertys_tree;
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
			$this->form_validation->set_rules("parent_id",		$this->lang->line("qwerty"),	"trim|required");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				$where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id."
								 AND t1.parent_id 	= ".$_POST["parent_id"]."
								 AND t2.qwerty_id 	!= '".$qwerty_id."' 
							   ";									
				$exist = $this->qwertys_model->get_qwertys($where_exist);
				
				if($exist)
					$data["error_message"] = $this->lang->line("qwerty_exist");
				else
				{
					//values
					$values = array(	"active"	=> $_POST["active"],
										"parent_id"	=> $_POST["parent_id"]
									);
					
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
		
		//get qwertys
		//=========================================================
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";
		$orderby 	= " ORDER BY `order` ASC ";			
		$qwertys 	= $this->qwertys_model->get_qwertys($where,$orderby);
				
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "qwerty_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $qwertys;
		$qwertys_tree					= $tree->create_tree(0);
		unset($tree);

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_qwerty");										
		
		//send data to view
		//=========================================================
		$data["qwertys_tree"] 			= $qwertys_tree;
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
	function delete_qwerty($qwerty_id = false)
	{		
		if($qwerty_id == false) die(); 
		
		//build an array which does contain the qwerty_id i want to delete and all its subqwertys ids who must be deleted  	
		
		//add main qwerty_id to array
		$qwertys_ids = array($qwerty_id);
		
		//get all qwertys
		$where 		= " AND lang_id = '".$this->admin_default_lang_id."' ";
		$fields 	= "t1.qwerty_id, t1.parent_id";	
		$qwertys 	= $this->qwertys_model->get_qwertys($where,false,false,false,$fields);		
				
		//make tree		
		$tree							= new Tree();
		$tree->id_field_name		  	= "qwerty_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $qwertys;
		$qwertys 						= $tree->create_tree($qwerty_id);
		unset($tree);
				
		//add to array all subqwertys ids		 
		foreach($qwertys as $qwerty)		
			array_push($qwertys_ids, $qwerty['qwerty_id']);
		
		//for each array element delete all
		foreach($qwertys_ids as $qwerty_id)
		{			
			//delete image, banner
			//=========================================================
			$this->delete_file("image",  $qwerty_id, $no_redirect = true);	
			$this->delete_file("banner", $qwerty_id, $no_redirect = true);
			
			//delete images (images gallery)
			//=========================================================				
			$where 		= "AND qwerty_id = '".$qwerty_id."' ";			
			$images 	= $this->qwertys_model->get_images($where, false, false, false, "image_id");
			foreach($images as $image)			
				$this->images_delete($image["image_id"], true);

			//delete files (files gallery)
			//=========================================================				
			$where 		= "AND qwerty_id = '".$qwerty_id."' ";			
			$files 	= $this->qwertys_model->get_files($where, false, false, false, "file_id");
			foreach($files as $file)			
				$this->files_delete($file["file_id"], true);		
			
			//delete qwerty
			//=========================================================
			$this->qwertys_model->delete_qwerty($qwerty_id);
		}			
				
		//redirect	
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);												
		</script><?php
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
	 * Image gallery
	 *  
	 * @param int $qwerty_id
	 */
	function images($qwerty_id = false)
	{
		$data = array();
		if($qwerty_id == false) die(); 

		//delete all
		//=========================================================
		if(isset($_POST["DeleteSelected"]))
		{
			if(isset($_POST["item"]))
				foreach($_POST["item"] as $item)										
					$this->images_delete($item, true);
			
			header("Location: ".current_url());			
			die();		
		}
				
		//get qwerty
		//=========================================================		
		$where 				= "AND t1.qwerty_id = '".$qwerty_id."' ";
		$qwertys 			= $this->qwertys_model->get_qwertys($where);
		if(!$qwertys) die();	
		$qwerty 			= $qwertys[0];							
		
		//get images
		//=========================================================		
		$where 				= "AND qwerty_id = '".$qwerty_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$images 			= $this->qwertys_model->get_images($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("qwerty_images");			
						
		//send data to view	
		//=========================================================	
		$data["qwerty"] 	= $qwerty;			
		$data["images"]		= $images;	
		$data["body"] 		= "admin/qwertys/list_images";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple images upload
	 * 
	 * @param int $qwerty_id
	 */
	function images_upload($qwerty_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get qwerty
			//=========================================================
			$where 				= "AND  t1.qwerty_id = '".$qwerty_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$qwertys 			= $this->qwertys_model->get_qwertys($where,false,false,false,$fields);
			if(!$qwertys) die();					
			$qwerty 			= $qwertys[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/qwertys/images/";
			$config["allowed_types"]= "gif|jpg|png";
			$config["max_size"]		= "3072";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			$config["file_name"] 	= str_replace(".","-",$qwerty["url_key"]);
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();				
				
				//resize file
				$config["image_library"] 	= "gd2";
				$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
				$config["maintain_ratio"] 	= TRUE;				
				$config["width"] 			= 800;
				$config["height"] 			= 80000;																							
				//load image manupulation library								
				$this->load->library("image_lib");
				$this->image_lib->initialize($config);			
				if($file_data["image_width"] > $config["width"])		
					$this->image_lib->resize();
				$this->image_lib->clear();	
				
				//create thumb		
				$config["image_library"] 	= "gd2";
				$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
				$config["create_thumb"] 	= TRUE;
				$config["thumb_marker"] 	= "_th";
				$config["maintain_ratio"]	= TRUE;																																									
				$config["width"] 			= 200;
				$config["height"]			= 20000;													
				//load image manupulation library
				$this->load->library("image_lib");
				$this->image_lib->clear();					
				$this->image_lib->initialize($config);
				$this->image_lib->resize();								
				
				//set order
				$this->db->where("qwerty_id", $qwerty_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("qwertys_images");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"qwerty_id" 	=> $qwerty_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->qwertys_model->add_image($values);
				
				//done message
				echo "success*".$this->lang->line("done_message_add");
			}
			else			
			{	
				//error message
				echo "error*".$this->upload->display_errors("","");
			}	
		}
	}
	
	/**
	 * Delete image
	 * 
	 * @param int $image_id
	 * @param bool $no_redirect
	 */
	function images_delete($image_id = false, $no_redirect = false)
	{		
		if($image_id == false) die(); 
	
		//get image
		//=========================================================		
		$where 				= "AND image_id = '".$image_id."' ";		
		$images 			= $this->qwertys_model->get_images($where);
		if(!$images) die();
		$image				= $images[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $image["filename"];
		$file_path 	= base_path()."uploads/qwertys/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($image["filename"]);
		$file_path 	= base_path()."uploads/qwertys/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->qwertys_model->delete_image($image_id);
				
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
	 * @param int $image_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function images_change($image_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->qwertys_model->edit_image($values,$image_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	

	/**
	 * file gallery
	 *  
	 * @param int $qwerty_id
	 */
	function files($qwerty_id = false)
	{
		$data = array();
		if($qwerty_id == false) die(); 

		//delete all
		//=========================================================
		if(isset($_POST["DeleteSelected"]))
		{
			if(isset($_POST["item"]))
				foreach($_POST["item"] as $item)										
					$this->files_delete($item, true);
			
			header("Location: ".current_url());			
			die();		
		}
				
		//get qwerty
		//=========================================================		
		$where 				= "AND t1.qwerty_id = '".$qwerty_id."' ";
		$qwertys 			= $this->qwertys_model->get_qwertys($where);
		if(!$qwertys) die();	
		$qwerty 			= $qwertys[0];							
		
		//get files
		//=========================================================		
		$where 				= "AND qwerty_id = '".$qwerty_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$files 			= $this->qwertys_model->get_files($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("qwerty_files");			
						
		//send data to view	
		//=========================================================	
		$data["qwerty"] 	= $qwerty;			
		$data["files"]		= $files;	
		$data["body"] 		= "admin/qwertys/list_files";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple files upload
	 * 
	 * @param int $qwerty_id
	 */
	function files_upload($qwerty_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get qwerty
			//=========================================================
			$where 				= "AND  t1.qwerty_id = '".$qwerty_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$qwertys 			= $this->qwertys_model->get_qwertys($where,false,false,false,$fields);
			if(!$qwertys) die();					
			$qwerty 			= $qwertys[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/qwertys/files/";
			$config["allowed_types"]= "*";
			$config["max_size"]		= "10240";								
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();							
				
				//set order
				$this->db->where("qwerty_id", $qwerty_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("qwertys_files");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"qwerty_id" 	=> $qwerty_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->qwertys_model->add_file($values);
				
				//done message
				echo "success*".$this->lang->line("done_message_add");
			}
			else			
			{	
				//error message
				echo "error*".$this->upload->display_errors("","");
			}	
		}
	}
	
	/**
	 * Delete file
	 * 
	 * @param int $file_id
	 * @param bool $no_redirect
	 */
	function files_delete($file_id = false, $no_redirect = false)
	{		
		if($file_id == false) die(); 
	
		//get file
		//=========================================================		
		$where 				= "AND file_id = '".$file_id."' ";		
		$files 			= $this->qwertys_model->get_files($where);
		if(!$files) die();
		$file				= $files[0];
		
		//detele file
		//=========================================================		
		$file_name	= $file["filename"];
		$file_path 	= base_path()."uploads/qwertys/files/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);					
		
		//delete from db
		//=========================================================
		$this->qwertys_model->delete_file($file_id);
				
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
	 * @param int $file_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function files_change($file_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->qwertys_model->edit_file($values,$file_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
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
