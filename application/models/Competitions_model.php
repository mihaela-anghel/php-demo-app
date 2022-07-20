<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Competitions_model extends CI_Model 
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
	function get_competitions($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
					FROM competitions as t1
					LEFT JOIN competitions_details as t2 ON t1.competition_id = t2.competition_id
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
	function get_just_competitions($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM competitions 
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
	function get_just_competitions_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM competitions_details 
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
	function add_competition($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("competitions",$values);
			$competition_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("competition_id" => $competition_id));
			$this->db->update("competitions",array("order"=>$competition_id));

			//insert details
			if($details !== false)
				$this->add_edit_competition_details($details,$competition_id);
		}
		if(isset($competition_id))
			return $competition_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$competition_id
	 */
	function edit_competition($values = false, $details = false, $competition_id = false)
	{
		if($values !== false && $competition_id !== false)
		{
			$this->db->where(array("competition_id" => $competition_id));
			$this->db->update("competitions",$values);
		}
		if($details !== false && $competition_id !== false)
			$this->add_edit_competition_details($details, $competition_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$competition_id
	 */
	private function add_edit_competition_details($details, $competition_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM competitions_details 
											WHERE competition_id = ".$competition_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['competition_id']	= $competition_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("competition",0,3).$competition_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("competitions_details",$values);						
			}
			else 
			{
				$where = array("competition_id" => $competition_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("competitions_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $competition_id
	 */
	function delete_competition($competition_id)
	{
		//delete competition
		$this->db->where("competition_id",$competition_id);
		$this->db->delete("competitions");
		
		//delete competition_details
		$this->db->where("competition_id",$competition_id);
		$this->db->delete("competitions_details");

		//delete
		$this->db->where("competition_id",$competition_id);
		$this->db->delete("competitions2categories");
		
		//delete
		$this->db->where("competition_id",$competition_id);
		$this->db->delete("competitions2age_categories");
		
		//delete
		$this->db->where("competition_id",$competition_id);
		$this->db->delete("competitions_participants");
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
	function get_prizes($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
					FROM competitions_prizes as t1
					LEFT JOIN competitions_prizes_details as t2 ON t1.prize_id = t2.prize_id
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
	function get_just_prizes($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM competitions_prizes 
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
	function get_just_prizes_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM competitions_prizes_details 
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
	function add_prize($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("competitions_prizes",$values);
			$prize_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("prize_id" => $prize_id));
			$this->db->update("competitions_prizes",array("order"=>$prize_id));

			//insert details
			if($details !== false)
				$this->add_edit_prize_details($details,$prize_id);
		}
		if(isset($prize_id))
			return $prize_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$prize_id
	 */
	function edit_prize($values = false, $details = false, $prize_id = false)
	{
		if($values !== false && $prize_id !== false)
		{
			$this->db->where(array("prize_id" => $prize_id));
			$this->db->update("competitions_prizes",$values);
		}
		if($details !== false && $prize_id !== false)
			$this->add_edit_prize_details($details, $prize_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$prize_id
	 */
	private function add_edit_prize_details($details, $prize_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM competitions_prizes_details 
											WHERE prize_id = ".$prize_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['prize_id']	= $prize_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("prize",0,3).$prize_id;
			
			if(!$exista)
			{	
				$this->db->insert("competitions_prizes_details",$values);						
			}
			else 
			{
				$where = array("prize_id" => $prize_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("competitions_prizes_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $prize_id
	 */
	function delete_prize($prize_id)
	{
		//delete prize
		$this->db->where("prize_id",$prize_id);
		$this->db->delete("competitions_prizes");
		
		//delete prize_details
		$this->db->where("prize_id",$prize_id);
		$this->db->delete("competitions_prizes_details");		
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
	function get_participants($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
			$fields = " p.*, 
						u.name, u.email, u.country_id, u.city, u.region, u.school, u.guide, countries.country_name, u.birthday, u.phone,
						catd.category_name,
						age_catd.age_category_name
						";

		if($groupby == false)	
			$groupby = "";	

		//get lang_id	
		$ci =& get_instance();		
		$lang_id = 0;	
		if(isset($ci->default_lang_id))
			$lang_id = $ci->default_lang_id;
		else if(isset($ci->admin_default_lang_id))
			$lang_id = $ci->admin_default_lang_id;		
		
		//query	
		$query = "  SELECT ".$fields." 
					FROM competitions_participants as p
					
					LEFT JOIN users as u ON p.user_id = u.user_id
					
					LEFT JOIN countries  ON u.country_id = countries.country_id
					
					LEFT JOIN categories  as cat ON p.category_id = cat.category_id 
					LEFT JOIN categories_details  as catd ON (p.category_id = catd.category_id AND catd.lang_id = '".$lang_id."')
					
					LEFT JOIN age_categories  as age_cat ON p.age_category_id = age_cat.age_category_id
					LEFT JOIN age_categories_details as age_catd ON (p.age_category_id = age_catd.age_category_id AND age_catd.lang_id = '".$lang_id."')					
					
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}
	
	/**
	 * Insert participant
	 * 
	 * @param array $values	 
	 */
	function add_participant($values = false)
	{
		if($values)		
		{
			//insert
			$this->db->insert("competitions_participants",$values);
			$participant_id = $this->db->insert_id();
			
			if(isset($values["registration_date"]))
			{
				//update project_number
				$project_number = strtotime($values["registration_date"]).$participant_id; 				
				$this->db->where(array("competitions_participant_id" => $participant_id));
				$this->db->update("competitions_participants",array("project_number" => $project_number));
			}
			
			return $participant_id;
		}									
	}
	
	/**
	 * Update participant
	 * 
	 * @param array $values
	 * @param int 	$participant_id
	 */
	function edit_participant($values = false, $participant_id = false)
	{
		if($values !== false && $participant_id !== false)
		{
			$this->db->where(array("competitions_participant_id" => $participant_id));
			$this->db->update("competitions_participants",$values);
		}		
	}
	
	/**
	 * Delete data
	 * 
	 * @param int $participant_id
	 */
	function delete_participant($competitions_participant_id)
	{
		//delete participant
		$this->db->where("competitions_participant_id",$competitions_participant_id);
		$this->db->delete("competitions_participants");				
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
	function get_winners($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
			$fields = "   u.image, u.name, countries.country_name,
		                  cd.name as competition,
		                  prd.prize_name as prize
						";

		if($groupby == false)	
			$groupby = "";	

		//get lang_id	
		$ci =& get_instance();		
		$lang_id = 0;	
		if(isset($ci->default_lang_id))
			$lang_id = $ci->default_lang_id;
		else if(isset($ci->admin_default_lang_id))
			$lang_id = $ci->admin_default_lang_id;		
						
		$query = "  SELECT ".$fields."
    		                 
		            FROM competitions as c 
					LEFT JOIN competitions_details as cd ON (c.competition_id = cd.competition_id AND cd.lang_id = '".$lang_id."')
					
					LEFT JOIN competitions_participants as p ON (p.competition_id = c.competition_id AND p.on_home = '1' AND p.diploma != '' AND p.prize_id > 0)    
		            
					LEFT JOIN competitions_prizes as pr ON (p.prize_id = pr.prize_id)
					LEFT JOIN competitions_prizes_details as prd ON (pr.prize_id = prd.prize_id AND pr.type = 'prize' AND prd.lang_id = '".$lang_id."')  
					
					LEFT JOIN categories  as cat ON p.category_id = cat.category_id 
					LEFT JOIN categories_details  as catd ON (p.category_id = catd.category_id AND catd.lang_id = '".$lang_id."')
					
					LEFT JOIN age_categories  as age_cat ON p.age_category_id = age_cat.age_category_id
					LEFT JOIN age_categories_details as age_catd ON (p.age_category_id = age_catd.age_category_id AND age_catd.lang_id = '".$lang_id."')					
										
					LEFT JOIN users as u ON p.user_id = u.user_id
					
					LEFT JOIN countries  ON u.country_id = countries.country_id
														    					 
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}
}	