<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Competition
{			
	var $lang_id;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();
		
		if(isset($this->ci->default_lang_id))
			$this->lang_id = $this->ci->default_lang_id;
		else if(isset($this->ci->admin_default_lang_id))
			$this->lang_id = $this->ci->admin_default_lang_id;	
	}
	function get_competition($competition)
	{
		$competition["is_open_to_register"] = $this->is_open_to_register($competition);
		$competition["is_open_to_submit"] 	= $this->is_open_to_submit($competition);		
		$competition["is_open_soon"] 		= $this->is_open_soon($competition);
		
		/* $competition["status"]                = "open";
		$competition["is_open_to_register"]   = true;
		$competition["is_open_to_submit"]     = true;		
		$competition["is_open_soon"]          = false;  */ 
		
		
		//count only who send project
		//=========================================================	
		/*
		$and = "";
		
		if($competition["status"] == "close")
			$and = " AND project_filename != '' ";
			
		if($competition["status"] == "open" && strtotime(date("Y-m-d")) > strtotime($competition["end_registration_date"]))
			$and = " AND project_filename != '' ";	
		*/	
		$and = " ";
				
		//get participants_number
		//=========================================================					
		$where 			= " AND competition_id = '".$competition['competition_id']."' ".$and." ";																				
		$participants 	= $this->ci->competitions_model->get_participants($where,false,false,false,"count(*) as nr");
		$competition["participants_number"] = $competition["default_count_participants"];
		if(isset($participants[0]["nr"]))
			$competition["participants_number"] += $participants[0]["nr"];
			
		//get countries_number
		//=========================================================					
		$where 			= " AND competition_id = '".$competition['competition_id']."' ".$and." ";																				
		$participants 	= $this->ci->competitions_model->get_participants($where,false,false,false,"count(distinct u.country_id) as nr");
		$competition["countries_number"] = $competition["default_count_countries"];
		if(isset($participants[0]["nr"]))
			$competition["countries_number"] += $participants[0]["nr"];

		//get schools_number
		//=========================================================					
		$where 			= " AND competition_id = '".$competition['competition_id']."' ".$and." ";																				
		$participants 	= $this->ci->competitions_model->get_participants($where,false,false,false,"count(distinct CONCAT(u.school, u.guide, u.city)) as nr");
		$competition["schools_number"] = $competition["default_count_schools"];
		if(isset($participants[0]["nr"]))
			$competition["schools_number"] += $participants[0]["nr"];	

		//get categories 
		//=========================================================	
		$where 								= " AND lang_id = ".$this->lang_id." 
												AND t1.category_id IN (SELECT category_id FROM competitions2categories WHERE competition_id = ".$competition['competition_id'].")
												AND active = '1'";
		$orderby 							= "ORDER BY `order` asc";				
		$categories 						= $this->ci->categories_model->get_categories($where,$orderby,false,false,false);		
		$competition["categories"] 			= $categories;
		
		//get age_categories 
		//=========================================================	
		$where 								= " AND lang_id = ".$this->lang_id." 
												AND t1.age_category_id IN (SELECT age_category_id FROM competitions2age_categories WHERE competition_id = ".$competition['competition_id'].")
												AND active = '1'";
		$orderby 							= "ORDER BY `order` asc";				
		$age_categories 					= $this->ci->age_categories_model->get_age_categories($where,$orderby,false,false,false);		
		$competition["age_categories"] 		= $age_categories;
		
		//get prizes
		$where 					= " AND competition_id = ".$competition['competition_id']."
									AND lang_id = '".$this->lang_id."' 
									AND active = '1' ";
		$orderby 				= "ORDER BY type ASC, `order` ASC";
		$prizes 				= $this->ci->competitions_model->get_prizes($where,$orderby,false,false,false);
		$competition['prizes']	= $prizes;
		
		//get results		
		//=========================================================	
		/* 
		$where 			= " AND competition_id = '".$competition['competition_id']."' 
							AND diploma != ''
							";	 
        */
		$where 			= " AND competition_id = '".$competition['competition_id']."' ";	
		
		$orderby 		= "ORDER BY cat.order ASC, 
									age_cat.order ASC,
									p.note DESC,
									p.competitions_participant_id ASC
									";	
				
		$participants 	= $this->ci->competitions_model->get_participants($where,$orderby,false,false,false);
		foreach($participants as $key=>$participant)		
		{
			//get prize
			//=========================================================		
			$where 			= " AND lang_id = '".$this->lang_id."'
								AND t1.prize_id = '".$participant["prize_id"]."'  ";
			$orderby 		= "ORDER BY `order` ASC";
			$prizes = $this->ci->competitions_model->get_prizes($where,$orderby,false,false,false);
			if($prizes)
				$participants[$key]["prize"] = $prizes[0];	
		}
		$competition['results']	= $participants;		
		
		return $competition;
	} 
	
	private function is_open_to_register($competition)
	{		
		if(	strtotime(date("Y-m-d")) >= strtotime($competition["start_registration_date"]) &&
			strtotime(date("Y-m-d")) <= strtotime($competition["end_registration_date"]) &&
			$competition["status"] == "open"
		)
			return true;
		return false;	
	}
	
	private function is_open_to_submit($competition)
	{		
		if(	strtotime(date("Y-m-d")) >= strtotime($competition["start_registration_date"]) &&
			strtotime(date("Y-m-d")) <= strtotime($competition["end_submit_project_date"]) &&
			$competition["status"] == "open"
		)
			return true;
		return false;	
	}
	
	private function is_open_soon($competition)
	{		
		if(	strtotime(date("Y-m-d")) < strtotime($competition["start_registration_date"]) && 
			$competition["status"] == "open"
		)
			return true;
		return false;					
	}
	
}	
