<?php
class Age_categories_model extends CI_Model 
{
	function __construct()
	{
		  parent::__construct();	 
	}
	function get_age_categories($where = false, $orderby = false, $limit = false, $offset = false, $fields = false)
	{								
		if($fields == false)	
			$fields = "t1.*, t2.*";
		if($where == false)		
			$where = "";
		if($orderby == false)	
			$orderby = "";
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;
		else 	
			$limit = "";	
		
		$query = "  SELECT ".$fields." 
					FROM age_categories as t1
					LEFT JOIN age_categories_details as t2 ON t1.age_category_id = t2.age_category_id
					WHERE 1 ".$where."
					".$orderby." ".$limit."
				 ";									
		$result = $this->db->query($query);
		return  $result->result_array();
		
		//$str = $this->db->last_query();	
		//print '<pre>';print_r($str);print '</pre>';			
	}	
	function get_age_categories_by($field, $value, $active=false)
	{		
		// selecteaza din tabela age_categories inregistari
		if($active != false) $where = array('active' => $active, $field => $value);
		else $where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('age_categories');						
		$query = $this->db->get();						
		return  $query->result_array();
	}	
	function get_age_categories_details_by($field, $value)
	{		
		// selecteaza din tabela age_categories_details  inregistari
		$where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('age_categories_details');						
		$query = $this->db->get();						
		return  $query->result_array();
	}
	function add_age_category($values = false, $details = false)
	{
		if($values !== false)		
		{
			$this->db->insert('age_categories',$values);
			$age_category_id = $this->db->insert_id();
			$this->db->where(array('age_category_id' => $age_category_id));
			$this->db->update('age_categories',array('order'=>$age_category_id));			
		}					
		if($details && $details !== false)
			$this->add_edit_details($age_category_id, $details);			
	}
	function edit_age_category($age_category_id, $values = false, $details = false)
	{
		if($values !== false)
		{
			$this->db->where(array('age_category_id' => $age_category_id));
			$this->db->update('age_categories',$values);
		}
		if($details && $details !== false)
			$this->add_edit_details($age_category_id, $details);		
	}
	function add_edit_details($age_category_id, $details)
	{
		// pt fiecare limba
		foreach($details['lang_id'] as $lang_id)
		{
			$query=$this->db->query("SELECT * FROM age_categories_details WHERE age_category_id=".$age_category_id." AND lang_id = ".$lang_id." "); 
			$exista=$query->row_array();
			
			// pt fiecare camp
			$values = array();
			$values['age_category_id'] = $age_category_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];			
			//end

			//concatenez id-ul la url_key	
			$values['url_key'] = $values['url_key'].'-acat'.$age_category_id;	
			
			if(!$exista)
			{					
				$this->db->insert('age_categories_details',$values);						
			}
			else 
			{
				$where = array('age_category_id' => $age_category_id, 'lang_id' => $lang_id);
				$this->db->where($where);
				$this->db->update('age_categories_details',$values);
			}							
		}
	}
	function delete_age_category($age_category_id)
	{
		// construiesc un array $age_categories_ids care contine id_ul categoriei care trebuie stearsa si toate id-urile tuturor subcategoriilor care trebuiesc sterse la randul lor
		
		//adaug id-ul categoriei in array
		$age_categories_ids = array($age_category_id);
		
		//get all age_categories
		$where = " AND lang_id = ".$this->admin_default_lang_id." ";
		$fields = "t1.age_category_id, t1.parent_id";	
		$age_categories = $this->age_categories_model->get_age_categories($where,false,false,false,$fields);		
		
		//make tree from $age_categories
		$this->load->library('tree');
		$this->tree->id_field_name		  	= 	"age_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$age_categories;		
		$age_categories = $this->tree->create_tree($age_category_id);
		
		//adauga in array toti copii categoriei		 
		foreach($age_categories as $age_category)		
		array_push($age_categories_ids, $age_category['age_category_id']);

		// pt fiecare id de categorie sterg tot
		foreach($age_categories_ids as $age_category_id)
		{
			// delete from age_categories
			$this->db->where('age_category_id',$age_category_id);
			$this->db->delete('age_categories');
			
			// delete from age_categories details
			$this->db->where('age_category_id',$age_category_id);
			$this->db->delete('age_categories_details');
			
			// delete
			$this->db->where('age_category_id',$age_category_id);
			$this->db->delete('competitions2age_categories');
			
			// delete 
			$this->db->where('age_category_id',$age_category_id);
			$this->db->delete('competitions_participants');
									
			//$this->db->where('age_category_id',$age_category_id);
			//$this->db->update('competitions_participants', array('age_category_id'=>'0'));
		}				
	}		
}	
