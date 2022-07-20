<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Galleries extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("galleries_model");	
		$this->lang->load("admin/galleries",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List galleries
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
				foreach($_POST["item"] as $key=>$gallery_id)
				{
					$this->delete_gallery($gallery_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."galleries");
			die();
		}				

		//SEARCH		
		//======================================================
		//create galleries_categories select 		
		$this->load->model('galleries_categories_model');	
		$this->load->library('tree');
		$orderby_ 	= " ORDER BY `order` asc ";	
		$where_ 	= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields_ 	= " t1.galleries_category_id, t1.parent_id, t2.galleries_category_name ";
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where_,$orderby_,false,false,$fields_);
		//make tree		
		$tree							= new Tree();
		$tree->id_field_name		  	= "galleries_category_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $galleries_categories;
		$galleries_categories 					= $tree->create_tree(0);		
		unset($tree);			
		$galleries_categories_select = array();		
		foreach($galleries_categories as $galleries_category)			
			$galleries_categories_select[$galleries_category["galleries_category_id"]."-".$galleries_category["level"]] = $galleries_category["galleries_category_name"];
				
		$search_by = array	
						(	/*array	(	"field_name"	=> "year",
										"field_label" 	=> $this->lang->line("gallery_year"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),*/
							array	(	'field_name'	=> 'galleries_category_id',
										'field_label' 	=> $this->lang->line('gallery_category'),
										'field_type'	=> 'select',
										'field_values'	=> $galleries_categories_select
									),																				
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("gallery_name"),
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
			header("Location: ".admin_url()."galleries");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."galleries");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["year"]) && !empty($search["year"])) 
				$where .= " AND t1.year = '".$search["year"]."' ";
							
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
				$where .= " AND LOWER(t2.name) LIKE '%".$this->db->escape_like_str($search["name"])."%' ";											

			if(isset($search['galleries_category_id']) && !empty($search['galleries_category_id'])) 			
				$where .= " AND galleries_category_id = '".$search['galleries_category_id']."' ";
		}																							
			
		//sort
		//======================================================				 	
		$sort_fields 			= array("t1.gallery_id", "t1.order", "t1.active");
		$default_sort_field 	= "t1.gallery_id"; 
		$default_sort_dir 		= "desc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->galleries_model->get_galleries($where,false,false,false,"count(t1.gallery_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."galleries/index";
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
		$galleries = $this->galleries_model->get_galleries($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($galleries as $key => $gallery)
		{
			//get number of images		
			$where 							= "AND gallery_id = ".$gallery['gallery_id']." ";						
			$images 						= $this->galleries_model->get_images($where,false,false,false,"count(*) as numrows");
			$galleries[$key]["images_number"]	= $images[0]["numrows"];
						
			//get number of videos		
			$where 							= "AND gallery_id = ".$gallery['gallery_id']." ";			
			$videos 						= $this->galleries_model->get_videos($where,false,false,false,"count(*) as numrows");
			$galleries[$key]['videos_number']= $videos[0]["numrows"];
			
			/*
			//get first images	
			$galleries[$key]["image"]			= "";	
			$where 							= "AND gallery_id = ".$gallery['gallery_id']." ";			
			$orderby 						= " ORDER BY `order` ASC";
			$images 						= $this->galleries_model->get_images($where,$orderby,1,0,"filename");
			if($images)
				$galleries[$key]["image"]		= $images[0]["filename"];
			*/			
						
			/*
			//get number of items						
			$this->db->where("gallery_id", $gallery["gallery_id"]);
			$this->db->from("galleries_files");
			$items_number =  $this->db->count_all_results();
			$galleries[$key]["items_number"] = $items_number;
			*/	

			//get category_name
			$galleries[$key]['category_name'] = "";
			if($gallery['galleries_category_id'] > 0)
			{													
				$parametru 	= array(	'table_name'		=>	'galleries_categories',	
										'id_field_name'		=>	'galleries_category_id',
										'id_field_value'	=>	$gallery['galleries_category_id'],
										'fields'			=>	't2.galleries_category_id, galleries_category_name, url_key',	
										);
				$parents = $this->tree->get_all_parents($parametru, $this->admin_default_lang_id);							
										
				foreach($parents as $parent)								
					$galleries_articles[$key]['galleries_category_name'] .= $parent['galleries_category_name']." - "; //for left tree 
				
				$where_ 						 = " AND lang_id = ".$this->admin_default_lang_id." AND t1.galleries_category_id = ".$gallery['galleries_category_id']." ";	
				$fields_ 						 = " t2.galleries_category_name ";
				$galleries_categories			 = $this->galleries_categories_model->get_galleries_categories($where_,false,false,false,$fields_);				
				if($galleries_categories)
					$galleries[$key]['category_name']	.= $galleries_categories[0]["galleries_category_name"];
			}
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($galleries)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($galleries);
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
		$data['galleries'] 			= $galleries;		
		$data['body'] 				= 'admin/galleries/list_galleries';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add gallery
	 */
	function add_gallery()
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("gallery_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("gallery_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("gallery_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}	
			//$this->form_validation->set_rules("year",				$this->lang->line("gallery_year"),		"trim|required");
			$this->form_validation->set_rules('galleries_category_id',$this->lang->line('gallery_category'),	'trim');			
			$this->form_validation->set_rules("active",				$this->lang->line("status"),	"trim|required");			
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
				$exist 		= $this->galleries_model->get_galleries($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("gallery_exist");				
				else 
				{
					//values				
					$values = array(	//"year" 				=> $_POST["year"],
										'galleries_category_id'	=> $_POST['galleries_category_id'],
										"active" 				=> $_POST["active"],
										"add_date"				=> date("Y-m-d H:i:s"),
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
					$this->galleries_model->add_gallery($values, $details);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					//header("Location: ".current_url());
					header("Location: ".admin_url().strtolower(get_class()));
					die();
				}														
			}						
		}	

		//get galleries_categories for parent select and make tree from $galleries_categories
		//=========================================================	
		$this->load->model('galleries_categories_model');	
		$this->load->library('tree');
		$orderby 	= "ORDER BY `order` asc";	
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields 	= " t1.galleries_category_id, t1.parent_id, t2.galleries_category_name ";
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby,false,false,$fields);		
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;
		$galleries_categories = $this->tree->create_tree(0);
		$data["galleries_categories"] 		= $galleries_categories;

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_gallery");
		
		//send data to view
		//=========================================================		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/galleries/add_gallery";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit gallery
	 * 
	 * @param int $gallery_id
	 */
	function edit_gallery($gallery_id = false)
	{					
		$data = array();	
		if($gallery_id == false) die(); 								
			
		//get gallery and gallery_details
		//=========================================================		
		$galleries = $this->galleries_model->get_just_galleries("AND gallery_id = '".$gallery_id."'");
		if(!$galleries) die();		
		$gallery = $galleries[0];
		$array_gallery_details  = $this->galleries_model->get_just_galleries_details("AND gallery_id = '".$gallery_id."'");
		foreach($array_gallery_details as $array_gallery_detail)				
			foreach($array_gallery_detail as $field=>$value)			
				$gallery_details[$field][$array_gallery_detail["lang_id"]] = $value;
								
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("gallery_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("gallery_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("gallery_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}					
			//$this->form_validation->set_rules("year",				$this->lang->line("gallery_year"),			"trim|required");
			$this->form_validation->set_rules('galleries_category_id',$this->lang->line('gallery_category'),	'trim');		
			$this->form_validation->set_rules("active",				$this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/*
				$where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id."
								 AND t2.gallery_id 	!= '".$gallery_id."' 
							   ";									
				$exist = $this->galleries_model->get_galleries($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("gallery_exist");
				else
				{
					//values
					$values = array(	//"year" 				=> $_POST["year"],
										'galleries_category_id'	=> $_POST['galleries_category_id'],
										"active"				=> $_POST["active"]
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
					$this->galleries_model->edit_gallery($values, $details, $gallery_id);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					header("Location: ".current_url());
					//header("Location: ".admin_url().strtolower(get_class()));
					die();
				}								 						
			}						
		}

		//get galleries_categories for parent select and make tree from $galleries_categories
		//=========================================================	
		$this->load->model('galleries_categories_model');	
		$this->load->library('tree');
		$orderby 	= "ORDER BY `order` asc";	
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields 	= " t1.galleries_category_id, t1.parent_id, t2.galleries_category_name ";
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby,false,false,$fields);		
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;
		$galleries_categories = $this->tree->create_tree(0);
		$data["galleries_categories"] 		= $galleries_categories;

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_gallery");										
		
		//send data to view
		//=========================================================
		$data["gallery"] 				= $gallery;
		$data["gallery_details"] 		= $gallery_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/galleries/edit_gallery";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete gallery
	 * 
	 * @param int $gallery_id
	 */
	function delete_gallery($gallery_id = false, $no_redirect = false)
	{		
		if($gallery_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $gallery_id, true);	
		$this->delete_file("banner", $gallery_id, true);
		
		//delete images (images gallery)
		//=========================================================				
		$where 		= "AND gallery_id = '".$gallery_id."' ";			
		$images 	= $this->galleries_model->get_images($where, false, false, false, "image_id");
		foreach($images as $image)			
			$this->images_delete($image["image_id"], true);
		
		//delete videos (videos gallery)
		//=========================================================				
		$where 		= "AND gallery_id = '".$gallery_id."' ";			
		$videos 	= $this->galleries_model->get_videos($where, false, false, false, "video_id");
		foreach($videos as $video)			
			$this->videos_delete($video["video_id"], true);	
		
		//delete gallery
		//=========================================================
		$this->galleries_model->delete_gallery($gallery_id);			
				
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
	 * @param int $gallery_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_gallery($gallery_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->galleries_model->edit_gallery($values,false,$gallery_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $gallery_id
	 */
	function upload_file($type, $gallery_id)
	{		
		$data = array();	
		if($gallery_id == false) die();

		//get gallery
		//=========================================================
		$galleries 	= $this->galleries_model->get_galleries(" AND t1.gallery_id = '".$gallery_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$galleries) die();
		$gallery		= $galleries[0];
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/galleries/";
				$config["allowed_types"]= "gif|jpg|png|jpeg";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($gallery["url_key"])?$gallery["url_key"]:"image_".$gallery_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $gallery_id, $no_redirect = true);	
						
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
					
					/*//create thumb					
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
					$this->image_lib->resize();*/

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("gallery_id", $gallery_id);
					$this->db->update("galleries",$values);	
										
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/galleries/banners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($gallery["url_key"])?$gallery["url_key"]:"banner_".$gallery_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $gallery_id, $no_redirect = true);	
					
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
					$this->db->where("gallery_id", $gallery_id);
					$this->db->update("galleries",$values);	
										
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
		$data["gallery"] = $gallery;			
		$data["body"]  	= "admin/galleries/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $gallery_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $gallery_id, $no_redirect = false)
	{
		if($gallery_id == false) die();
		
		//get gallery
		//=========================================================
		$galleries 	= $this->galleries_model->get_galleries(" AND t1.gallery_id = '".$gallery_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$galleries) die();
		$gallery		= $galleries[0];			
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $gallery["image"];
			$file_path 	= base_path()."uploads/galleries/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($gallery["image"]);
			$file_path 	= base_path()."uploads/galleries/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("gallery_id" => $gallery_id));
			$this->db->update("galleries",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $gallery["banner"];
			$file_path 	= base_path()."uploads/galleries/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("gallery_id" => $gallery_id));
			$this->db->update("galleries",array($type => ""));	
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
	 * @param int $gallery_id
	 */
	function images($gallery_id = false)
	{
		$data = array();
		if($gallery_id == false) die(); 

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
				
		//get gallery
		//=========================================================		
		$where 				= "AND t1.gallery_id = '".$gallery_id."' ";
		$galleries 			= $this->galleries_model->get_galleries($where);
		if(!$galleries) die();	
		$gallery 			= $galleries[0];							
		
		//get images
		//=========================================================		
		$where 				= "AND gallery_id = '".$gallery_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$images 			= $this->galleries_model->get_images($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("gallery_images");			
						
		//send data to view	
		//=========================================================	
		$data["gallery"] 	= $gallery;			
		$data["images"]		= $images;	
		$data["body"] 		= "admin/galleries/list_images";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple images upload
	 * 
	 * @param int $gallery_id
	 */
	function images_upload($gallery_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get gallery
			//=========================================================
			$where 				= "AND  t1.gallery_id = '".$gallery_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$galleries 			= $this->galleries_model->get_galleries($where,false,false,false,$fields);
			if(!$galleries) die();					
			$gallery 			= $galleries[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/galleries/images/";
			$config["allowed_types"]= "gif|jpg|png|jpeg";
			$config["max_size"]		= "3072";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			$config["file_name"] 	= str_replace(".","-",$gallery["url_key"]);
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
				$config["width"] 			= 850;
				$config["height"] 			= 8500;																							
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
				$this->db->where("gallery_id", $gallery_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("galleries_images");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"gallery_id" 	=> $gallery_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->galleries_model->add_image($values);
				
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
		$images 			= $this->galleries_model->get_images($where);
		if(!$images) die();
		$image				= $images[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $image["filename"];
		$file_path 	= base_path()."uploads/galleries/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($image["filename"]);
		$file_path 	= base_path()."uploads/galleries/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->galleries_model->delete_image($image_id);
				
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
		$this->galleries_model->edit_image($values,$image_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
	
	/**
	 * video gallery
	 *  
	 * @param int $gallery_id
	 */
	function videos($gallery_id = false)
	{
		$data = array();
		if($gallery_id == false) die();

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
				$this->db->where("gallery_id", $gallery_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("galleries_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"gallery_id" 		=> $gallery_id, 
									"video" 		=> $_POST["video"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->galleries_model->add_video($values);
				
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
					$this->galleries_model->edit_video($values, $video_id);
					
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
				
		//get gallery
		//=========================================================		
		$where 				= "AND t1.gallery_id = '".$gallery_id."' ";
		$galleries 				= $this->galleries_model->get_galleries($where);
		if(!$galleries) die();	
		$gallery 				= $galleries[0];							
		
		//get videos
		//=========================================================		
		$where 				= "AND gallery_id = '".$gallery_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$videos 			= $this->galleries_model->get_videos($where,$orderby);										
		
		//gallery title
		//=========================================================
		$this->page_title = $this->lang->line("gallery_videos");			
						
		//send data to view	
		//=========================================================	
		$data["gallery"] 		= $gallery;			
		$data["videos"]		= $videos;	
		$data["body"] 		= "admin/galleries/list_videos";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple videos upload
	 * 
	 * @param int $gallery_id
	 */
	function videos_upload($gallery_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get gallery
			//=========================================================
			$where 				= "AND  t1.gallery_id = '".$gallery_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$galleries 				= $this->galleries_model->get_galleries($where,false,false,false,$fields);
			if(!$galleries) die();					
			$gallery 				= $galleries[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/galleries/videos/";
			$config["allowed_types"]= "mp4";
			$config["max_size"]		= "10240";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			//$config["file_name"] 	= str_replace(".","-",$gallery["url_key"]);
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();																			
				
				//set order
				$this->db->where("gallery_id", $gallery_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("galleries_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"gallery_id" 		=> $gallery_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->galleries_model->add_video($values);
				
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
		$videos 			= $this->galleries_model->get_videos($where);
		if(!$videos) die();
		$video				= $videos[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $video["filename"];
		$file_path 	= base_path()."uploads/galleries/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($video["filename"]);
		$file_path 	= base_path()."uploads/galleries/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->galleries_model->delete_video($video_id);
				
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
		$this->galleries_model->edit_video($values,$video_id);
		
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
