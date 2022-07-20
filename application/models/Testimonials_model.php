<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Testimonials_model extends CI_Model 
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
	function get_testimonials($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
					FROM testimonials as t1
					LEFT JOIN testimonials_details as t2 ON t1.testimonial_id = t2.testimonial_id
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
	function get_just_testimonials($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM testimonials 
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
	function get_just_testimonials_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM testimonials_details 
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
	function add_testimonial($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("testimonials",$values);
			$testimonial_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("testimonial_id" => $testimonial_id));
			$this->db->update("testimonials",array("order"=>$testimonial_id));

			//insert details
			if($details !== false)
				$this->add_edit_testimonial_details($details,$testimonial_id);
		}
		if(isset($testimonial_id))
			return $testimonial_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$testimonial_id
	 */
	function edit_testimonial($values = false, $details = false, $testimonial_id = false)
	{
		if($values !== false && $testimonial_id !== false)
		{
			$this->db->where(array("testimonial_id" => $testimonial_id));
			$this->db->update("testimonials",$values);
		}
		if($details !== false && $testimonial_id !== false)
			$this->add_edit_testimonial_details($details, $testimonial_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$testimonial_id
	 */
	private function add_edit_testimonial_details($details, $testimonial_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM testimonials_details 
											WHERE testimonial_id = ".$testimonial_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['testimonial_id']	= $testimonial_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("testimonial",0,1).$testimonial_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("testimonials_details",$values);						
			}
			else 
			{
				$where = array("testimonial_id" => $testimonial_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("testimonials_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $testimonial_id
	 */
	function delete_testimonial($testimonial_id)
	{
		//delete testimonial
		$this->db->where("testimonial_id",$testimonial_id);
		$this->db->delete("testimonials");
		
		//delete testimonial_details
		$this->db->where("testimonial_id",$testimonial_id);
		$this->db->delete("testimonials_details");		
	}
}	