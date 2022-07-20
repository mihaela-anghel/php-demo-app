<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Admins extends Base_controller 
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->model("admins_model");		
		$this->lang->load("admin/admins",$this->admin_default_lang);

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());			
	}
	
	/**
	 * List admins
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

		//sort
		//======================================================				 	
		$sort_fields 			= array("admin_id", "admin_username", "admin_email", "admin_name");
		$default_sort_field 	= "admin_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;				
			
		//pagination
		//======================================================						
		$rows 					= $this->admins_model->get_admins(false,$orderby,false,false,"count(admin_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= admin_url()."admins/index";
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
		$admins = $this->admins_model->get_admins($where,$orderby,$config["per_page"],$offset);
		foreach($admins as $key=>$admin)
		{
			$admins[$key]["role"] = $this->admins_model->get_role($admin["admin_id"]);
		}		
		
		//number of results to display
		//====================================================== 
		$display_from 		=	(count($admins)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($admins);
		$display_total 		= 	$total_rows;		
		$results_displayed  = 	$this->lang->line("results")." ".$display_from." - ".$display_to." ".$this->lang->line('from')." ".$display_total;		
				
		//send data to view
		//======================================================
		$data["sort_label"] 		= $sort_label; 
		$data["per_page_select"]	= $this->global_admin->show_per_page_select($section_name,$config["per_page"]);			
		$data["results_displayed"] 	= $results_displayed;	
		$data["pagination"]			= $pagination;		
		$data["admins"] 			= $admins;			
		$data["body"] 				= "admin/admins/list_admins"; 
		$this->load->view("admin/template",$data);	
	}
	
	/**
	 * Add admin
	 */
	function add_admin()
	{
		$data = array();
		
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_username",	$this->lang->line("admin_username"),	"trim|required|min_length[4]");
			$this->form_validation->set_rules("admin_password",	$this->lang->line("admin_password"),	"trim|required|min_length[4]");
			$this->form_validation->set_rules("admin_password2",$this->lang->line("admin_password2"),	"trim|required|matches[admin_password]");			
			$this->form_validation->set_rules("admin_role_id",	$this->lang->line("admin_role"),		"trim|required");
			$this->form_validation->set_rules("admin_email",	$this->lang->line("admin_email"),		"trim|required|valid_email");			
			$this->form_validation->set_rules("admin_name",		$this->lang->line("admin_name"),		"trim|required");
			$this->form_validation->set_rules("admin_phone",	$this->lang->line("admin_phone"),		"trim");					
			$this->form_validation->set_rules("active",			$this->lang->line("status"),			"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{
				$where_exist= " AND LOWER(admin_username) = '".strtolower($this->db->escape_str($_POST["admin_username"]))."' ";					
				$exist 		= $this->admins_model->get_admins($where_exist);
										
				if($exist)
					$data["error_message"] = $this->lang->line("admin_exist");
				else
				{
					//values
					$values = array("admin_role_id"		 => $_POST["admin_role_id"],
									"admin_username"	 => $_POST["admin_username"],
									"admin_password"	 => md5($_POST["admin_password"]),
									"admin_name"		 => $_POST["admin_name"],
									"admin_email"		 => $_POST["admin_email"],	
									"admin_phone"  		 => $_POST["admin_phone"],
									"active"			 => $_POST["active"]
									);
					//insert
					$this->admins_model->add_admin($values);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_add");

					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}	
			}//end form valid
		}//end form
		
		//get roles
		//=========================================================
		if($_SESSION["admin_auth"]["admin_role"] != "webmaster")
			$where = "AND admin_role != 'webmaster'";
		else 
			$where = false;			
		$roles = $this->admins_model->get_roles($where);
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_admin");
		
		//send data to view
		//=========================================================	
		$data["roles"] = $roles;
		$data["body"]  = "admin/admins/add_admin";
		$this->load->view("admin/template_iframe",$data);							
	}
	
	/**
	 * Edit admin
	 * 
	 * @param int $admin_id
	 */
	function edit_admin($admin_id = false)
	{
		$data = array();
		if($admin_id == false) die();				
		
		//get admin
		//=========================================================
		$admins 	= $this->admins_model->get_admins(" AND admin_id = '".$admin_id."' ");
		if(!$admins) die();
		$admin 		= $admins[0];		
			
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{						
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_username",	$this->lang->line("admin_username"),	"trim|required|min_length[4]");
			$this->form_validation->set_rules("admin_role_id",	$this->lang->line("admin_role"),		"trim|required");
			$this->form_validation->set_rules("admin_email",	$this->lang->line("admin_email"),		"trim|required|valid_email");			
			$this->form_validation->set_rules("admin_name",		$this->lang->line("admin_name"),		"trim|required");
			$this->form_validation->set_rules("admin_phone",	$this->lang->line("admin_phone"),		"trim");					
			$this->form_validation->set_rules("active",			$this->lang->line("status"),			"trim|required");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{
				$where_exist= "	AND LOWER(admin_username) = '".strtolower($this->db->escape_str($_POST["admin_username"]))."'
								AND admin_id != '".$admin_id."'
								";					
				$exist 		= $this->admins_model->get_admins($where_exist);
											
				if($exist)
					$data["error_message"] = $this->lang->line("admin_exist");
				else
				{
					//values
					$values = array("admin_role_id"		 => $_POST["admin_role_id"],
									"admin_username"	 => $_POST["admin_username"],									
									"admin_name"		 => $_POST["admin_name"],
									"admin_email"		 => $_POST["admin_email"],	
									"admin_phone"  		 => $_POST["admin_phone"],
									"active"			 => $_POST["active"]
									);
					//update				
					$where = array("admin_id" => $admin_id);				
					$this->admins_model->edit_admin($values,$admin_id);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php	
					die();				
				}	
			}//end form valid
		}//end form						
						
		//get roles
		//=========================================================
		if($_SESSION["admin_auth"]["admin_role"] != "webmaster")			
			$where = "AND admin_role != 'webmaster'";
		else 
			$where = false;			
		$roles = $this->admins_model->get_roles();		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_admin");
		
		//send data to view
		//=========================================================
		$data["admin"] = $admin;
		$data["roles"] = $roles;		
		$data["body"]  = "admin/admins/edit_admin";
		$this->load->view("admin/template_iframe",$data);
	}
	
	/**
	 * Delete admin
	 * 
	 * @param int $admin_id
	 */
	function delete_admin($admin_id = false)
	{
		if($admin_id == false) die();
		
		//delete admin
		//=========================================================
		$this->admins_model->delete_admin($admin_id);
		
		//redirect	
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);												
		</script><?php		
	}
	
	/**
	 * Change field value
	 * 
	 * @param int 		$admin_id
	 * @param string 	$field
	 * @param mixed 	$new_value
	 */
	function change_admin($admin_id, $field, $new_value)
	{																
		if($admin_id == false) die();
		
		$values = array($field => $new_value);									
		$this->admins_model->edit_admin($values,$admin_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
	
	/**
	 * Change password
	 * 
	 * @param int $admin_id
	 */
	function change_password($admin_id = false)
	{
		$data = array();
		if($admin_id == false) die();				
		
		//get admin
		//=========================================================
		$admins = $this->admins_model->get_admins(" AND admin_id = '".$admin_id."' ");
		if(!$admins) die();
		$admin = $admins[0];
		
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_password",	$this->lang->line("admin_password"),	"trim|required|min_length[4]");
			$this->form_validation->set_rules("admin_password2",$this->lang->line("admin_password2"),	"trim|required|matches[admin_password]");			
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================	
			if($form_is_valid)
			{				
				//update password
				$values = array("admin_password" => md5($_POST["admin_password"]));								
				$this->admins_model->edit_admin($values, $admin_id);
				
				//redirect
				?><script type="text/javascript" language="javascript">									
				parent.jQuery.fancybox.close();
				window.parent.location.reload();											
				</script><?php
				die();													
			}
		}				
		
		//get roles
		//=========================================================
		if($_SESSION["admin_auth"]["admin_role"] != "webmaster")
			$where = "AND admin_role != 'webmaster'";
		else 
			$where = false;			
		$roles = $this->admins_model->get_roles($where);

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("change_password");
		
		//send data to view
		//=========================================================
		$data["admin"] = $admin;
		$data["roles"] = $roles;
		$data["body"]  = "admin/admins/change_password";
		$this->load->view("admin/template_iframe",$data);		
	}
	
	/**
	 * List roles
	 */
	function list_roles()
	{			
		//get roles
		//=========================================================
		if($_SESSION["admin_auth"]["admin_role"] != "webmaster")
			$where = "AND admin_role != 'webmaster'";
		else 
			$where = false;	
		$roles = $this->admins_model->get_roles($where);					

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("roles_and_permissions");
		
		//send data to view
		//=========================================================		
		$data["roles"] 	= $roles;			
		$data["body"] 	= "admin/admins/list_roles";
		$this->load->view("admin/template",$data);
	}
	
	/**
	 * Add role
	 */
	function add_role()
	{
		$data = array();
		
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_role",$this->lang->line("admin_role"),"trim|required");						
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form is valid
			//=========================================================
			if($form_is_valid)
			{				
				$exist = $this->admins_model->get_roles("AND admin_role = '".$_POST["admin_role"]."'");
								
				if($exist)
					$data["error_message"] = $this->lang->line("role_exist");
				else
				{
					//insert
					$values = array("admin_role" => $_POST["admin_role"]);
					$this->admins_model->add_role($values);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_add");
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();					
				}	
			}//end form valid
		}//end form	

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_role");
		
		//send data to view
		//=========================================================			
		$data["body"]  = "admin/admins/add_role";
		$this->load->view("admin/template_iframe",$data);				
	}
	
	/**
	 * Edit role
	 * 
	 * @param int $role_id
	 */
	function edit_role($role_id = false)
	{
		$data = array();
		if($role_id == false) die();				
		
		//get role
		$roles = $this->admins_model->get_roles("AND admin_role_id = '".$role_id."'");
		if(!$roles) die();
		$role = $roles[0];
		
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			$this->form_validation->set_rules("admin_role",$this->lang->line("admin_role"),"trim|required");						
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();									
			
			//if form is valid
			//=========================================================
			if($form_is_valid)
			{
				$exist = $this->admins_model->get_roles("AND admin_role = '".$_POST["admin_role"]."' AND admin_role_id != '".$role_id."'");
								
				if($exist)
					$data["error_message"] = $this->lang->line("role_exist");
				else
				{
					//update					
					$values = array("admin_role" => $_POST["admin_role"]);
					$this->admins_model->edit_role($values, $role_id);
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_edit");
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php	
					die();		
				}	
			}
		}
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_role");
		
		//send data to view
		//=========================================================
		$data["role"] = $roles[0];			
		$data["body"]  = "admin/admins/edit_role";
		$this->load->view("admin/template_iframe",$data);			
	}
	
	/**
	 * Delete role
	 * 
	 * @param int $role_id
	 */
	function delete_role($role_id = false)
	{
		if($role_id == false) die();
		
		//delete role
		//=========================================================
		$this->admins_model->delete_role($role_id);
		
		//redirect
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php
	}	
	
	/**
	 * Set role permissions
	 * 
	 * @param int $role_id
	 */
