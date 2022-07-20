<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Partners_model extends CI_Model 
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
	function get_partners($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM partners 
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
	function add_partner($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("partners",$values);
			$partner_id = $this->db->insert_id();
						
			//update order
			$this->db->where(array("partner_id" => $partner_id));
			$this->db->update("partners",array("order"=>$partner_id));
			
			//update url_key
			if(isset($values['url_key']))
			{	
				$url_key = $values['url_key'].'-'.substr("partner",0,3).$partner_id;

				$this->db->where(array("partner_id" => $partner_id));
				$this->db->update("partners",array("url_key" => $url_key));
			}			
		}
		if(isset($partner_id))
			return $partner_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$partner_id
	 */
	function edit_partner($values = false, $partner_id = false)
	{
		if($values !== false && $partner_id !== false)
		{
			if(isset($values['url_key']))			
				$values['url_key'] = $values['url_key'].'-'.substr("partner",0,3).$partner_id;
				
			$this->db->where(array("partner_id" => $partner_id));
			$this->db->update("partners",$values);
		}			
	}	
		
	/**
	 * Delete data
	 * 
	 * @param int $partner_id
	 */
	function delete_partner($partner_id)
	{
		//delete partner
		$this->db->where("partner_id",$partner_id);
		$this->db->delete("partners");					
	}
}	