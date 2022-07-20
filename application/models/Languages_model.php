<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Languages_model extends CI_Model 
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
	function get_languages($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM languages						
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
	function add_language($values)
	{
		if(isset($values["code"]))
			$values["code"] = strtolower($values["code"]);
		$this->db->insert("languages",$values);
		$lang_id = $this->db->insert_id();
		
		//update order
		$this->db->where(array("lang_id" => $lang_id));
		$this->db->update("languages",array("order"=>$lang_id));
		
		return $lang_id;			
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int $lang_id
	 */
	function edit_language($values, $lang_id)
	{
		if(isset($values["code"]))
			$values["code"] = strtolower($values["code"]);
			
		$this->db->where(array("lang_id" => $lang_id));
		$this->db->update("languages",$values);							
	}
	
	/**
	 * Delete data
	 * 
	 * @param int $lang_id
	 */
	function delete_language($lang_id)
	{
		$this->db->where("lang_id",$lang_id);
		$this->db->delete("languages");		
	}		
}	