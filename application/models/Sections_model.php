<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Sections_model extends CI_Model 
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
	function get_sections($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query 	= " SELECT ".$fields." 
					FROM admins_sections as t1
					LEFT JOIN admins_sections_details as t2 ON t1.admin_section_id = t2.admin_section_id				
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
											
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;		
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
	function get_just_sections($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query 	= "  	SELECT ".$fields." 
						FROM admins_sections 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";										
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;		
	}
	
	/**
	 * Select data from db
	 * 
	 * @param 	string 	$where
	 * @param 	string 	$orderby
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @param 	string 	$fields
	 * @param 	string 	$groupby
	 * @return 	array
	 */	
	function get_just_sections_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query   = "	SELECT ".$fields." 
						FROM admins_sections_details 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;	
	}
	
	/**
	 * Insert data
	 * 
	 * @param array $values
	 * @param array $details
	 */
	function add_section($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("admins_sections",$values);
			$section_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("admin_section_id" => $section_id));
			$this->db->update("admins_sections",array("order"=>$section_id));

			//insert details
			if($details !== false)
				$this->add_edit_section_details($details,$section_id);
		}
		if(isset($section_id))
			return $section_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$section_id
	 */
	function edit_section($values = false, $details = false, $section_id = false)
	{
		if($values !== false && $section_id !== false)
		{
			$this->db->where(array("admin_section_id" => $section_id));
			$this->db->update("admins_sections",$values);
		}
		if($details !== false && $section_id !== false)
			$this->add_edit_section_details($details, $section_id);		
	}
		
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$section_id
	 */
	private function add_edit_section_details($details, $section_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM admins_sections_details 
											WHERE admin_section_id = ".$section_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			 = array();
			$values['admin_section_id']= $section_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			//$values['url_key'] = $values['url_key'].'-'.$section_id;
			
			if(!$exista)
			{	
				$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("admins_sections_details",$values);						
			}
			else 
			{
				$where = array("admin_section_id" => $section_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("admins_sections_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $section_id
	 */
	function delete_section($section_id)
	{
		//delete section
		$this->db->where("admin_section_id",$section_id);
		$this->db->delete("admins_sections");
		
		//delete section_details
		$this->db->where("admin_section_id",$section_id);
		$this->db->delete("admins_sections_details");

		//delete from admins_roles_sections_rights
		$this->db->where("admin_section_id",$section_id);
		$this->db->delete("admins_roles_sections_rights");
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
	function get_rights($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query 	= "  	SELECT ".$fields." 
						FROM admins_rights as t1
						LEFT JOIN admins_rights_details as t2 ON t1.admin_right_id = t2.admin_right_id				
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;			
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
	function get_just_rights($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query 	= "  	SELECT ".$fields." 
						FROM admins_rights 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
											
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;		
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
	function get_just_rights_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query 	= "  	SELECT ".$fields." 
						FROM admins_rights_details 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";										
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;			
	}
	
	/**
	 * Insert data
	 * 
	 * @param array $values
	 * @param array $details
	 */
	function add_right($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("admins_rights",$values);
			$right_id = $this->db->insert_id();
						
			//insert details
			if($details !== false)
				$this->add_edit_right_details($details,$right_id);
		}

		if(isset($right_id))
			return $right_id;	
	}
	
	/**
	 * Update data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$right_id
	 */
	function edit_right($values = false, $details = false, $right_id = false)
	{
		if($values !== false && $right_id !== false)
		{
			$this->db->where(array("admin_right_id" => $right_id));
			$this->db->update("admins_rights",$values);
		}
		if($details !== false && $right_id !== false)
			$this->add_edit_right_details($details, $right_id);		
	}
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$right_id
	 */
	private function add_edit_right_details($details, $right_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM admins_rights_details 
											WHERE admin_right_id = ".$right_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			 = array();
			$values['admin_right_id']= $right_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						
			
			if(!$exista)
			{	
				$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("admins_rights_details",$values);						
			}
			else 
			{
				$where = array("admin_right_id" => $right_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("admins_rights_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $right_id
	 */
	function delete_right($right_id)
	{
		//delete right
		$this->db->where("admin_right_id",$right_id);
		$this->db->delete("admins_rights");
		
		//delete right_details
		$this->db->where("admin_right_id",$right_id);
		$this->db->delete("admins_rights_details");

		//delete from admins_roles_sections_rights
		$this->db->where("admin_right_id",$right_id);
		$this->db->delete("admins_roles_sections_rights");
	}	
}	
