<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Global_admin 
{		
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance(); 		
		$this->ci->load->model("sections_model");

		$this->ci->load->library("setting");
	}	
	
	/**
	 * Get admin menu items
	 * 
	 * @return array
	 */
	function get_menu_sections()
	{						
		//called from header view on menu items listing
		//=========================================================		
		if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
		{
			$where 		= " AND t2.lang_id = '".$this->ci->admin_default_lang_id."' AND menu = '1'";		
			$groupby	= " GROUP BY t2.admin_section_id ";
			$orderby	= " ORDER BY `order` ASC ";
		}
		else
		{
			$where 		= " AND t1.admin_section_id IN (	SELECT 	admin_section_id 
															FROM 	admins_roles_sections_rights
															WHERE 	admin_role_id = ".$_SESSION['admin_auth']['admin_role_id']." 
														)
							AND t2.lang_id = '".$this->ci->admin_default_lang_id."'						
							AND active = '1'						
							AND menu = '1'
							";			
			$groupby	= false;
			$orderby	= " ORDER BY `order` ASC ";
		}
		
		$sections = $this->ci->sections_model->get_sections($where, $orderby, false, false, false, $groupby);					
		return	$sections;
	}	
	
	/**
	 * Get name of section
	 * 
	 * @param string $section_url	 
	 * @return string
	 */
	function get_section_name($section_url)
	{		
		$sections = $this->ci->sections_model->get_sections(" AND lang_id = '".$this->ci->admin_default_lang_id."' AND admin_section_url = '".$section_url."' ");		
		if($sections)
			$return = $sections[0]["admin_section_name"];
		else
			$return = "";
		return $return;		
	}
	
	/**
	 * Get name of right
	 *  
	 * @param string $right_url	 
	 * @return string
	 */
	function get_right_name($right_url)
	{		
		$rights = $this->ci->sections_model->get_rights(" AND lang_id = '".$this->ci->admin_default_lang_id."' AND admin_right_url = '".$right_url."' ");
		if($rights)
			$return = $rights[0]["admin_right_name"];
		else
			$return = "";	
		return $return;		
	}
	
	
	/**
	 * Check access to section or right
	 * 
	 * @param string $type can be "section" or "right"
	 * @param string $url
	 * @return bool
	 */
	function has_access($type, $url)
	{								
		if(!isset($_SESSION["admin_auth"]))
		{
			return true;
			die();
		}
		
		if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
			return true;
		else 
		{						
			if($type == "section")
			{											
				if($url == "home")
				{
					return true;				
				}	
				else
				{						
					$this->ci->db->select();
					$this->ci->db->from("admins_roles_sections_rights");
					$this->ci->db->join("admins_sections","admins_sections.admin_section_id = admins_roles_sections_rights.admin_section_id","left");
					$this->ci->db->where("admins_sections.admin_section_url",$url);
					$this->ci->db->where("admins_roles_sections_rights.admin_role_id",$_SESSION["admin_auth"]["admin_role_id"]);
					$query = $this->ci->db->get();
					$rows = $query->result_array();					
					$nr = count($rows);			
							
					if($nr > 0) 
						return true;
					else 
						return false;	
				}								
			}
			else if($type == "right")
			{								
				$url = explode("/",$url);				
				$url_controller = $url[0];
				$url_function 	= "index";
				if(isset($url[1]))
					$url_function 	= $url[1];

				//get admin_section_id
				$admin_section_id = 0;
				$query 		= " SELECT admin_section_id 									
								FROM admins_sections
								WHERE admin_section_url= '".$url_controller."'";		
							
				$results = $this->ci->db->query($query);				
				$results = $results->result_array();
				if($results)
					$admin_section_id = $results[0]["admin_section_id"];	
					
				//get admin_right_id
				$admin_right_id = 0;
				$query 		= " SELECT admin_right_id 									
								FROM admins_rights
								WHERE admin_right_url= '".$url_controller."'";		
							
				$results = $this->ci->db->query($query);				
				$results = $results->result_array();
				if($results)
					$admin_right_id = $results[0]["admin_right_id"];	

					
				//check if has access to all section
				$query 		= " SELECT admin_section_id, admin_right_id 
								FROM admins_roles_sections_rights
								WHERE admin_section_id = '".$admin_section_id."'
								AND admin_right_id = '0'
								AND	admin_role_id = '".$_SESSION["admin_auth"]["admin_role_id"]."' ";	
							
				$results = $this->ci->db->query($query);				
				$has_all_access  = $results->result_array();

				if($has_all_access)
					return true;
				else
				{					
					//check if exists rights defined but not associated to role
					$query 		= " SELECT admin_right_id 									
									FROM admins_rights
									LEFT JOIN admins_sections ON admins_rights.admin_section_id = admins_sections.admin_section_id
									WHERE admin_right_url = '".$url_function."' 
									AND admin_section_url= '".$url_controller."'
									AND admin_right_id NOT IN 	(	SELECT admin_right_id 
																	FROM admins_roles_sections_rights 
																	WHERE admin_role_id = ".$_SESSION['admin_auth']['admin_role_id']."
																	)																																																					
									";		
								
					$results = $this->ci->db->query($query);				
					$results = $results->result_array();
					
					if($results)
						return false;
					else	
						return true;
				}												
			}
			//end right
		}				 	
	}
	
	/**
	 * Get admin language
	 * 
	 * @return string
	 */
	function get_admin_default_language()
	{		
		$query = $this->ci->db->query(" SELECT * 
										FROM languages
										WHERE default_admin = '1'													 												 													 	
									 ");
		$lang = $query->row_array();		
		if($lang)	
			return $lang;
		else
			return "";			
	}	
	
	/**
	 * Show sort arrows and set the current sort direction session
	 * 
	 * @param string $sort_fields
	 * @param string $default_sort_field
	 * @param string $default_sort_dir
	 * @param string $label
	 * @return string
	 */
	function set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $label)
	{		
		foreach($sort_fields as $sort_field)
		{											
			if(isset($_SESSION[$label]["sort_field"]))
			{
				if($_SESSION[$label]["sort_field"] == $sort_field) 
					$dir = $_SESSION[$label]["sort_order"];
				else 
					$dir = "asc";					
			}
			else
			{
				if($default_sort_field == $sort_field) 
					$dir = $default_sort_dir;
				else 
					$dir = "asc";				
			}
									
			if($dir == "asc")	{	$src = "up.gif"; 	$new_dir = "desc";	}
			if($dir == "desc")	{	$src = "down.gif";	$new_dir = "asc";	}
			
			//set selected sort field and direction with diffrent color arraw
			if(isset($_SESSION[$label]["sort_field"]) && $_SESSION[$label]["sort_field"] == $sort_field || (!isset($_SESSION[$label]["sort_field"]) && $default_sort_field == $sort_field )  )			
			{
				if($dir == "asc")		
					$src = "up_on.gif"; 		
				if($dir == "desc")		
					$src = "down_on.gif"; 	
			}	
			
			//if we have a function such as a home controller index takes its name from variable controllers section 
			$array = explode("-",$label);
			$controller_name = $array[0];
		
			$sort_label[$sort_field] = '<a href = "'.admin_url().$controller_name.'/set_session/'.$label.'/sort/'.str_replace(".","___",$sort_field).'-'.$new_dir.'">
										<img src = "'.base_url().'images/admin/arrows/'.$src.'" alt="" border = "0"/>
										</a>';
		}
		return $sort_label;
	}	
	
	/**
	 * Show per page select options
	 * 
	 * @param string $section
	 * @param int $default_value
	 * @return string
	 */
	function show_per_page_select($section,$default_value)
	{
		$default_values = array(1,5,10,15,20,30,50,100);		
		$values = explode(',',$this->ci->setting->item('admin_number_per_page'));			
		if(!is_array($values)) 
			$values = $default_values;
				
		//if we have a function such as a home controller index takes its name from variable controllers section
		$array = explode("-",$section);
		$controller_name = $array[0];
		
		$select = '<span style = "padding-left:20px;">&nbsp;</span>';		
		$select .= "<select onchange=\"window.location='".admin_url().$controller_name."/set_session/".$section."/per_page/'+this.value\" style=\"min-width:50px; width:auto;\">";
				
		foreach($values as $value)
		{
			if(isset($_SESSION[$section]['per_page']) && $_SESSION[$section]["per_page"] == $value) 
				$selected = 'selected="selected"';
			elseif($default_value == $value)
				$selected = 'selected="selected"';
			else
				$selected = ''; 
			$select .= '<option value = "'.$value.'" '.$selected.'>'.$value.' / pag</option>';			
		}	
		$select .= "</select>";
		return $select;
	}
}	
