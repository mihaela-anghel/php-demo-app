<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Arbiters_model extends CI_Model 
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
	function get_arbiters($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM arbiters 
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
	function add_arbiter($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("arbiters",$values);
			$arbiter_id = $this->db->insert_id();
						
			//update order
			$this->db->where(array("arbiter_id" => $arbiter_id));
			$this->db->update("arbiters",array("order"=>$arbiter_id));
			
			//update url_key
			if(isset($values['url_key']))
			{	
				$url_key = $values['url_key'].'-'.substr("arbiter",0,3).$arbiter_id;

				$this->db->where(array("arbiter_id" => $arbiter_id));
				$this->db->update("arbiters",array("url_key" => $url_key));
			}			
		}
		if(isset($arbiter_id))
			return $arbiter_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$arbiter_id
	 */
	function edit_arbiter($values = false, $arbiter_id = false)
	{
		if($values !== false && $arbiter_id !== false)
		{
			if(isset($values['url_key']))			
				$values['url_key'] = $values['url_key'].'-'.substr("arbiter",0,3).$arbiter_id;
				
			$this->db->where(array("arbiter_id" => $arbiter_id));
			$this->db->update("arbiters",$values);
		}			
	}	
		
	/**
	 * Delete data
	 * 
	 * @param int $arbiter_id
	 */
	function delete_arbiter($arbiter_id)
	{
		//delete arbiter
		$this->db->where("arbiter_id",$arbiter_id);
		$this->db->delete("arbiters");					
	}
}	