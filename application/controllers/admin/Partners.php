<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Partners extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("partners_model");	
		$this->lang->load("admin/partners",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List partners
	 * 
	 * @param int $offset
	 */
	function index($offset = 0)
	{										
		//controller name
		//======================================================		
		$section_name 	= strtolower(get_class());
		$data			= array();
		$where 			= false;
		
		//delete all
		//==================================================================
		if(isset($_POST['DeleteSelected']))
		{			
			$aux = 0;
			if(isset($_POST["item"]) && count($_POST["item"]))
			{
				foreach($_POST["item"] as $key=>$partner_id)
				{
					$this->delete_partner($partner_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."partners");
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
						(	/*array	(	"field_name"	=> "partner_id",
										"field_label" 	=> "ID",
										"field_type"	=> "input",
										"field_values"	=> array()	
									),
							array	(	"field_name"	=> "category_id",
										"field_label" 	=> "Category ID",
										"field_type"	=> "select",
										"field_values"	=> $categories_select	
									),*/																																																															
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("partner_name"),
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
			header("Location: ".admin_url()."partners");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."partners");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["partner_id"]) && !empty($search["partner_id"])) 
				$where .= " AND partner_id = '".$search["partner_id"]."' ";
							
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
				$where .= " AND LOWER(name) LIKE '%".strtolower($this->db->escape_like_str($search["name"]))."%' ";										
			}									
		}																							
		
		//sort
		//======================================================				 	
		$sort_fields 			= array("partner_id", "order", "active");
		$default_sort_field 	= "partner_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY `".$_SESSION[$section_name]["sort_field"]."` ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY `".$default_sort_field."` ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->partners_model->get_partners($where,false,false,false,"count(partner_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."partners/index";
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
		$partners = $this->partners_model->get_partners($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($partners as $key => $partner)
		{										
			/*
			//get number of items						
			$this->db->where("partner_id", $partner["partner_id"]);
			$this->db->from("partners_files");
			$items_number =  $this->db->count_all_results();
			$partners[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($partners)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($partners);
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
		$data['partners'] 			= $partners;		
		$data['body'] 				= 'admin/partners/list_partners';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add partner
	 */
	function add_partner()
	{		
		$data = array();
				
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');						
			$this->form_validation->set_rules("name",				$this->lang->line("partner_name"),			"trim|required");
			//$this->form_validation->set_rules("description",		$this->lang->line("partner_description"),	"trim");
			$this->form_validation->set_rules("url",				$this->lang->line("partner_url"),			"trim|prep_url");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");			
			//upload image
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
			{
				//config upload file
				$this->lang->load('upload',$this->admin_default_lang);	
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/partners/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '3072';
				$config['max_width'] 	= '50000';
				$config['max_height'] 	= '50000';			
				//$config['file_name'] 	= 'banner_'.uniqid('');
				$config['overwrite'] 	= FALSE;
				
				//load upload library
				$this->load->library('upload', $config);					
				if ($this->upload->do_upload('file'))
				{
					$file_data = $this->upload->data();
					$filename  = $file_data['file_name'];
					
					//config image
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $config['upload_path'].$file_data['file_name'];
					$config['maintain_ratio'] 	= TRUE;
					$config['width']  			= 300;
					$config['height'] 			= 3000;						
					//load image manupulation library
					if($file_data['image_width'] > $config['width'])
					{
						$this->load->library('image_lib');
						$this->image_lib->initialize($config);					
						$this->image_lib->resize();
					}															
				}
				else
				{
					$filename 		= false;
					$upload_error 	= $this->upload->display_errors('','');
				}

				//upload error				
				if(isset($upload_error))
				{					
					if(!isset($_POST["file"]) || $_POST["file"] == "") $_POST["file"] = " ";
					
					$this->form_validation->set_rules("file",	$this->lang->line("partner_image"),	"custom_message_1");
					$this->form_validation->set_message("custom_message_1",$upload_error);										
				}
			}	
			else
				$this->form_validation->set_rules("file",		$this->lang->line("partner_image"),		"required");					
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				$where_exist = " AND name = '".$this->db->escape_str($_POST["name"])."' ";					
				$exist 		 = $this->partners_model->get_partners($where_exist);				
				
				if($exist)				
					$data["error_message"] = $this->lang->line("partner_exist");				
				else 
				{
					//values
					$values = array(	"active" 			=> $_POST["active"],
										"name" 				=> $_POST["name"],
										//"description" 		=> $_POST["description"],
										"url" 				=> $_POST["url"],
										"url_key"			=> url_key($_POST["name"],"-"),
										"add_date"			=> date("Y-m-d H:i:s"),																												
									);
					if(isset($filename) && $filename)
						$values["image"] = $filename;
											
					//insert				
					$this->partners_model->add_partner($values);
					
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
		$this->page_title = $this->lang->line("add_partner");
		
		//send data to view
		//=========================================================					
		$data["body"] 		= "admin/partners/add_partner";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit partner
	 * 
	 * @param int $partner_id
	 */
	function edit_partner($partner_id = false)
	{					
		$data = array();	
		if($partner_id == false) die(); 								
					
		//get partner
		//=========================================================		
		$where 				= "AND partner_id = '".$partner_id."' ";
		$partners 			= $this->partners_model->get_partners($where);
		if(!$partners) die();	
		$partner 			= $partners[0];		
						
		//edit form
		//=========================================================	
		if(isset($_POST["Edit"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');						
			$this->form_validation->set_rules("name",				$this->lang->line("partner_name"),			"trim|required");
			//$this->form_validation->set_rules("description",		$this->lang->line("partner_description"),	"trim");
			$this->form_validation->set_rules("url",				$this->lang->line("partner_url"),			"trim|prep_url");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");			
			//upload image
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
			{
				//config upload file
				$this->lang->load('upload',$this->admin_default_lang);	
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/partners/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '3072';
				$config['max_width'] 	= '50000';
				$config['max_height'] 	= '50000';			
				//$config['file_name'] 	= 'banner_'.uniqid('');
				$config['overwrite'] 	= FALSE;
				
				//load upload library
				$this->load->library('upload', $config);					
				if ($this->upload->do_upload('file'))
				{
					$file_data = $this->upload->data();
					$filename  = $file_data['file_name'];
					
					//config image
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $config['upload_path'].$file_data['file_name'];
					$config['maintain_ratio'] 	= TRUE;
					$config['width']  			= 300;
					$config['height'] 			= 3000;	
					//load image manupulation library
					if($file_data['image_width'] > $config['width'])
					{
						$this->load->library('image_lib');
						$this->image_lib->initialize($config);					
						$this->image_lib->resize();
					}

					//delete old file
					//=========================================================
					$this->delete_file("image",  $partner_id, true);	
				}
				else
				{
					$filename 		= false;
					$upload_error 	= $this->upload->display_errors('','');
				}

				//upload error				
				if(isset($upload_error))
				{					
					if(!isset($_POST["file"]) || $_POST["file"] == "") $_POST["file"] = " ";
					$this->form_validation->set_rules("file",	$this->lang->line("partner_image"),	"custom_message_1");
					$this->form_validation->set_message("custom_message_1",$upload_error);
				}
			}	
			elseif($partner["image"] == "")
				$this->form_validation->set_rules("file",		$this->lang->line("partner_image"),		"required");		
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				$where_exist = " AND name 			= '".$this->db->escape_str($_POST["name"])."'
								 AND partner_id 	!= '".$partner_id."' 
							   ";									
				$exist = $this->partners_model->get_partners($where_exist);				
				
				if($exist)
					$data["error_message"] = $this->lang->line("partner_exist");
				else
				{					
					//values
					$values = array(	"active" 			=> $_POST["active"],
										"name" 				=> $_POST["name"],
										//"description" 		=> $_POST["description"],
										"url" 				=> $_POST["url"],
										"url_key"			=> url_key($_POST["name"],"-"),																				
									);									
					if(isset($filename) && $filename)
						$values["image"] = $filename;
						
					//update				
					$this->partners_model->edit_partner($values, $partner_id);
					
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
		$this->page_title = $this->lang->line("edit_partner");										
		
		//send data to view
		//=========================================================
		$data["partner"] 				= $partner;							
		$data["body"] 					= "admin/partners/edit_partner";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete partner
	 * 
	 * @param int $partner_id
	 */
	function delete_partner($partner_id = false, $no_redirect = false)
	{		
		if($partner_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $partner_id, true);	
		$this->delete_file("banner", $partner_id, true);				
		
		//delete partner
		//=========================================================
		$this->partners_model->delete_partner($partner_id);			
				
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
	 * @param int $partner_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_partner($partner_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->partners_model->edit_partner($values,$partner_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $partner_id
	 */
	function upload_file($type, $partner_id)
	{		
		$data = array();	
		if($partner_id == false) die();

		//get partner
		//=========================================================		
		$where 				= "AND partner_id = '".$partner_id."' ";
		$partners 			= $this->partners_model->get_partners($where);
		if(!$partners) die();	
		$partner 			= $partners[0];				
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/partners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($partner["url_key"])?$partner["url_key"]:"image_".$partner_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $partner_id, $no_redirect = true);	
						
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 250;
					$config["height"] 			= 2500;
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
					$this->db->where("partner_id", $partner_id);
					$this->db->update("partners",$values);	
										
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/partners/banners/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				$config["file_name"] 	= (isset($partner["url_key"])?$partner["url_key"]:"banner_".$partner_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $partner_id, $no_redirect = true);	
					
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
					$this->db->where("partner_id", $partner_id);
					$this->db->update("partners",$values);	
										
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
		$data["partner"] = $partner;			
		$data["body"]  	= "admin/partners/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $partner_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $partner_id, $no_redirect = false)
	{
		if($partner_id == false) die();
		
		//get partner
		//=========================================================		
		$where 				= "AND partner_id = '".$partner_id."' ";
		$partners 			= $this->partners_model->get_partners($where);
		if(!$partners) die();	
		$partner 			= $partners[0];		
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $partner["image"];
			$file_path 	= base_path()."uploads/partners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($partner["image"]);
			$file_path 	= base_path()."uploads/partners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("partner_id" => $partner_id));
			$this->db->update("partners",array($type => ""));	
		}
		
		//delete banner
		//=========================================================
		if($type == "banner")
		{
			//delete file
			$file_name	= $partner["banner"];
			$file_path 	= base_path()."uploads/partners/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);							

			//db update
			$this->db->where(array("partner_id" => $partner_id));
			$this->db->update("partners",array($type => ""));	
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
