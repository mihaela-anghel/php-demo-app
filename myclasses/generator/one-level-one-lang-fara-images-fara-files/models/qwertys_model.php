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
	 * Insert data
	 * 
	 * @param array $values	 
	 */
	function add_qwerty($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("qwertys",$values);
			$qwerty_id = $this->db->insert_id();
						
			//update order
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",array("order"=>$qwerty_id));
			
			//update url_key
			if(isset($values['url_key']))
			{	
				$url_key = $values['url_key'].'-'.substr("qwerty",0,1).$qwerty_id;

				$this->db->where(array("qwerty_id" => $qwerty_id));
				$this->db->update("qwertys",array("url_key" => $url_key));
			}			
		}
		if(isset($qwerty_id))
			return $qwerty_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$qwerty_id
	 */
	function edit_qwerty($values = false, $qwerty_id = false)
	{
		if($values !== false && $qwerty_id !== false)
		{
			if(isset($values['url_key']))			
				$values['url_key'] = $values['url_key'].'-'.substr("qwerty",0,1).$qwerty_id;
				
			$this->db->where(array("qwerty_id" => $qwerty_id));
			$this->db->update("qwertys",$values);
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
	}
}	