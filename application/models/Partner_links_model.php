<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Partner_links_model extends CI_Model 
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
	function get_partner_links($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM partner_links 
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
	function add_partner_link($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("partner_links",$values);
			$partner_link_id = $this->db->insert_id();
						
			//update order
			$this->db->where(array("partner_link_id" => $partner_link_id));
			$this->db->update("partner_links",array("order"=>$partner_link_id));
			
			//update url_key
			if(isset($values['url_key']))
			{	
				$url_key = $values['url_key'].'-'.substr("partner_link",0,3).$partner_link_id;

				$this->db->where(array("partner_link_id" => $partner_link_id));
				$this->db->update("partner_links",array("url_key" => $url_key));
			}			
		}
		if(isset($partner_link_id))
			return $partner_link_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$partner_link_id
	 */
	function edit_partner_link($values = false, $partner_link_id = false)
	{
		if($values !== false && $partner_link_id !== false)
		{
			if(isset($values['url_key']))			
				$values['url_key'] = $values['url_key'].'-'.substr("partner_link",0,3).$partner_link_id;
				
			$this->db->where(array("partner_link_id" => $partner_link_id));
			$this->db->update("partner_links",$values);
		}			
	}	
		
	/**
	 * Delete data
	 * 
	 * @param int $partner_link_id
	 */
	function delete_partner_link($partner_link_id)
	{
		//delete partner_link
		$this->db->where("partner_link_id",$partner_link_id);
		$this->db->delete("partner_links");					
	}
}	