<?php
class Members_model extends CI_Model 
{
	function __construct()
	{
		  parent::__construct();	 
	}
	
	function get_members($where = false, $orderby = false, $limit = false, $offset = false, $fields = false)
	{						
		if($fields == false)	
			$fields = "*";
		if($where == false)		
			$where = "";
		if($orderby == false)	
			$orderby = "";
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;
		else 	
			$limit = "";	
		
		$query = "  SELECT ".$fields." 
					FROM members					
					WHERE 1 ".$where."
					".$orderby." ".$limit."
				 ";											
		$result = $this->db->query($query);
		return  $result->result_array();
		
		//$str = $this->db->last_query();	
		//print '<pre>';print_r($str);print '</pre>';			
	}
	
	function get_field_value($member_id,$field)
	{
		$query = "  SELECT `".$field."` 
					FROM members					
					WHERE member_id = ".$member_id." ";
											
		$result = $this->db->query($query);
		$array  = $result->result_array();
		return $array[0][$field];
	}
	
	function add_member($values)
	{
		$this->db->insert('members',$values);
		$inserted_id =  $this->db->insert_id();				
		return $inserted_id;
	}
	
	function edit_member($values,$where)
	{
		$this->db->where($where);
		$this->db->update('members',$values);		
	}
	
	function delete_member($member_id)
	{
		// delete from members
		$this->db->where('member_id',$member_id);
		$this->db->delete('members');		
	}	
				
}	