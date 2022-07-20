<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Users extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("users_model");	
		$this->lang->load("users",$this->admin_default_lang);			

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	/**
	 * List users
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
				foreach($_POST["item"] as $key=>$user_id)
				{
					$this->delete_user($user_id, $no_redirect = true);
					$aux++;
				}
			}

			$_SESSION["done_message"] = str_replace("{x}", $aux, $this->lang->line("done_message_delete_all"));
			header('Location: '.admin_url()."users");
			die();
		}				

		//SEARCH		
		//======================================================
		///create countries select
		$this->load->library('locations');
		$countries 	=  $this->locations->get_countries();
		foreach($countries as $country)
			$countries_select[$country['country_id']] = $country['country_name']; 
					
		$search_by = array	
						(	array	(	"field_name"	=> "user_id",
										"field_label" 	=> "ID",
										"field_type"	=> "input",
										"field_values"	=> array()	
									),																																																																					
							array	(	"field_name"	=> "name",
										"field_label" 	=> $this->lang->line("user_name"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),
							array	(	"field_name"	=> "email",
										"field_label" 	=> $this->lang->line("user_email"),
										"field_type"	=> "input",
										"field_values"	=> array()	
									),	
							array	(	"field_name"	=> "country_id",
										"field_label" 	=> $this->lang->line("user_country"),
										"field_type"	=> "select",
										"field_values"	=> $countries_select	
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
			header("Location: ".admin_url()."users");
			die();
		}
			
		//reset search session	
		if(isset($_POST["Reset"]) && isset($_SESSION[$section_name]["search_by"]))		
		{
			unset($_SESSION[$section_name]["search_by"]);
			header("Location: ".admin_url()."users");
			die();			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]["search_by"]))
		{					
			$search = $_SESSION[$section_name]["search_by"];		
						
			if(isset($search["user_id"]) && !empty($search["user_id"])) 
				$where .= " AND user_id = '".$search["user_id"]."' ";
				
			if(isset($search["country_id"]) && !empty($search["country_id"])) 
				$where .= " AND country_id = '".$search["country_id"]."' ";											
				
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
				$where .= " AND name LIKE '%".$this->db->escape_like_str($search["name"])."%' ";

			if(isset($search["email"]) && !empty($search["email"])) 			
				$where .= " AND email LIKE '%".$this->db->escape_str($search["email"])."%' ";	
												
		}																							
		
		//sort
		//======================================================				 	
		$sort_fields 			= array("user_id", "order", "active");
		$default_sort_field 	= "user_id"; 
		$default_sort_dir 		= "desc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY `".$_SESSION[$section_name]["sort_field"]."` ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY `".$default_sort_field."` ".$default_sort_dir;
			
		//pagination
		//======================================================						
		$rows 					= $this->users_model->get_users($where,false,false,false,"count(user_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."users/index";
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
		$users = $this->users_model->get_users($where,$orderby,$config["per_page"],$offset);				
		
		//get extra info
		//======================================================
		$this->load->library('locations');	
		foreach($users as $key => $user)
		{										
			//get country			
			$users[$key]['country']	= $this->locations->get_country_name($user['country_id']);
			
			/* //get number of items						
			$this->db->where("user_id", $user["user_id"]);
			$this->db->from("competitions_participants");
			$items_number =  $this->db->count_all_results();
			$users[$key]["items_number"] = $items_number; */	
			
			$where 			    = " AND user_id = '".$user['user_id']."'  AND c.status = 'close' ";
			$participations 	= $this->users_model->get_participants($where,false,false,false,"count(*) as nr");
			$users[$key]["closed_number"] = 0;
			if(isset($participations[0]["nr"]))			    
                $users[$key]["closed_number"] = $participations[0]["nr"];
			
            $where 			    = " AND user_id = '".$user['user_id']."'  AND c.status = 'open' ";
            $participations 	= $this->users_model->get_participants($where,false,false,false,"count(*) as nr");
            $users[$key]["opened_number"] = 0;
            if(isset($participations[0]["nr"]))
                $users[$key]["opened_number"] = $participations[0]["nr"];
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($users)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($users);
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
		$data['users'] 				= $users;		
		$data['body'] 				= 'admin/users/list_users';
		$this->load->view('admin/template',$data);					
	}
	
	/**
	 * Add user
	 */
	function add_user()
	{		
		$data = array();
				
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');						
			$this->form_validation->set_rules("name",				$this->lang->line("user_name"),				"trim|required");
			$this->form_validation->set_rules("city",				$this->lang->line("user_city"),				"trim");
			$this->form_validation->set_rules("country_id",			$this->lang->line("user_country"),			"trim");
			$this->form_validation->set_rules("birthday",			$this->lang->line("user_birthday"),			"trim");
			$this->form_validation->set_rules("school",				$this->lang->line("user_school"),			"trim");			
			$this->form_validation->set_rules("guide",				$this->lang->line("user_guide"),			"trim");
			$this->form_validation->set_rules("phone",				$this->lang->line("user_phone"),			"trim|numeric|min_length[10]");
			$this->form_validation->set_rules("email",				$this->lang->line("user_email"),			"trim|required|valid_email|callback_check_email");
			$this->form_validation->set_rules("password",			$this->lang->line("user_password"),			"trim|required|min_length[8]");
			$this->form_validation->set_rules("confirm_password",	$this->lang->line("user_confirm_password"),	"trim|required|matches[password]");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");											
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');			
			$form_is_valid = $this->form_validation->run();											
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{								
				//values
				$values = array(	"name" 					=> $_POST["name"],
									"city" 					=> $_POST["city"],
									"country_id" 			=> $_POST["country_id"],
									"birthday" 				=> $_POST["birthday"],
									"school" 				=> $_POST["school"],
									"guide" 				=> $_POST["guide"],
				                    "phone" 				=> $_POST["phone"],
									"email" 				=> $_POST["email"],
									"password" 				=> md5($_POST["password"]),
									"active" 				=> $_POST["active"],
									"add_date"				=> date("Y-m-d H:i:s"),
									"lang_id"				=> $this->admin_default_lang_id																																				
								);													
				
				//insert				
				$this->users_model->add_user($values);
												
				//done message
				$_SESSION["done_message"] = $this->lang->line("done_message_add");
				
				//redirect
				//header("Location: ".current_url());
				header("Location: ".admin_url().strtolower(get_class()));
				die();																	
			}						
		}	

		$this->load->library('locations');
		$data['countries'] 	= $this->locations->get_countries();				
		//$data['judete'] 	= $this->locations->get_judete();

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_user");
		
		//send data to view
		//=========================================================					
		$data["body"] 		= "admin/users/add_user";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Edit user
	 * 
	 * @param int $user_id
	 */
	function edit_user($user_id = false)
	{					
		$data = array();	
		if($user_id == false) die(); 								
					
		//get user
		//=========================================================		
		$where 				= "AND user_id = '".$user_id."' ";
		$users 			= $this->users_model->get_users($where);
		if(!$users) die();	
		$user 			= $users[0];		
						
		//edit form
		//=========================================================	
		if(isset($_POST["Edit"]))
		{			
			//form validation
			//=========================================================		
			$this->load->library('form_validation');						
			$this->form_validation->set_rules("name",				$this->lang->line("user_name"),				"trim|required");
			$this->form_validation->set_rules("city",				$this->lang->line("user_city"),				"trim");
			$this->form_validation->set_rules("country_id",			$this->lang->line("user_country"),			"trim");
			$this->form_validation->set_rules("birthday",			$this->lang->line("user_birthday"),			"trim");
			$this->form_validation->set_rules("school",				$this->lang->line("user_school"),			"trim");
			$this->form_validation->set_rules("guide",				$this->lang->line("user_guide"),			"trim");
			$this->form_validation->set_rules("phone",				$this->lang->line("user_phone"),			"trim|numeric|min_length[10]");
			$this->form_validation->set_rules("email",				$this->lang->line("user_email"),			"trim|required|valid_email|callback_check_email[".$user_id."]");
			$this->form_validation->set_rules("active",				$this->lang->line("status"),				"trim|required");
			$this->form_validation->set_rules("inactive_reason",		" ",				"trim");											
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');			
			$form_is_valid = $this->form_validation->run();	
						
			//if form valid
			//=========================================================
			if($form_is_valid)
			{												
				//values
				$values = array(	"name" 					=> $_POST["name"],
									"city" 					=> $_POST["city"],
									"country_id" 			=> $_POST["country_id"],
									"birthday" 				=> $_POST["birthday"],
									"school" 				=> $_POST["school"],
									"guide" 				=> $_POST["guide"],
				                    "phone" 				=> $_POST["phone"],
									"email" 				=> $_POST["email"],
									"active" 				=> $_POST["active"],
									"inactive_reason" 		=> $_POST["inactive_reason"],
				                    "admin_message" 		=> $_POST["admin_message"]
								);									
				
				//update				
				$this->users_model->edit_user($values, $user_id);
				
				//done message
				$_SESSION["done_message"] = $this->lang->line("done_message_edit");
				
				//redirect
				//header("Location: ".current_url());
				header("Location: ".admin_url().strtolower(get_class())."#".$user_id);
				die();								
			}						
		}	

		$this->load->library('locations');
		$data['countries'] 	= $this->locations->get_countries();				
		//$data['judete'] 	= $this->locations->get_judete();

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_user");										
		
		//send data to view
		//=========================================================
		$data["user"] 				= $user;							
		$data["body"] 					= "admin/users/edit_user";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Delete user
	 * 
	 * @param int $user_id
	 */
	function delete_user($user_id = false, $no_redirect = false)
	{		
		if($user_id == false) die(); 
		
		//delete image, banner
		//=========================================================
		$this->delete_file("image",  $user_id, true);
		$this->delete_file("school_certificate",  $user_id, true);	
		
		//delete user
		//=========================================================
		$this->users_model->delete_user($user_id);			
				
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
	 * @param int $user_id
	 * @param string $field
	 * @param mixed $new_value
	 */
	function change_user($user_id, $field, $new_value)
	{																
		$values = array($field => $new_value);							
		$this->users_model->edit_user($values,$user_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}
	
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $user_id
	 */
	function upload_file($type, $user_id)
	{		
		$data = array();	
		if($user_id == false) die();

		//get user
		//=========================================================		
		$where 			= "AND user_id = '".$user_id."' ";
		$users 			= $this->users_model->get_users($where);
		if(!$users) die();	
		$user 			= $users[0];				
		
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
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/users/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				//$config["file_name"] 	= (isset($user["url_key"])?$user["url_key"]:"image_".$user_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $user_id, true);	
						
					$file_data = $this->upload->data();													
					
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 800;
					$config["height"] 			= 8000;
					if($file_data['image_width'] > $config['width'])
					{
						$this->load->library('image_lib');
						$this->image_lib->initialize($config);					
						$this->image_lib->resize();
					}
										
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
					$this->users_model->edit_user($values, $user_id);
										
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
			
			//upload school_certificate
			//=========================================================
			if($type  == "school_certificate") 
			{																					
				//config upload file
				$config["upload_path"] 	= $this->config->item("base_path")."uploads/users/school_certificates/";
				$config["allowed_types"]= "pdf|doc|docx|xls|xlsx|jpg|png|gif";
				$config["max_size"]		= "3072";
				$config["max_width"] 	= "10000";
				$config["max_height"] 	= "10000";
				//$config["file_name"] 	= (isset($user["url_key"])?$user["url_key"]:"image_".$user_id);
				$config["overwrite"] 	= FALSE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					//delete old file
					//=========================================================
					$this->delete_file($type, $user_id, $no_redirect = true);	
						
					$file_data = $this->upload->data();																		

					//update
					$values = array($type => $file_data["file_name"]);
					$this->users_model->edit_user($values, $user_id);	
										
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
						
			
		}//end post

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("upload_file");
		
		//send data to view
		//=========================================================
		$data["type"]	= $type;
		$data["user"] 	= $user;			
		$data["body"]  	= "admin/users/upload_file";
		$this->load->view("admin/template_iframe",$data);						
	}
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $user_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $user_id, $no_redirect = false)
	{
		if($user_id == false) die();
		
		//get user
		//=========================================================		
		$where 				= "AND user_id = '".$user_id."' ";
		$users 			= $this->users_model->get_users($where);
		if(!$users) die();	
		$user 			= $users[0];		
																
		//delete image
		//=========================================================
		if($type == "image")
		{
			//delete file
			$file_name	= $user["image"];
			$file_path 	= base_path()."uploads/users/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
				
			//delete thumb
			$file_name	= get_thumb_name($user["image"]);
			$file_path 	= base_path()."uploads/users/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);	

			//db update					
			$this->users_model->edit_user(array($type => ""), $user_id);
		}				

		//delete image
		//=========================================================
		if($type == "school_certificate")
		{
			//delete file
			$file_name	= $user["school_certificate"];
			$file_path 	= base_path()."uploads/users/school_certificates/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);
							
			//db update			
			$this->users_model->edit_user(array($type => ""), $user_id);
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
	 * Check if email exists in db
	 */
	function check_email($email, $user_id = false)
	{
		$where 		= " AND email = '".$this->db->escape_str($email)."' ";
		if($user_id)
			$where 	.= " AND user_id != '".$user_id."' ";
		
		$users 	= $this->users_model->get_users($where);
		if(!$users)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email', $this->lang->line('user_check_email'));
			return false;
		}		
	}
	
	/**
	 * Change password
	 */
	function change_password($user_id = false)
	{	
		$data = array();	
		if($user_id == false) die(); 								
					
		//get user
		//=========================================================		
		$where 			= "AND user_id = '".$user_id."' ";
		$users 			= $this->users_model->get_users($where);
		if(!$users) die();	
		$user 			= $users[0];	
		
		//if isset post
		//=============================================================
		if(isset($_POST['Change']))
		{
			//form validation
			//=============================================================
			$this->load->library('form_validation');						
			$this->form_validation->set_rules('new_password',			$this->lang->line('user_change_password_new'),		'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirmed_new_password',	$this->lang->line('user_change_password_confirm'),	'trim|required|matches[new_password]');
			//$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');	
			$form_is_valid = $this->form_validation->run();									
						
			if($form_is_valid) 
			{
				//db update	
				//=============================================================			
				$values = array('password'	=> md5($_POST['new_password']));		
				$this->users_model->edit_user($values,$user_id);	

				//send email
				//=============================================================				
				$name				= ucwords(strtolower($user['name']));
				$site_name			= $this->setting->item['site_name'];					
				$email				= $user['email'];
				$password			= $_POST['new_password'];
								
				$this->load->model('email_templates_model');
				$where			 	= " AND t1.identifier = 'change_password' 
										AND t2.lang_id = ".$user["lang_id"]." ";	
				$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
				$email_template	 	= $email_template[0];
				
				$email_subject 	= $email_template['name'];
				$email_content 	= $email_template['description'];
				$email_content	= str_replace(	array('{name}', '{site_name}', '{email}', '{password}'),
												array($name, $site_name, $email, $password),
												$email_content
											  );	

				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				$this->email->to($user['email']);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();

				//done message
				//=============================================================
				$_SESSION['done_message'] = $this->lang->line("done_message_edit");

				//redirect
				//=============================================================
				header('Location: '.current_url());
				die();									
			}			
		}
		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("user_change_password");										
		
		//send data to view
		//=========================================================
		$data["user"] 				= $user;							
		$data["body"] 				= "admin/users/change_password";
		$this->load->view("admin/template",$data);		
	}
	
	function send_email($user_id = false)
	{		
		$data = array();
		$this->page_title = $this->lang->line('user_send_email');
		
		if($user_id)
		{
		    //get user
    		//=========================================================		
    		$where 			= "AND user_id = '".$user_id."' ";
    		$users 			= $this->users_model->get_users($where);
    		if($users)	
    		    $data["user"] = $users[0];	
		}
				
		//daca se face submit la forumar	
		if(isset($_POST['Send']))
		{												
			//form validation
			$this->load->library('form_validation');				
			$this->form_validation->set_rules('subject',		$this->lang->line('user_email_subject'),	'trim|required');				
			$this->form_validation->set_rules('send_to',		" ",										'trim|required');
			$this->form_validation->set_rules('type',		    " ",										'trim|required');
			$this->form_validation->set_rules('content',		$this->lang->line('user_email_content'),	'trim|required');			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();							
						
			//if form valid
			if($form_is_valid)
			{																																											
				//get users		
				//=========================================================	
				$where 				= "";
				
				if(isset($_POST["send_to"]) && $_POST["send_to"] == "all")
					$where 			.= "";	
				
				elseif(isset($_POST["send_to"]) && $_POST["send_to"] == "active")	
					$where 			.= " AND active = '1' ";
				
				elseif(isset($_POST["send_to"]) && $_POST["send_to"] == "user")	
					$where 			.= " AND user_id = '".$user_id."' ";
				
				elseif(isset($_POST["send_to"]) && $_POST["send_to"] == "active_and_not_registered_for_current_competition")	
				{
					//get current competition
					$this->load->model("competitions_model");	
					$where_ 		= " AND active = '1' 
                						AND lang_id = '".$this->admin_default_lang_id."'
                						AND on_home = '1'";						
            		$orderby_ 	    = " ORDER BY t1.competition_id DESC ";		
            		$competitions	= $this->competitions_model->get_competitions($where_,$orderby_, 1, 0);
				    
				    $where 			.= " AND active = '1' 
			                             AND user_id NOT IN (    SELECT distinct user_id 
			                                                     FROM competitions_participants
			                                                     WHERE competition_id = '".($competitions?$competitions[0]["competition_id"]:0)."'
			                                                 ) ";	
				}
				else
					$where 			.= " AND user_id = 0 ";	
				
				if(isset($_POST["type"]) && $_POST["type"] == "national")	
				    $where 			.= " AND country_id = '175' ";	
				
				if(isset($_POST["type"]) && $_POST["type"] == "international")	
				    $where 			.= " AND country_id != '175' ";	    
								
				$users 	= $this->users_model->get_users($where);
				
				$email_subject = $_POST["subject"];
				$email_content = $_POST["content"]; 
				
				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//send email
				//===========================================================
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				
				$error_no 		= 0;
				$error_message 	= "";
				
				$done_no		= 0;
				$done_message  	= "";
				
				foreach($users as $key=>$user)
				{																			
					$this->email->clear(true);			
					$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
					$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);
					$this->email->subject($email_subject);
					$this->email->message($email_content);
					$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
					$this->email->to($user['email']);																															
											
					if($this->email->send())
					{					
						$done_no++;
						$done_message .= "<div class=\"done\">S-a trimis cu success la ".$user['email']."</div>";
					}													
					else
					{
						$error_no++;
						$error_message .= "<div class=\"error\">Din motive tehnice nu s-a trimis la ".$user['email']."</div>";
					}
					
					sleep(9);
				}
				
				
			}			   
		}
		
		if(isset($error_message) && $error_message)
		{
			$data["error_message"] = "<div>Nu s-au trimis ".$error_no." email-uri</div>".$error_message;
		}
		if(isset($done_message) && $done_message)
		{
			$data["done_message"] = "<div>S-au trimis ".$done_no." email-uri</div>".$done_message;
		}
    		
		//send data to view
		$data['body'] 			= 'admin/users/send_email';
		$this->load->view('admin/template_iframe',$data);		
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
