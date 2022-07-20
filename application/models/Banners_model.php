<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Banners_model extends CI_Model 
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
	function get_banners($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM banners 
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
	function add_banner($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("banners",$values);
			$banner_id = $this->db->insert_id();
						
			//update order
			$this->db->where(array("banner_id" => $banner_id));
			$this->db->update("banners",array("order"=>$banner_id));
			
			//update url_key
			if(isset($values['url_key']))
			{	
				$url_key = $values['url_key'].'-'.substr("banner",0,1).$banner_id;

				$this->db->where(array("banner_id" => $banner_id));
				$this->db->update("banners",array("url_key" => $url_key));
			}			
		}
		if(isset($banner_id))
			return $banner_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$banner_id
	 */
	function edit_banner($values = false, $banner_id = false)
	{
		if($values !== false && $banner_id !== false)
		{
			if(isset($values['url_key']))			
				$values['url_key'] = $values['url_key'].'-'.substr("banner",0,1).$banner_id;
				
			$this->db->where(array("banner_id" => $banner_id));
			$this->db->update("banners",$values);
		}			
	}	
		
	/**
	 * Delete data
	 * 
	 * @param int $banner_id
	 */
	function delete_banner($banner_id)
	{
		//delete banner
		$this->db->where("banner_id",$banner_id);
		$this->db->delete("banners");					
	}
}	