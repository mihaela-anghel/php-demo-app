<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Pages extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("pages_model");	
		$this->lang->load("admin/pages",$this->admin_default_lang);	
		$this->load->library('tree');

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List pages
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
		$sort_fields 			= array("t1.page_id", "t2.name", "t1.order", "t1.active");
		$default_sort_field 	= "t1.order"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//get list
		//======================================================	
		$pages = $this->pages_model->get_pages($where,$orderby);
		
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $pages;
		$pages 							= $tree->create_tree(0);		
		unset($tree);
		
		//get extra info
		//======================================================
		$this->load->library('tree');	
		foreach($pages as $key => $page)
		{
			//get number of images		
			$where 							= "AND page_id = ".$page['page_id']." ";			
			$images 						= $this->pages_model->get_images($where,false,false,false,"count(*) as numrows");
			$pages[$key]['images_number']	= $images[0]["numrows"];

			//get number of files		
			$where 							= "AND page_id = ".$page['page_id']." ";			
			$files 							= $this->pages_model->get_files($where,false,false,false,"count(*) as numrows");
			$pages[$key]['files_number']	= $files[0]["numrows"];

			//get number of videos		
			$where 							= "AND page_id = ".$page['page_id']." ";			
			$videos 						= $this->pages_model->get_videos($where,false,false,false,"count(*) as numrows");
			$pages[$key]['videos_number']	= $videos[0]["numrows"];
					
			//get page parents
			//============================================================			
			$parametru 		= array(	'table_name'		=>	'pages',	
										'id_field_name'		=>	'page_id',
										'id_field_value'	=>	$page['page_id'],
										'fields'			=>	't2.page_id, name, url_key',	
										);						
			$parents = $this->tree->get_all_parents($parametru, $this->admin_default_lang_id);			
			$pages[$key]["parents_ids"] = array();
			foreach($parents as $parent)
				array_push($pages[$key]["parents_ids"], $parent["page_id"]);
		}
		
		//send data to view
		//====================================================== 
		$data["section_name"]	= $section_name;				
		$data['sort_label'] 	= $sort_label;		
		$data['pages'] 			= $pages;		
		$data['body'] 			= 'admin/pages/list_pages';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add page
	 */
	function add_page()
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("page_name").$label_language,		"trim|required");
				$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("page_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("page_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}							
			$this->form_validation->set_rules("parent_id",		$this->lang->line("page"),	"trim|required");
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
				$exist 		= $this->pages_model->get_pages($where_exist);
				
				$exist = false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("page_exist");				
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
										//"abstract" 			=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],
										"meta_title" 		=> $_POST["meta_title"],
										"meta_description" 	=> $_POST["meta_description"],
										"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"add_date"			=> $add_date,
										"lang_id"			=> $lang_ids	
									);					
					//insert				
					$this->pages_model->add_page($values, $details);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					//header("Location: ".current_url());
					header("Location: ".admin_url().strtolower(get_class()));
					die();
				}														
			}						
		}
		
		//get pages
		//=========================================================
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";
		$orderby 	= " ORDER BY `order` ASC ";			
		$pages 	= $this->pages_model->get_pages($where,$orderby);
				
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $pages;
		$pages_tree						= $tree->create_tree(0);
		unset($tree);

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_page");
		
		//send data to view
		//=========================================================
		$data["pages_tree"]				= $pages_tree;
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/pages/add_page";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit page
	 * 
	 * @param int $page_id
	 */
	function edit_page($page_id = false)
	{					
		$data = array();	
		if($page_id == false) die(); 								
			
		//get page and page_details
		//=========================================================		
		$pages = $this->pages_model->get_just_pages("AND page_id = '".$page_id."'");
		if(!$pages) die();		
		$page = $pages[0];
		$array_page_details  = $this->pages_model->get_just_pages_details("AND page_id = '".$page_id."'");
		foreach($array_page_details as $array_page_detail)				
			foreach($array_page_detail as $field=>$value)			
				$page_details[$field][$array_page_detail["lang_id"]] = $value;
								
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("page_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("page_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("page_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}							
			$this->form_validation->set_rules("parent_id",		$this->lang->line("page"),	"trim|required");
			$this->form_validation->set_rules("active",			$this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/* $where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= '".$this->admin_default_lang_id."'
								 AND t1.parent_id 	= '".$_POST["parent_id"]."'
								 AND t2.page_id 	!= '".$page_id."' 
							   ";									
				$exist = $this->pages_model->get_pages($where_exist); */
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("page_exist");
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
										//"abstract" 			=> $_POST["abstract"],
									    "description" 		=> $_POST["description"],										
										"meta_title" 		=> $_POST["meta_title"],
										"meta_description" 	=> $_POST["meta_description"],
										"meta_keywords" 	=> $_POST["meta_keywords"],
										"url_key" 			=> $url_key,
										"lang_id"			=> $lang_ids										
									);
					
					//update				
					$this->pages_model->edit_page($values, $details, $page_id);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					//header("Location: ".current_url());
					header("Location: ".admin_url().strtolower(get_class()));
					die();
				}								 						
			}						
		}
		
		//get pages
		//=========================================================
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";
		$orderby 	= " ORDER BY `order` ASC ";			
		$pages 		= $this->pages_model->get_pages($where,$orderby);
				
		//make tree
		//======================================================
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $pages;
		$pages_tree						= $tree->create_tree(0);
		unset($tree);

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_page");										
		
		//send data to view
		//=========================================================
		$data["pages_tree"] 			= $pages_tree;
		$data["page"] 					= $page;
		$data["page_details"] 			= $page_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/pages/edit_page";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete page
	 * 
	 * @param int $page_id
	 */
	function delete_page($page_id = false)
	{		
		if($page_id == false) die(); 
		
		//build an array which does contain the page_id i want to delete and all its subpages ids who must be deleted  	
		
		//add main page_id to array
		$pages_ids = array($page_id);
		
		//get all pages
		$where 		= " AND lang_id = '".$this->admin_default_lang_id."' ";
		$fields 	= "t1.page_id, t1.parent_id";	
		$pages 	= $this->pages_model->get_pages($where,false,false,false,$fields);		
				
		//make tree		
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $pages;
		$pages 							= $tree->create_tree($page_id);
		unset($tree);
				
		//add to array all subpages ids		 
		foreach($pages as $page)		
			array_push($pages_ids, $page['page_id']);
		
		//for each array element delete all
		foreach($pages_ids as $page_id)
		{			
			//delete image, banner
			//=========================================================
			$this->delete_file("image",  $page_id, $no_redirect = true);	
			$this->delete_file("banner", $page_id, $no_redirect = true);
			
			//delete images (images gallery)
			//=========================================================				
			$where 		= "AND page_id = '".$page_id."' ";			
			$images 	= $this->pages_model->get_images($where, false, false, false, "image_id");
			foreach($images as $image)			
				$this->images_delete($image["image_id"], true);

			//delete files (files gallery)
			//=========================================================				
			$where 		= "AND page_id = '".$page_id."' ";			
			$files 	= $this->pages_model->get_files($where, false, false, false, "file_id");
			foreach($files as $file)			
				$this->files_delete($file["file_id"], true);	

			//delete videos (videos gallery)
			//=========================================================				
			$where 		= "AND page_id = '".$page_id."' ";			
			$videos 	= $this->pages_model->get_videos($where, false, false, false, "video_id");
			foreach($videos as $video)			
				$this->videos_delete($video["video_id"], true);	
			
			//delete page
			//=========================================================
			$this->pages_model->delete_page($page_id);
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
	 * @param int $page_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_page($page_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->pages_model->edit_page($values,false,$page_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $page_id
	 */
	function upload_file($type, $page_id)
	{		
		$data = array();	
		if($page_id == false) die();

		//get page
		//=========================================================
		$pages 	= $this->pages_model->get_pages(" AND t1.page_id = '".$page_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$pages) die();
		$page		= $pages[0];
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/";
				$config["allowed_types"]= "gif|jpg|png|jpeg";
				$config["max_size"]		= "6144";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($page["url_key"])?$page["url_key"]:"image_".$page_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $page_id, $no_redirect = true);	
						
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 800;
					$config["height"] 			= 8000;
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
					$config["height"] 			= 30000;																														
					//load image manupulation library
					$this->load->library("image_lib");					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("page_id", $page_id);
					$this->db->update("pages",$values);	
										
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/banners/";
				$config["allowed_types"]= "gif|jpg|png|jpeg";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($page["url_key"])?$page["url_key"]:"banner_".$page_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $page_id, $no_redirect = true);	
					
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 1600;
					$config["height"] 			= 4000;
					if($file_data["image_width"] <= $config["width"])
						$config["width"] 		= $file_data["image_width"]; 											
					//load image manupulation library
					$this->load->library("image_lib");
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();										

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("page_id", $page_id);
					$this->db->update("pages",$values);	
										
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
			
			//upload icon
			//=========================================================
			if($type  == "icon") 
			{																					
				//config upload file
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/icons/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "6144";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($page["url_key"])?$page["url_key"]:"icon_".$page_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $page_id, $no_redirect = true);	
						
					$file_data = $this->upload->data();													
					
					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("page_id", $page_id);
					$this->db->update("pages",$values);	
										
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
			}//end icon
			
		}//end post

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("upload_file");
		
		//send data to view
		//=========================================================
		$data["type"]	= $type;
		$data["page"] 	= $page;			
		$data["body"]  	= "admin/pages/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $page_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $page_id, $no_redirect = false)
	{
		if($page_id == false) die();
		
		//get page
		//=========================================================
		$pages 		= $this->pages_model->get_pages(" AND t1.page_id = '".$page_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$pages) die();
		$page		= $pages[0];			
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $page["image"];
			$file_path 	= base_path()."uploads/pages/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($page["image"]);
			$file_path 	= base_path()."uploads/pages/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("page_id" => $page_id));
			$this->db->update("pages",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $page["banner"];
			$file_path 	= base_path()."uploads/pages/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("page_id" => $page_id));
			$this->db->update("pages",array($type => ""));	
		}
		
		//delete icon
		//=========================================================
		if($type == "icon")
		{
			//delete file
			$file_name	= $page["image"];
			$file_path 	= base_path()."uploads/pages/icons/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
							
			//db update
			$this->db->where(array("page_id" => $page_id));
			$this->db->update("pages",array($type => ""));	
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
	 * @param int $page_id
	 */
	function images($page_id = false)
	{
		$data = array();
		if($page_id == false) die(); 

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
				
		//get page
		//=========================================================		
		$where 				= "AND t1.page_id = '".$page_id."' ";
		$pages 				= $this->pages_model->get_pages($where);
		if(!$pages) die();	
		$page 				= $pages[0];							
		
		//get images
		//=========================================================		
		$where 				= "AND page_id = '".$page_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$images 			= $this->pages_model->get_images($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("page_images");			
						
		//send data to view	
		//=========================================================	
		$data["page"] 		= $page;			
		$data["images"]		= $images;	
		$data["body"] 		= "admin/pages/list_images";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple images upload
	 * 
	 * @param int $page_id
	 */
	function images_upload($page_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get page
			//=========================================================
			$where 				= "AND  t1.page_id = '".$page_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$pages 				= $this->pages_model->get_pages($where,false,false,false,$fields);
			if(!$pages) die();					
			$page 				= $pages[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/images/";
			$config["allowed_types"]= "gif|jpg|png|jpeg";
			$config["max_size"]		= "6144";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			//$config["file_name"] 	= str_replace(".","-",$page["url_key"]);
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
				$config["width"] 			= 1000;
				$config["height"] 			= 10000;																							
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
				$config["width"] 			= 300;
				$config["height"]			= 3000;													
				//load image manupulation library
				$this->load->library("image_lib");
				$this->image_lib->clear();					
				$this->image_lib->initialize($config);
				$this->image_lib->resize();						
				
				//set order
				$this->db->where("page_id", $page_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("pages_images");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"page_id" 		=> $page_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->pages_model->add_image($values);
				
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
		$images 			= $this->pages_model->get_images($where);
		if(!$images) die();
		$image				= $images[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $image["filename"];
		$file_path 	= base_path()."uploads/pages/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($image["filename"]);
		$file_path 	= base_path()."uploads/pages/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->pages_model->delete_image($image_id);
				
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
		$this->pages_model->edit_image($values,$image_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	

	/**
	 * file gallery
	 *  
	 * @param int $page_id
	 */
	function files($page_id = false)
	{
		$data = array();
		if($page_id == false) die(); 
		
		if(isset($_POST["SaveButton"]))
		{																				
			print_r($_POST);
			foreach($_POST["SaveButton"] as $file_id => $value)
			{
				//form validation
				//=========================================================		
				$this->load->library('form_validation');			
				$this->form_validation->set_rules("name[".$file_id."]",		" ",	"trim");
				$this->form_validation->set_error_delimiters('<div class="error">','</div>');
				$form_is_valid = $this->form_validation->run();
				
				if($form_is_valid)
				{
					$values = array(	"name" 		=> $_POST["name"][$file_id]);	
	
					//insert				
					$this->pages_model->edit_file($values, $file_id);
					
					header("Location: ".current_url());			
					die();	
				}
			}							
		}

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
				
		//get page
		//=========================================================		
		$where 				= "AND t1.page_id = '".$page_id."' ";
		$pages 				= $this->pages_model->get_pages($where);
		if(!$pages) die();	
		$page 				= $pages[0];							
		
		//get files
		//=========================================================		
		$where 				= "AND page_id = '".$page_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$files 				= $this->pages_model->get_files($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title 	= $this->lang->line("page_files");			
						
		//send data to view	
		//=========================================================	
		$data["page"] 		= $page;			
		$data["files"]		= $files;	
		$data["body"] 		= "admin/pages/list_files";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple files upload
	 * 
	 * @param int $page_id
	 */
	function files_upload($page_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get page
			//=========================================================
			$where 				= "AND  t1.page_id = '".$page_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$pages 				= $this->pages_model->get_pages($where,false,false,false,$fields);
			if(!$pages) die();					
			$page 				= $pages[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/files/";
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
				$this->db->where("page_id", $page_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("pages_files");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"page_id" 		=> $page_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->pages_model->add_file($values);
				
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
		$files 				= $this->pages_model->get_files($where);
		if(!$files) die();
		$file				= $files[0];
		
		//detele file
		//=========================================================		
		$file_name	= $file["filename"];
		$file_path 	= base_path()."uploads/pages/files/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);					
		
		//delete from db
		//=========================================================
		$this->pages_model->delete_file($file_id);
				
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
		$this->pages_model->edit_file($values,$file_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * video gallery
	 *  
	 * @param int $page_id
	 */
	function videos($page_id = false)
	{
		$data = array();
		if($page_id == false) die();

		if(isset($_POST["Add"]))
		{																				
			//form validation
			//=========================================================		
			$this->load->library('form_validation');			
			$this->form_validation->set_rules("video",		" ",	"trim|required");
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				//set order
				$this->db->where("page_id", $page_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("pages_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"page_id" 		=> $page_id, 
									"video" 		=> $_POST["video"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->pages_model->add_video($values);
				
				header("Location: ".current_url());			
				die();																
			}				
		}	

		if(isset($_POST["SaveButton"]))
		{																				
			print_r($_POST);
			foreach($_POST["SaveButton"] as $video_id => $value)
			{
				//form validation
				//=========================================================		
				$this->load->library('form_validation');			
				$this->form_validation->set_rules("name[".$video_id."]",		" ",	"trim");
				$this->form_validation->set_error_delimiters('<div class="error">','</div>');
				$form_is_valid = $this->form_validation->run();
				
				if($form_is_valid)
				{
					$values = array(	"name" 		=> $_POST["name"][$video_id]);	
	
					//insert				
					$this->pages_model->edit_video($values, $video_id);
					
					header("Location: ".current_url());			
					die();	
				}
			}							
		}	

		//delete all
		//=========================================================
		if(isset($_POST["DeleteSelected"]))
		{
			if(isset($_POST["item"]))
				foreach($_POST["item"] as $item)										
					$this->videos_delete($item, true);
			
			header("Location: ".current_url());			
			die();		
		}
				
		//get page
		//=========================================================		
		$where 				= "AND t1.page_id = '".$page_id."' ";
		$pages 				= $this->pages_model->get_pages($where);
		if(!$pages) die();	
		$page 				= $pages[0];							
		
		//get videos
		//=========================================================		
		$where 				= "AND page_id = '".$page_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$videos 			= $this->pages_model->get_videos($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("page_videos");			
						
		//send data to view	
		//=========================================================	
		$data["page"] 		= $page;			
		$data["videos"]		= $videos;	
		$data["body"] 		= "admin/pages/list_videos";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple videos upload
	 * 
	 * @param int $page_id
	 */
	function videos_upload($page_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get page
			//=========================================================
			$where 				= "AND  t1.page_id = '".$page_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$pages 				= $this->pages_model->get_pages($where,false,false,false,$fields);
			if(!$pages) die();					
			$page 				= $pages[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/pages/videos/";
			$config["allowed_types"]= "mp4";
			$config["max_size"]		= "10240";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			//$config["file_name"] 	= str_replace(".","-",$page["url_key"]);
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();																			
				
				//set order
				$this->db->where("page_id", $page_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("pages_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"page_id" 		=> $page_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->pages_model->add_video($values);
				
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
	 * Delete video
	 * 
	 * @param int $video_id
	 * @param bool $no_redirect
	 */
	function videos_delete($video_id = false, $no_redirect = false)
	{		
		if($video_id == false) die(); 
	
		//get video
		//=========================================================		
		$where 				= "AND video_id = '".$video_id."' ";		
		$videos 			= $this->pages_model->get_videos($where);
		if(!$videos) die();
		$video				= $videos[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $video["filename"];
		$file_path 	= base_path()."uploads/pages/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($video["filename"]);
		$file_path 	= base_path()."uploads/pages/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->pages_model->delete_video($video_id);
				
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
	 * @param int $video_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function videos_change($video_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->pages_model->edit_video($values,$video_id);
		
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