function set_role_permissions($role_id = false)
	{
		if($role_id == false) die();				
		
		//get permissions
		//=========================================================
		$permissions = $this->admins_model->get_permissions($role_id);				
		
		//get sections
		//=========================================================
		$this->load->model("sections_model");		
		if($_SESSION["admin_auth"]["admin_role_id"] == 1) // for webmaster
			$where = "	AND lang_id = ".$this->admin_default_lang_id."";			
		else
			$where = "  AND lang_id = ".$this->admin_default_lang_id."
						AND t1.admin_section_id IN ( 	SELECT admin_section_id 
														FROM admins_roles_sections_rights
														WHERE admin_role_id = ".$_SESSION["admin_auth"]["admin_role_id"]."
													)													 
						AND active = '1'
						"; 									
		$sections = $this->sections_model->get_sections($where, "ORDER BY `order` asc"); 
		foreach($sections as $key=>$section)
		{
			$where_right = "AND t1.admin_section_id = ".$section["admin_section_id"]." 
							AND lang_id = ".$this->admin_default_lang_id."							 
							AND active = '1' ";
			if($_SESSION["admin_auth"]["admin_role_id"] != 1)
				$where_right .= "AND t1.admin_right_id IN ( 	SELECT admin_right_id 
																FROM admins_roles_sections_rights
																WHERE admin_role_id = ".$_SESSION["admin_auth"]["admin_role_id"]."
															) ";
			$sections[$key]["rights"] = $this->sections_model->get_rights($where_right, "ORDER BY `order` ASC");
		}
		
		//edit form		
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			//=========================================================
			$this->load->library("form_validation");
			foreach($sections as $key=>$section)
			{
				$this->form_validation->set_rules("section_".$section["admin_section_id"],$section["admin_section_name"],"");
				$where_right = "	AND t1.admin_section_id = '".$section["admin_section_id"]."' 
									AND lang_id = '".$this->admin_default_lang_id."'";
				if($_SESSION["admin_auth"]["admin_role_id"] != 1)
					$where_right .= "AND t1.admin_right_id IN ( 	SELECT admin_right_id 
																	FROM admins_roles_sections_rights
																	WHERE admin_role_id = ".$_SESSION["admin_auth"]["admin_role_id"]."
																) ";
				$sections[$key]["rights"] = $this->sections_model->get_rights($where_right, "ORDER BY `order` ASC");								
				foreach($sections[$key]["rights"] as $right)
				{					
					$this->form_validation->set_rules("right_".$right["admin_right_id"], $right["admin_right_name"], "trim");
				}
			}								
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						

			//if form valid
			//=========================================================
			if($form_is_valid)
			{				
				$this->admins_model->delete_permissions($role_id);
				foreach($sections as $key=>$section)
				{					
					if(isset($_POST["section_".$section["admin_section_id"]]) && $_POST["section_".$section["admin_section_id"]] == "1")
					{
						$values = array("admin_role_id"	 	=> $role_id, 
										"admin_section_id"  => $section["admin_section_id"], 
										"admin_right_id" 	=> 0
										);
						$this->admins_model->add_permission($values);
					}		
					$where_right = "	AND t1.admin_section_id = '".$section["admin_section_id"]."' 
										AND lang_id = '".$this->admin_default_lang_id."'";
					if($_SESSION["admin_auth"]["admin_role_id"] != 1)
						$where_right .= "AND t1.admin_right_id IN ( 	SELECT admin_right_id 
																		FROM admins_roles_sections_rights
																		WHERE admin_role_id = ".$_SESSION["admin_auth"]["admin_role_id"]."
																	) ";			
					$sections[$key]["rights"] = $this->sections_model->get_rights($where_right, "ORDER BY `order` ASC");								
					foreach($sections[$key]["rights"] as $right)
					{					
						if(		isset($_POST["right_".$right["admin_right_id"]]) 
								&& $_POST["right_".$right["admin_right_id"]] == "1" 
								&& 	(	(	isset($_POST["section_".$section["admin_section_id"]]) 
											&& $_POST["section_".$section["admin_section_id"]] != "1"
										) ||
										!isset($_POST["section_".$section["admin_section_id"]])	
									 )
						   )
						{
							$values = array("admin_role_id"	 	=> $role_id, 
											"admin_section_id"  => $section["admin_section_id"], 
											"admin_right_id" 	=> $right["admin_right_id"]
											);
							$this->admins_model->add_permission($values);
						}
					}
				}				
			}
		}
		
		//get role
		//=========================================================
		$role = $this->admins_model->get_roles("AND admin_role_id = '".$role_id."'");
		$role = $role[0];		
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("permissions")." ".$this->lang->line("for")." ".$role["admin_role"]." ";
		
		//send data to view
		//=========================================================		
		$data["role_id"] 		= $role_id;
		$data["permissions"] 	= $permissions;		
		$data["sections"] 		= $sections;
		$data["body"] 			= "admin/admins/set_role_permissions";
		$this->load->view("admin/template",$data);
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
