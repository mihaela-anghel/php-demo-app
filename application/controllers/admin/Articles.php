<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Articles extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("articles_model");	
		$this->lang->load("admin/articles",$this->admin_default_lang);
		
		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List articles
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
				foreach($_POST["item"] as $key=>$article_id)
				{
					$this->delete_article($article_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."articles");
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
						(	 array	(	"field_name"	=> "published_date",
										"field_label" 	=> $this->lang->line("article_published_date"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									), 																				
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("article_name"),
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
			header("Location: ".admin_url()."articles");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]))		
		{
			if(isset($_SESSION[$section_name]["search_by"]))
			    unset($_SESSION[$section_name]["search_by"]);
			
			header("Location: ".admin_url()."articles");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["url"]) && !empty($search["url"])) 
				$where .= " AND t1.url = '".$search["url"]."' ";
							
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
				$where .= " AND LOWER(t2.name) LIKE LOWER('%".$this->db->escape_like_str($search["name"])."%') ";										
			}	
			
			if(isset($search["published_date"]) && !empty($search["published_date"])) 
				$where .= " AND t1.published_date = '".$search["published_date"]."' ";
		}																									
			
		//sort
		//======================================================				 	
		$sort_fields 			= array("t1.article_id", "t1.order", "t1.active");
		$default_sort_field 	= "t1.order"; 
		$default_sort_dir 		= "desc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->articles_model->get_articles($where,false,false,false,"count(t1.article_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."articles/index";
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
		$articles = $this->articles_model->get_articles($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($articles as $key => $article)
		{
			//get number of images		
			$where 							= "AND article_id = ".$article['article_id']." ";						
			$images 						= $this->articles_model->get_images($where,false,false,false,"count(*) as numrows");
			$articles[$key]["images_number"]	= $images[0]["numrows"];
						
			//get number of files		
			$where 							= "AND article_id = ".$article['article_id']." ";			
			$files 							= $this->articles_model->get_files($where,false,false,false,"count(*) as numrows");
			$articles[$key]["files_number"]	= $files[0]["numrows"];
			
			//get number of videos		
			$where 							= "AND article_id = ".$article['article_id']." ";			
			$videos 						= $this->articles_model->get_videos($where,false,false,false,"count(*) as numrows");
			$articles[$key]["videos_number"]= $videos[0]["numrows"];
			
			/*
			//get first images	
			$articles[$key]["image"]			= "";	
			$where 							= "AND article_id = ".$article['article_id']." ";			
			$orderby 						= " ORDER BY `order` ASC";
			$images 						= $this->articles_model->get_images($where,$orderby,1,0,"filename");
			if($images)
				$articles[$key]["image"]		= $images[0]["filename"];
			*/			
						
			/*
			//get number of items						
			$this->db->where("article_id", $article["article_id"]);
			$this->db->from("articles_files");
			$items_number =  $this->db->count_all_results();
			$articles[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($articles)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($articles);
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
		$data['articles'] 			= $articles;		
		$data['body'] 				= 'admin/articles/list_articles';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add article
	 */
	function add_article()
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("article_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("article_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("article_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}	
			//$this->form_validation->set_rules("url",		       $this->lang->line("article_url"),	"trim|prep_url");
			//$this->form_validation->set_rules("map",		       $this->lang->line("article_url"),	"trim");
			$this->form_validation->set_rules("published_date",	   $this->lang->line("article_published_date"),	"trim|required");
			$this->form_validation->set_rules("active",			   $this->lang->line("status"),	"trim|required");			
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
				$exist 		= $this->articles_model->get_articles($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("article_exist");				
				else 
				{
					//values				
					$values = array(	//"url"	            => $_POST["url"],
					                    //"map"	            => $_POST["map"],
					                    "published_date"	=> $_POST["published_date"],
										"active" 			=> $_POST["active"],
										"add_date"			=> date("Y-m-d H:i:s"),
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
					$article_id = $this->articles_model->add_article($values, $details);
					
					//done message
					$_SESSION["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					header("Location: ".admin_url().strtolower(get_class()));
					//header("Location: ".current_url());
					//header("Location: ".admin_url().strtolower(get_class())."/edit_article/".$article_id);
					die();
				}														
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_article");
		
		//send data to view
		//=========================================================		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;		
		$data["body"] 					= "admin/articles/add_article";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit article
	 * 
	 * @param int $article_id
	 */
	function edit_article($article_id = false)
	{					
		$data = array();	
		if($article_id == false) die(); 								
			
		//get article and article_details
		//=========================================================		
		$articles = $this->articles_model->get_just_articles("AND article_id = '".$article_id."'");
		if(!$articles) die();		
		$article = $articles[0];
		$array_article_details  = $this->articles_model->get_just_articles_details("AND article_id = '".$article_id."'");
		foreach($array_article_details as $array_article_detail)				
			foreach($array_article_detail as $field=>$value)			
				$article_details[$field][$array_article_detail["lang_id"]] = $value;
								
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
				 
				$this->form_validation->set_rules("name[".$language["lang_id"]."]",				$this->lang->line("article_name").$label_language,		"trim|required");
				//$this->form_validation->set_rules("abstract[".$language["lang_id"]."]",			$this->lang->line("article_abstract").$label_language,	"trim");
				$this->form_validation->set_rules("description[".$language["lang_id"]."]",		$this->lang->line("article_description").$label_language,"trim");
				$this->form_validation->set_rules("meta_title[".$language["lang_id"]."]",		$this->lang->line("meta_title").$label_language,		"trim");
				$this->form_validation->set_rules("meta_description[".$language["lang_id"]."]",	$this->lang->line("meta_description").$label_language,	"trim");
				$this->form_validation->set_rules("meta_keywords[".$language["lang_id"]."]",	$this->lang->line("meta_keywords").$label_language,		"trim");									
			}					
			//$this->form_validation->set_rules("url",		       $this->lang->line("article_url"),	"trim|prep_url");
			//$this->form_validation->set_rules("map",		       $this->lang->line("article_url"),	"trim");
			$this->form_validation->set_rules("published_date",	   $this->lang->line("article_published_date"),	"trim|required");
			$this->form_validation->set_rules("active",			   $this->lang->line("status"),	"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/*
				$where_exist = " AND t2.name 		= '".$_POST["name"][$this->admin_default_lang_id]."'
								 AND t2.lang_id 	= ".$this->admin_default_lang_id."
								 AND t2.article_id 	!= '".$article_id."' 
							   ";									
				$exist = $this->articles_model->get_articles($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("article_exist");
				else
				{
					//values
					$values = array(	//"url"	            => $_POST["url"],
					                    //"map"	            => $_POST["map"],
					                    "published_date"	=> $_POST["published_date"],
										"active"			=> $_POST["active"]
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
					$this->articles_model->edit_article($values, $details, $article_id);
					
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
		$this->page_title = $this->lang->line("edit");										
		
		//send data to view
		//=========================================================
		$data["article"] 				= $article;
		$data["article_details"] 		= $article_details;		
		$data["show_label_language"] 	= $show_label_language;
		$data["languages"] 				= $languages;				
		$data["body"] 					= "admin/articles/edit_article";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete article
	 * 
	 * @param int $article_id
	 */
	function delete_article($article_id = false, $no_redirect = false)
	{		
		if($article_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $article_id, true);	
		$this->delete_file("banner", $article_id, true);
		
		//delete images (images gallery)
		//=========================================================				
		$where 		= "AND article_id = '".$article_id."' ";			
		$images 	= $this->articles_model->get_images($where, false, false, false, "image_id");
		foreach($images as $image)			
			$this->images_delete($image["image_id"], true);

		//delete files (files gallery)
		//=========================================================				
		$where 		= "AND article_id = '".$article_id."' ";			
		$files 	= $this->articles_model->get_files($where, false, false, false, "file_id");
		foreach($files as $file)			
			$this->files_delete($file["file_id"], true);
		
		//delete videos (videos gallery)
		//=========================================================				
		$where 		= "AND article_id = '".$article_id."' ";			
		$videos 	= $this->articles_model->get_videos($where, false, false, false, "video_id");
		foreach($videos as $video)			
			$this->videos_delete($video["video_id"], true);	
		
		//delete article
		//=========================================================
		$this->articles_model->delete_article($article_id);			
				
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
	 * @param int $article_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_article($article_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->articles_model->edit_article($values,false,$article_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $article_id
	 */
	function upload_file($type, $article_id)
	{		
		$data = array();	
		if($article_id == false) die();

		//get article
		//=========================================================
		$articles 	= $this->articles_model->get_articles(" AND t1.article_id = '".$article_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$articles) die();
		$article		= $articles[0];
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/articles/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($article["url_key"])?$article["url_key"]:"image_".$article_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $article_id, $no_redirect = true);	
						
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
					/* $config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["create_thumb"] 	= TRUE;
					$config["thumb_marker"] 	= "_th";
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 400;
					$config["height"] 			= 4000;																														
					//load image manupulation library
					$this->load->library("image_lib");					
					$this->image_lib->initialize($config);
					$this->image_lib->resize(); */

					//update
					$values = array($type => $file_data["file_name"]);
					$this->db->where("article_id", $article_id);
					$this->db->update("articles",$values);	
										
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/articles/banners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($article["url_key"])?$article["url_key"]:"banner_".$article_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $article_id, $no_redirect = true);	
					
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
					$this->db->where("article_id", $article_id);
					$this->db->update("articles",$values);	
										
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
		$data["article"] = $article;			
		$data["body"]  	= "admin/articles/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $article_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $article_id, $no_redirect = false)
	{
		if($article_id == false) die();
		
		//get article
		//=========================================================
		$articles 	= $this->articles_model->get_articles(" AND t1.article_id = '".$article_id."' AND lang_id = '".$this->admin_default_lang_id."' ");
		if(!$articles) die();
		$article		= $articles[0];			
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $article["image"];
			$file_path 	= base_path()."uploads/articles/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($article["image"]);
			$file_path 	= base_path()."uploads/articles/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("article_id" => $article_id));
			$this->db->update("articles",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $article["banner"];
			$file_path 	= base_path()."uploads/articles/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("article_id" => $article_id));
			$this->db->update("articles",array($type => ""));	
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
	 * @param int $article_id
	 */
	function images($article_id = false)
	{
		$data = array();
		if($article_id == false) die(); 

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
				
		//get article
		//=========================================================		
		$where 				= "AND t1.article_id = '".$article_id."' ";
		$articles 			= $this->articles_model->get_articles($where);
		if(!$articles) die();	
		$article 			= $articles[0];							
		
		//get images
		//=========================================================		
		$where 				= "AND article_id = '".$article_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$images 			= $this->articles_model->get_images($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("article_images");			
						
		//send data to view	
		//=========================================================	
		$data["article"] 	= $article;			
		$data["images"]		= $images;	
		$data["body"] 		= "admin/articles/list_images";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple images upload
	 * 
	 * @param int $article_id
	 */
	function images_upload($article_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get article
			//=========================================================
			$where 				= "AND  t1.article_id = '".$article_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$articles 			= $this->articles_model->get_articles($where,false,false,false,$fields);
			if(!$articles) die();					
			$article 			= $articles[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/articles/images/";
			$config["allowed_types"]= "gif|jpg|png";
			$config["max_size"]		= "10240";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			$config["file_name"] 	= str_replace(".","-",$article["url_key"]);
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
				$config["height"] 			= 8000;																							
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
				$config["width"] 			= 400;
				$config["height"]			= 4000;													
				//load image manupulation library
				$this->load->library("image_lib");
				$this->image_lib->clear();					
				$this->image_lib->initialize($config);
				$this->image_lib->resize();								
				
				//set order
				$this->db->where("article_id", $article_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("articles_images");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"article_id" 	=> $article_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->articles_model->add_image($values);
				
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
		$images 			= $this->articles_model->get_images($where);
		if(!$images) die();
		$image				= $images[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $image["filename"];
		$file_path 	= base_path()."uploads/articles/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($image["filename"]);
		$file_path 	= base_path()."uploads/articles/images/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->articles_model->delete_image($image_id);
				
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
		$this->articles_model->edit_image($values,$image_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	

	/**
	 * file gallery
	 *  
	 * @param int $article_id
	 */
	function files($article_id = false)
	{
		$data = array();
		if($article_id == false) die(); 

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
				
		//get article
		//=========================================================		
		$where 				= "AND t1.article_id = '".$article_id."' ";
		$articles 			= $this->articles_model->get_articles($where);
		if(!$articles) die();	
		$article 			= $articles[0];							
		
		//get files
		//=========================================================		
		$where 				= "AND article_id = '".$article_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$files 			= $this->articles_model->get_files($where,$orderby);										
		
		//page title
		//=========================================================
		$this->page_title = $this->lang->line("article_files");			
						
		//send data to view	
		//=========================================================	
		$data["article"] 	= $article;			
		$data["files"]		= $files;	
		$data["body"] 		= "admin/articles/list_files";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple files upload
	 * 
	 * @param int $article_id
	 */
	function files_upload($article_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get article
			//=========================================================
			$where 				= "AND  t1.article_id = '".$article_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$articles 			= $this->articles_model->get_articles($where,false,false,false,$fields);
			if(!$articles) die();					
			$article 			= $articles[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/articles/files/";
			$config["allowed_types"]= "*";
			$config["max_size"]		= "20480";								
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();							
				
				//set order
				$this->db->where("article_id", $article_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("articles_files");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"article_id" 	=> $article_id, 
									"filename" 		=> $file_data["file_name"],
									"original_filename" => $_FILES["file"]["name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->articles_model->add_file($values);
				
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
		$files 			= $this->articles_model->get_files($where);
		if(!$files) die();
		$file				= $files[0];
		
		//detele file
		//=========================================================		
		$file_name	= $file["filename"];
		$file_path 	= base_path()."uploads/articles/files/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);					
		
		//delete from db
		//=========================================================
		$this->articles_model->delete_file($file_id);
				
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
		$this->articles_model->edit_file($values,$file_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * video gallery
	 *  
	 * @param int $article_id
	 */
	function videos($article_id = false)
	{
		$data = array();
		if($article_id == false) die();

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
				$this->db->where("article_id", $article_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("articles_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"article_id" 		=> $article_id, 
									"video" 		=> $_POST["video"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->articles_model->add_video($values);
				
				header("Location: ".current_url());			
				die();																
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
				
		//get article
		//=========================================================		
		$where 				= "AND t1.article_id = '".$article_id."' ";
		$articles 				= $this->articles_model->get_articles($where);
		if(!$articles) die();	
		$article 				= $articles[0];							
		
		//get videos
		//=========================================================		
		$where 				= "AND article_id = '".$article_id."' ";
		$orderby 			= "ORDER BY `order` asc";	
		$videos 			= $this->articles_model->get_videos($where,$orderby);										
		
		//article title
		//=========================================================
		$this->page_title = $this->lang->line("article_videos");			
						
		//send data to view	
		//=========================================================	
		$data["article"] 	= $article;			
		$data["videos"]		= $videos;	
		$data["body"] 		= "admin/articles/list_videos";
		$this->load->view("admin/template",$data);
	}

	/**
	 * Multiple videos upload
	 * 
	 * @param int $article_id
	 */
	function videos_upload($article_id)
	{					
		if (!empty($_FILES))
		{																				
			//get mime type by extension
			//=========================================================
			$this->load->helper("file");
			$_FILES["file"]["type"] = get_mime_by_extension($_FILES["file"]["name"]);
						
			//get article
			//=========================================================
			$where 				= "AND  t1.article_id = '".$article_id."' AND lang_id = ".$this->admin_default_lang_id." ";		
			$fields				= "url_key";
			$articles 				= $this->articles_model->get_articles($where,false,false,false,$fields);
			if(!$articles) die();					
			$article 				= $articles[0];						
						
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->admin_default_lang);			
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/articles/videos/";
			$config["allowed_types"]= "mp4";
			$config["max_size"]		= "20480";
			$config["max_width"] 	= "10000";
			$config["max_height"] 	= "10000";			
			//$config["file_name"] 	= str_replace(".","-",$article["url_key"]);
			$config["overwrite"] 	= FALSE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("file"))
			{				
				$file_data = $this->upload->data();				
								
				//set order
				$this->db->where("article_id", $article_id);
				$this->db->select_max("order", "max_order");
				$query 		= $this->db->get("articles_videos");
				$row 		= $query->row_array();
				$new_order 	= $row["max_order"]+1;
				
				//values
				$values = array(	"article_id" 		=> $article_id, 
									"filename" 		=> $file_data["file_name"],
									"order"			=> $new_order,									
								);	

				//insert				
				$this->articles_model->add_video($values);
				
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
		$videos 			= $this->articles_model->get_videos($where);
		if(!$videos) die();
		$video				= $videos[0];
		
		//detele file
		//=========================================================
		//delete file
		$file_name	= $video["filename"];
		$file_path 	= base_path()."uploads/articles/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);
			
		//delete thumb
		$file_name	= get_thumb_name($video["filename"]);
		$file_path 	= base_path()."uploads/articles/videos/".$file_name;	
		if($file_name && file_exists($file_path))	
			unlink($file_path);	
		
		//delete from db
		//=========================================================
		$this->articles_model->delete_video($video_id);
				
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
		$this->articles_model->edit_video($values,$video_id);
		
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
