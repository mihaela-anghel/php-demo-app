<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Qwertys_model extends CI_Model 
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
	function get_qwertys($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
			$fields = "t1.*, t2.*";

		if($groupby == false)	
			$groupby = "";												
		
		$query = "  SELECT ".$fields." 
					FROM qwertys as t1
					LEFT JOIN qwertys_details as t2 ON t1.qwerty_id = t2.qwerty_id
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
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
	function get_just_qwertys($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM qwertys 
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
	function get_just_qwertys_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM qwertys_details 
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
	function add_qwerty($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("qwertys",$values);
			$qwerty_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",array("order"=>$qwerty_id));

			//insert details
			if($details !== false)
				$this->add_edit_qwerty_details($details,$qwerty_id);
		}
		if(isset($qwerty_id))
			return $qwerty_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$qwerty_id
	 */
	function edit_qwerty($values = false, $details = false, $qwerty_id = false)
	{
		if($values !== false && $qwerty_id !== false)
		{
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",$values);
		}
		if($details !== false && $qwerty_id !== false)
			$this->add_edit_qwerty_details($details, $qwerty_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$qwerty_id
	 */
	private function add_edit_qwerty_details($details, $qwerty_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM qwertys_details 
											WHERE qwerty_id = ".$qwerty_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['qwerty_id']	= $qwerty_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("qwerty",0,1).$qwerty_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("qwertys_details",$values);						
			}
			else 
			{
				$where = array("qwerty_id" => $qwerty_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("qwertys_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $qwerty_id
	 */
	function delete_qwerty($qwerty_id)
	{
		//delete qwerty
		$this->db->where("qwerty_id",$qwerty_id);
		$this->db->delete("qwertys");
		
		//delete qwerty_details
		$this->db->where("qwerty_id",$qwerty_id);
		$this->db->delete("qwertys_details");		
	}
}	