<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class email_templates_model extends CI_Model 
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
	function get_email_templates($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
					FROM email_templates as t1
					LEFT JOIN email_templates_details as t2 ON t1.email_template_id = t2.email_template_id
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
	function get_just_email_templates($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM email_templates 
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
	function get_just_email_templates_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM email_templates_details 
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
	function add_email_template($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("email_templates",$values);
			$email_template_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("email_template_id" => $email_template_id));
			$this->db->update("email_templates",array("order"=>$email_template_id));

			//insert details
			if($details !== false)
				$this->add_edit_email_template_details($details,$email_template_id);
		}
		if(isset($email_template_id))
			return $email_template_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$email_template_id
	 */
	function edit_email_template($values = false, $details = false, $email_template_id = false)
	{
		if($values !== false && $email_template_id !== false)
		{
			$this->db->where(array("email_template_id" => $email_template_id));
			$this->db->update("email_templates",$values);
		}
		if($details !== false && $email_template_id !== false)
			$this->add_edit_email_template_details($details, $email_template_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$email_template_id
	 */
	private function add_edit_email_template_details($details, $email_template_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM email_templates_details 
											WHERE email_template_id = ".$email_template_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['email_template_id']	= $email_template_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("email_template",0,1).$email_template_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("email_templates_details",$values);						
			}
			else 
			{
				$where = array("email_template_id" => $email_template_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("email_templates_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $email_template_id
	 */
	function delete_email_template($email_template_id)
	{
		//delete email_template
		$this->db->where("email_template_id",$email_template_id);
		$this->db->delete("email_templates");
		
		//delete email_template_details
		$this->db->where("email_template_id",$email_template_id);
		$this->db->delete("email_templates_details");		
	}
}	