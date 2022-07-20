<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Users_model extends CI_Model 
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		  parent::__construct();
		  
		  $this->db_xkcc = $this->load->database('xkcc', TRUE);
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
	function get_users($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM users 
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
	function add_user($values = false)
	{
		if($values !== false)		
		{										
			//insert
			$this->db->insert("users",$values);
			$user_id = $this->db->insert_id();
			
			//syncronize
			$this->syncronize_user($user_id, "add");			
		}
		if(isset($user_id))
			return $user_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param int 	$user_id
	 */
	function edit_user($values = false, $user_id = false)
	{
		if($values !== false && $user_id !== false)
		{				
			$this->db->where(array("user_id" => $user_id));
			$this->db->update("users",$values);
			
			//syncronize
			$this->syncronize_user($user_id, "edit");			
		}			
	}	
		
	/**
	 * Delete data
	 * 
	 * @param int $user_id
	 */
	function delete_user($user_id)
	{				
		//syncronize
		$this->syncronize_user($user_id, "delete");
		
		//delete competitions_participants
		$this->db->where("user_id",$user_id);
		$this->db->delete("competitions_participants");	
		
		//delete user
		$this->db->where("user_id",$user_id);
		$this->db->delete("users");					
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
            $fields = " p.user_id, c.competition_id";
	                        
        if($groupby == false)
            $groupby = "";
            
        //query
        $query = "  SELECT ".$fields."
            		FROM competitions_participants as p                                            
            		LEFT JOIN competitions as c ON p.competition_id = c.competition_id                                           
            		WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
                            
        $results = $this->db->query($query);
        $results = $results->result_array();
        
        return $results;
	}
	
	function syncronize_user($user_id, $action)
	{
	    if($this->setting->item["db_syncronize"] != "yes")
	        return false;
	    
	    $lang_id = 0;
	    if(isset($this->admin_default_lang_id))
	         $lang_id = $this->admin_default_lang_id;
	    if(isset($this->default_lang_id))
	         $lang_id = $this->default_lang_id;
	           
	       
	    //get user
	    //=========================================================		
		$where 			= "AND user_id = '".$user_id."' AND country_id = 175";
		$users 			= $this->get_users($where);
		if(!$users)
		    return false;		
		$user 			= $users[0];
		
		//check if user exists in xkcc
		//=========================================================		
		$query 	      = " SELECT * FROM users WHERE email = '".$this->db_xkcc->escape_str($user["email"])."'  ";										
		$results      = $this->db_xkcc->query($query);
		$xckk_users   = $results->result_array();		
		
		if($action == "add" || $action == "edit")
		{
    		if(!$xckk_users)
    		{    		       		    
    		    $values               = $user;
    		    $values["lang_id"]    = $lang_id;    		    
    		    unset($values["user_id"]);
    		    unset($values["image"]);
    		    unset($values["school_certificate"]);
    		    
    		    //insert xckk
    		    $this->db_xkcc->insert("users",$values);    			   			    			
    		}
    		else
    		{    		        		    
    		    $xckk_user = $xckk_users[0];
    		    
    		    $values               = $user;    		        		   
    		    unset($values["user_id"]);
    		    unset($values["lang_id"]);
    		    unset($values["image"]);
    		    unset($values["school_certificate"]);
    		   
    		    //update xckk    		   
    		    $this->db_xkcc->where(array("user_id" => $xckk_user["user_id"]));
    		    $this->db_xkcc->update("users",$values);
    		}
		}
		elseif($action == "delete")
		{
    		if($xckk_users)
    		{
    		    $xckk_user = $xckk_users[0];
    		    
    		    //delete xckk
    		    
    		    //delete competitions_participants
        		$this->db_xkcc->where(array("user_id" => $xckk_user["user_id"]));
        		$this->db_xkcc->delete("competitions_participants");	
        		
        		//delete user
        		$this->db_xkcc->where(array("user_id" => $xckk_user["user_id"]));
        		$this->db_xkcc->delete("users");		
    		}    		
		}
	}
}	