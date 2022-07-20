<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Admins_model extends CI_Model 
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		  parent::__construct();	 
	}
	
	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_admins($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";	
						
		$query 	 = "  	SELECT ".$fields." 
						FROM admins						
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
											
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;		
	}
		
	/**
	 * Insert data
	 * 
	 * @param array $values
	 * @return int
	 */
	function add_admin($values)
	{
		$this->db->insert("admins",$values);
		$admin_id = $this->db->insert_id();
		
		return $admin_id;	
	}
	
	/**
	 * Update data
	 * 
	 * @param array $values
	 * @param int $admin_id
	 */
	function edit_admin($values,$admin_id)
	{		
		$this->db->where("admin_id",$admin_id);
		$this->db->update("admins",$values);		
	}
	
	/**
	 * Delete data
	 * 
	 * @param int $admin_id
	 */
	function delete_admin($admin_id)
	{
		$this->db->where("admin_id",$admin_id);
		$this->db->delete("admins");		
	}		

	/**
	 * Get role
	 * 
	 * @param int $admin_id
	 */
	function get_role($admin_id)
	{
		$query 	 = "  	SELECT admin_role  
						FROM admins	LEFT JOIN admins_roles ON admins.admin_role_id = admins_roles.admin_role_id 					
						WHERE admins.admin_id = '".$admin_id."'";
											
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		if(isset($results[0]['admin_role']))	
			return $results[0]['admin_role'];
		else
			return "";								
	}	
	
	/**
	 * Select data from db
	 * 
	 * @param  string 	$where	 
	 * @return array
	 */
	function get_roles($where = false)
	{
		if($where == false)		
			$where = "";
			
		$query 	 = "  	SELECT *  
						FROM admins_roles 					
						WHERE 1 ".$where." ORDER BY admin_role ASC";											
		$results = $this->db->query($query);
		$results = $results->result_array();
				
		return $results;				
	}	
	
	/**
	 * Insert data
	 * 
	 * @param array $values
	 * @return int
	 */
	function add_role($values)
	{
		$this->db->insert("admins_roles",$values);
		$role_id =  $this->db->insert_id();
		return $role_id;		
	}
	
	/**
	 * Update data
	 * 
	 * @param array $values
	 * @param int 	$admin_role_id
	 */
	function edit_role($values,$admin_role_id)
	{		
		$this->db->where("admin_role_id",$admin_role_id);
		$this->db->update("admins_roles",$values);		
	}
	
	/**
	 * Delete data
	 * 
	 * @param int $role_id
	 */
	function delete_role($role_id)
	{
		//delete role
		$this->db->where("admin_role_id",$role_id);
		$this->db->delete("admins_roles");

		//delete admins
		$this->db->where("admin_role_id",$role_id);
		$this->db->delete("admins");
	}	
	
	/**
	 * Select data from db
	 * 
	 * @param int $role_id
	 * @return unkarraynown
	 */
	function get_permissions($role_id)
	{		
		$query 	 = "  	SELECT * 
						FROM admins_roles_sections_rights 					
						WHERE admin_role_id = '".$role_id."'";											
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;
	}
		
	/**
	 * Insert data
	 * 
	 * @param array $values
	 */
	function add_permission($values)
	{			
		$this->db->insert("admins_roles_sections_rights",$values);			
	}
	
	/**
	 * Delete data
	 * 
	 * @param int $role_id
	 */
	function delete_permissions($role_id)
	{
		$this->db->where("admin_role_id",$role_id);
		$this->db->delete("admins_roles_sections_rights");		
	}
}	
