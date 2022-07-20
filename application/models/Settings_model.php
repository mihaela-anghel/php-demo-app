<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Settings_model extends CI_Model 
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
	function get_settings($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM settings as t1
						LEFT JOIN settings_details as t2 ON t1.setting_id = t2.setting_id				
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
	function get_just_settings($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query	 = " 	SELECT ".$fields." 
						FROM settings 
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
	function get_just_settings_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						
		$query = "  SELECT ".$fields." 
					FROM settings_details 
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
	function add_setting($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("settings",$values);
			$setting_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("setting_id" => $setting_id));
			$this->db->update("settings",array("order"=>$setting_id));

			//insert details
			if($details !== false)
				$this->add_edit_setting_details($details,$setting_id);
		}

		if(isset($setting_id))
			return $setting_id;
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$setting_id
	 */
	function edit_setting($values = false, $details = false, $setting_id = false)
	{
		if($values !== false && $setting_id !== false)
		{
			$this->db->where(array("setting_id" => $setting_id));
			$this->db->update('settings',$values);
		}
		if($details !== false && $setting_id !== false)
			$this->add_edit_setting_details($details, $setting_id);		
	}
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$setting_id
	 */
	private function add_edit_setting_details($details, $setting_id)
	{
		//foreach lang
		foreach($details["lang_id"] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM settings_details 
											WHERE setting_id = ".$setting_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			 = array();
			$values["setting_id"]= $setting_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						
			
			if(!$exista)
			{					
				$this->db->insert("settings_details",$values);						
			}
			else 
			{
				$where = array("setting_id" => $setting_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("settings_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $setting_id
	 */
	function delete_setting($setting_id)
	{
		//delete setting
		$this->db->where("setting_id",$setting_id);
		$this->db->delete("settings");
		
		//delete setting_details
		$this->db->where("setting_id",$setting_id);
		$this->db->delete("settings_details");		
	}			
}	
