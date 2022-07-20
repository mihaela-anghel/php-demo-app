<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Banners extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("banners_model");	
		$this->lang->load("admin/banners",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());

		$this->position_options = array(	//"header"		=> $this->lang->line("banner_on_header")." / 1920 x 1080 px",
											//"slider" 		=> $this->lang->line("banner_on_slider")." / 1600 x 680 px",
											//"home" 			=> $this->lang->line("banner_on_home")." / 390 x 344 px",
											"right" 		=> $this->lang->line("banner_on_right")." / 600px latime, orice inaltime",
											//"left"		=> $this->lang->line("banner_on_left")." / 1920 x 1080 px",													
											//"footer"	=> $this->lang->line("banner_on_footer")." / 345 x 120 px",
										);											
	}
	
	/**
	 * List banners
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
				foreach($_POST["item"] as $key=>$banner_id)
				{
					$this->delete_banner($banner_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."banners");
			die();
		}				

		//SEARCH		
		//======================================================			
		$search_by = array	
						(	/*array	(	"field_name"	=> "banner_id",
										"field_label" 	=> "ID",
										"field_type"	=> "input",
										"field_values"	=> array()	
									),*/	
							array	(	"field_name"	=> "position",
										"field_label" 	=> $this->lang->line("banner_position"),
										"field_type"	=> "select",
										"field_values"	=> $this->position_options	
									),																																																
							array	(	"field_name"	=> "status",
										"field_label" 	=> $this->lang->line("status"),
										"field_type"	=> "checkbox",
										"field_values"	=> array("1" => $this->lang->line("active"), "0" => $this->lang->line("inactive"))	
									),														
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("banner_name"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),																																		
							);		
		
		//set search session
		if(isset($_POST["Search"]))		
		{
			$_SESSION[$section_name]["search_by"] = $_POST;
			header("Location: ".admin_url()."banners");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."banners");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["position"]) && !empty($search["position"])) 
				$where .= " AND position = '".$search["position"]."' ";	
										
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
		$sort_fields 			= array("banner_id", "order", "active");
		$default_sort_field 	= "banner_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY position DESC, `".$_SESSION[$section_name]["sort_field"]."` ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY position DESC, `".$default_sort_field."` ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->banners_model->get_banners($where,false,false,false,"count(banner_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."banners/index";
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
		$banners = $this->banners_model->get_banners($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================	
		foreach($banners as $key => $banner)
		{										
			/*
			//get number of items						
			$this->db->where("banner_id", $banner["banner_id"]);
			$this->db->from("banners_files");
			$items_number =  $this->db->count_all_results();
			$banners[$key]["items_number"] = $items_number;
			*/			
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($banners)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($banners);
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
		$data['banners'] 			= $banners;		
		$data['body'] 				= 'admin/banners/list_banners';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add banner
	 */
	function add_banner()
	{		
		$data = array();
				
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');	
			foreach($this->admin_languages as $language)
			{
				$this->form_validation->set_rules("name_". $language["code"],			$this->lang->line("banner_name"),			"trim");	
				//$this->form_validation->set_rules("subtitle_". $language["code"],		$this->lang->line("banner_subtitle"),		"trim");
				$this->form_validation->set_rules("description_". $language["code"],	$this->lang->line("banner_description"),	"trim");				
			}					
			$this->form_validation->set_rules("url",				$this->lang->line("banner_url"),			"trim");
			$this->form_validation->set_rules("position",			$this->lang->line("banner_position"),		"trim|required");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");
			//upload image
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
			{
				//config upload file
				$this->lang->load('upload',$this->admin_default_lang);	
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/banners/';
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
					if($_POST['position'] == "slider")
					{
						$config['width']  = 1600;
						$config['height'] = 680;
					}
					else if($_POST['position'] == "home")
					{
						$config['maintain_ratio'] 	= true;
						$config['width']  = 390;
						$config['height'] = 344;
					}
					else if($_POST['position'] == "left")
					{
						$config['width']  = 965;
						$config['height'] = 445;
					}
					else if($_POST['position'] == "right")
					{
						$config['width']  = 600;
						$config['height'] = 6000;
					}
					else if($_POST['position'] == "footer")
					{
						$config['width']  = 345;
						$config['height'] = 120;
					}	
					//load image manupulation library
					if($file_data['image_width'] != $config['width'] && $file_data['image_height'] != $config['height'])
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
					
					$this->form_validation->set_rules("file",	$this->lang->line("banner_filename"),	"custom_message_1");
					$this->form_validation->set_message("custom_message_1",$upload_error);										
				}
			}	
			else
				$this->form_validation->set_rules("file",		$this->lang->line("banner_filename"),		"required");		
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				/*
				$where_exist = " AND name = '".$_POST["name"][$this->admin_default_lang_id]."' ";					
				$exist 		= $this->banners_model->get_banners($where_exist);
				*/;
				$exist		= false;
				
				if($exist)				
					$data["error_message"] = $this->lang->line("banner_exist");				
				else 
				{
					//values
					$values = array(	"url" 				=> $_POST["url"],	
										"position" 			=> $_POST["position"],
										"active" 			=> $_POST["active"],
										"add_date"			=> date("Y-m-d H:i:s"),																												
									);
					foreach($this->admin_languages as $language)
					{
						$values["name_".$language["code"]] 			= $_POST["name_".$language["code"]];
						//$values["subtitle_".$language["code"]] 		= $_POST["subtitle_".$language["code"]];
						$values["description_".$language["code"]] 	= $_POST["description_".$language["code"]];
					}
									
					if(isset($filename) && $filename)
						$values["filename"] = $filename;
																			
					//insert				
					$this->banners_model->add_banner($values);
					
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
		$this->page_title = $this->lang->line("add_banner");
		
		//send data to view
		//=========================================================					
		$data["body"] 		= "admin/banners/add_banner";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit banner
	 * 
	 * @param int $banner_id
	 */
	function edit_banner($banner_id = false)
	{					
		$data = array();	
		if($banner_id == false) die(); 								
					
		//get banner
		//=========================================================		
		$where 				= "AND banner_id = '".$banner_id."' ";
		$banners 			= $this->banners_model->get_banners($where);
		if(!$banners) die();	
		$banner 			= $banners[0];		
						
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');						
			foreach($this->admin_languages as $language)
			{
				$this->form_validation->set_rules("name_". $language["code"],			$this->lang->line("banner_name"),			"trim");
				//$this->form_validation->set_rules("subtitle_". $language["code"],		$this->lang->line("banner_subtitle"),		"trim");	
				$this->form_validation->set_rules("description_". $language["code"],	$this->lang->line("banner_description"),	"trim");				
			}
			$this->form_validation->set_rules("url",				$this->lang->line("banner_url"),			"trim");
			$this->form_validation->set_rules("position",			$this->lang->line("banner_position"),		"trim|required");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");
			//upload image
			if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "" )
			{
				//config upload file
				$this->lang->load('upload',$this->admin_default_lang);	
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/banners/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '3072';
				$config['max_width'] 	= '5000';
				$config['max_height'] 	= '5000';			
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
					if($_POST['position'] == "slider")
					{
						$config['width']  = 1600;
						$config['height'] = 680;
					}
					else if($_POST['position'] == "home")
					{
						$config['maintain_ratio'] 	= true;
						$config['width']  = 390;
						$config['height'] = 344;
					}
					else if($_POST['position'] == "left")
					{
						$config['width']  = 965;
						$config['height'] = 445;
					}
					else if($_POST['position'] == "right")
					{
						$config['width']  = 600;
						$config['height'] = 6000;
					}	
					else if($_POST['position'] == "footer")
					{
						$config['width']  = 345;
						$config['height'] = 120;
					}
					//load image manupulation library
					if($file_data['image_width'] != $config['width'] && $file_data['image_height'] != $config['height'])
					{
						$this->load->library('image_lib');
						$this->image_lib->initialize($config);					
						$this->image_lib->resize();
					}

					//delete old file
					//=========================================================
					$this->delete_file("filename",  $banner_id, true);	
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
					$this->form_validation->set_rules("file",	$this->lang->line("banner_filename"),	"custom_message_1");
					$this->form_validation->set_message("custom_message_1",$upload_error);
				}
			}	
			//else
				//$this->form_validation->set_rules("file",		$this->lang->line("banner_filename"),		"required");		
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				/*
				$where_exist = " AND name 		= '".$_POST["name"]."'
								 AND banner_id 	!= '".$banner_id."' 
							   ";									
				$exist = $this->banners_model->get_banners($where_exist);
				*/
				$exist = false;
				
				if($exist)
					$data["error_message"] = $this->lang->line("banner_exist");
				else
				{					
					$values = array(	"url" 				=> $_POST["url"],	
										"position" 			=> $_POST["position"],
										"active" 			=> $_POST["active"],																																						
									);
					foreach($this->admin_languages as $language)
					{
						$values["name_".$language["code"]] 			= $_POST["name_".$language["code"]];
						//$values["subtitle_".$language["code"]] 		= $_POST["subtitle_".$language["code"]];
						$values["description_".$language["code"]] 	= $_POST["description_".$language["code"]];
					}					
					if(isset($filename) && $filename)
						$values["filename"] = $filename;																

					//update				
					$this->banners_model->edit_banner($values, $banner_id);
					
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
		$this->page_title = $this->lang->line("edit_banner");										
		
		//send data to view
		//=========================================================
		$data["banner"] 				= $banner;							
		$data["body"] 					= "admin/banners/edit_banner";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete banner
	 * 
	 * @param int $banner_id
	 */
	function delete_banner($banner_id = false, $no_redirect = false)
	{		
		if($banner_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("filename",  $banner_id, true);	
		
		//delete banner
		//=========================================================
		$this->banners_model->delete_banner($banner_id);			
				
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
	 * @param int $banner_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_banner($banner_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->banners_model->edit_banner($values,$banner_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $banner_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $banner_id, $no_redirect = false)
	{
		if($banner_id == false) die();
		
		//get banner
		//=========================================================		
		$where 				= "AND banner_id = '".$banner_id."' ";
		$banners 			= $this->banners_model->get_banners($where);
		if(!$banners) die();	
		$banner 			= $banners[0];		
																
		//delete filename
		//=========================================================
		if($type == "filename")
		{
			//delete file
			$file_name	= $banner[$type];
			$file_path 	= base_path()."uploads/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($banner[$type]);
			$file_path 	= base_path()."uploads/banners/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update
			$this->db->where(array("banner_id" => $banner_id));
			$this->db->update("banners",array($type => ""));	
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
		header("Location: ".admin_url().str_replace("-","/",$section));			
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
			header("Location: ".admin_url().str_replace("-","/",$section));				
		}		
	}	
}
