<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Partner_links extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("partner_links_model");	
		$this->lang->load("admin/partner_links",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List partner_links
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
				foreach($_POST["item"] as $key=>$partner_link_id)
				{
					$this->delete_partner_link($partner_link_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."partner_links");
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
						(	/*array	(	"field_name"	=> "partner_link_id",
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
										"field_label" 	=> $this->lang->line("partner_link_name"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),																																																														
							array	(	"field_name"	=> "type",
										"field_label" 	=> $this->lang->line("partner_link_type"),
										"field_type"	=> "select",
										"field_values"	=> array("file" => $this->lang->line("partner_link_file"), "script" => $this->lang->line("partner_link_script"))	
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
			header("Location: ".admin_url()."partner_links");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."partner_links");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["partner_link_id"]) && !empty($search["partner_link_id"])) 
				$where .= " AND partner_link_id = '".$search["partner_link_id"]."' ";
							
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

			if(isset($search["type"]) && !empty($search["type"])) 
			{
				$where .= " AND type = '".$this->db->escape_str($search["type"])."' ";										
			}
		}																							
		
		//sort
		//======================================================				 	
		$sort_fields 			= array("partner_link_id", "order", "active");
		$default_sort_field 	= "partner_link_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY `".$_SESSION[$section_name]["sort_field"]."` ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY `".$default_sort_field."` ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->partner_links_model->get_partner_links($where,false,false,false,"count(partner_link_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."partner_links/index";
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
		$partner_links = $this->partner_links_model->get_partner_links($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($partner_links as $key => $partner_link)
		{										
			/*
			//get number of items						
			$this->db->where("partner_link_id", $partner_link["partner_link_id"]);
			$this->db->from("partner_links_files");
			$items_number =  $this->db->count_all_results();
			$partner_links[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($partner_links)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($partner_links);
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
		$data['partner_links'] 			= $partner_links;		
		$data['body'] 				= 'admin/partner_links/list_partner_links';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add partner_link
	 */
	function add_partner_link()
	{		
		$data = array();
				
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');									
			$this->form_validation->set_rules('type', '', 'trim');						
			if($_POST['type'] == 'file')
			{
				$this->form_validation->set_rules("name",				$this->lang->line("partner_link_name"),			"trim");
				$this->form_validation->set_rules("url",				$this->lang->line("partner_link_url"),			"trim|prep_url");

				//upload image
				if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
				{
					//config upload file
					$this->lang->load('upload',$this->admin_default_lang);	
					$config['upload_path'] 	= $this->config->item('base_path').'uploads/partner_links/';
					$config['allowed_types']= 'gif|jpg|png';
					$config['max_size']		= '3072';
					$config['max_width'] 	= '50000';
					$config['max_height'] 	= '50000';			
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
						
						$this->form_validation->set_rules("file",	$this->lang->line("partner_link_file"),	"custom_message_1");
						$this->form_validation->set_message("custom_message_1",$upload_error);										
					}
				}	
				else
					$this->form_validation->set_rules("file",		$this->lang->line("partner_link_file"),		"required");
			}
			else if($_POST['type'] == 'script')
			{
				$this->form_validation->set_rules('script',				$this->lang->line('partner_link_script'),		'trim|required');
			}			
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");							
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				//values
				$values = array(	"type" 				=> $_POST["type"],
									"active" 			=> $_POST["active"],
									"add_date"			=> date("Y-m-d H:i:s"),																												
								);				
				if($_POST['type'] == 'file')
				{
					$values["filename"] = $filename;
					$values["name"] 	= $_POST["name"];
					$values["url"] 		= $_POST["url"];
				}	
				
				if($_POST['type'] == 'script')
				{
					$values["script"] = $_POST["script"];					
				}
										
				//insert				
				$this->partner_links_model->add_partner_link($values);
				
				//done message
				$_SESSION["done_message"] = $this->lang->line("done_message_add");
				
				//redirect
				//header("Location: ".current_url());
				header("Location: ".admin_url().strtolower(get_class()));
				die();																		
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_partner_link");
		
		//send data to view
		//=========================================================					
		$data["body"] 		= "admin/partner_links/add_partner_link";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit partner_link
	 * 
	 * @param int $partner_link_id
	 */
	function edit_partner_link($partner_link_id = false)
	{					
		$data = array();	
		if($partner_link_id == false) die(); 								
					
		//get partner_link
		//=========================================================		
		$where 				= "AND partner_link_id = '".$partner_link_id."' ";
		$partner_links 			= $this->partner_links_model->get_partner_links($where);
		if(!$partner_links) die();	
		$partner_link 			= $partner_links[0];		
						
		//edit form
		//=========================================================	
		if(isset($_POST["Edit"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');									
			$this->form_validation->set_rules('type', '', 'trim');						
			if($_POST['type'] == 'file')
			{
				$this->form_validation->set_rules("name",				$this->lang->line("partner_link_name"),			"trim");
				$this->form_validation->set_rules("url",				$this->lang->line("partner_link_url"),			"trim|prep_url");

				//upload image
				if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
				{
					//config upload file
					$this->lang->load('upload',$this->admin_default_lang);	
					$config['upload_path'] 	= $this->config->item('base_path').'uploads/partner_links/';
					$config['allowed_types']= 'gif|jpg|png';
					$config['max_size']		= '3072';
					$config['max_width'] 	= '50000';
					$config['max_height'] 	= '50000';			
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
						$this->delete_file("filename",  $partner_link_id, true);	
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
						
						$this->form_validation->set_rules("file",	$this->lang->line("partner_link_file"),	"custom_message_1");
						$this->form_validation->set_message("custom_message_1",$upload_error);										
					}
				}	
				else
					$this->form_validation->set_rules("file",		$this->lang->line("partner_link_file"),		($partner_link["type"]=="file"?"trim":"required"));
			}
			else if($_POST['type'] == 'script')
			{
				$this->form_validation->set_rules('script',				$this->lang->line('partner_link_script'),		'trim|required');
			}			
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");							
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				//values
				$values = array(	"type" 				=> $_POST["type"],
									"active" 			=> $_POST["active"],																																					
								);				
				if($_POST['type'] == 'file')
				{
					if(isset($filename) && $filename)
						$values["filename"] = $filename;
					$values["name"] 		= $_POST["name"];
					$values["url"] 			= $_POST["url"];
					$values["script"] 		= "";	
				}	
				
				if($_POST['type'] == 'script')
				{
					//delete old file
					//=========================================================
					$this->delete_file("filename",  $partner_link_id, true);
						
					$values["filename"] 	= "";
					$values["name"] 		= "";
					$values["url"] 			= "";					
					$values["script"]	 	= $_POST["script"];	
					
				}
					
				//update				
				$this->partner_links_model->edit_partner_link($values, $partner_link_id);
				
				//done message
				$_SESSION["done_message"] = $this->lang->line("done_message_edit");
				
				//redirect
				//header("Location: ".current_url());
				header("Location: ".admin_url().strtolower(get_class()));
				die();												 						
			}						
		}				

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_partner_link");										
		
		//send data to view
		//=========================================================
		$data["partner_link"] 				= $partner_link;							
		$data["body"] 					= "admin/partner_links/edit_partner_link";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete partner_link
	 * 
	 * @param int $partner_link_id
	 */
	function delete_partner_link($partner_link_id = false, $no_redirect = false)
	{		
		if($partner_link_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("filename",  $partner_link_id, true);	
		
		//delete partner_link
		//=========================================================
		$this->partner_links_model->delete_partner_link($partner_link_id);			
				
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
	 * @param int $partner_link_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_partner_link($partner_link_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->partner_links_model->edit_partner_link($values,$partner_link_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
		
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $partner_link_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $partner_link_id, $no_redirect = false)
	{
		if($partner_link_id == false) die();
		
		//get partner_link
		//=========================================================		
		$where 					= "AND partner_link_id = '".$partner_link_id."' ";
		$partner_links 			= $this->partner_links_model->get_partner_links($where);
		if(!$partner_links) die();	
		$partner_link 			= $partner_links[0];		
																
		//delete filename
		//=========================================================
		if($type == "filename")
		{
			//delete file
			$file_name	= $partner_link[$type];
			$file_path 	= base_path()."uploads/partner_links/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($file_name);
			$file_path 	= base_path()."uploads/partner_links/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("partner_link_id" => $partner_link_id));
			$this->db->update("partner_links",array($type => ""));	
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
