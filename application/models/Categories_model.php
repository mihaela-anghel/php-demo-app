<?php
class Categories_model extends CI_Model 
{
	function __construct()
	{
		  parent::__construct();	 
	}
	function get_categories($where = false, $orderby = false, $limit = false, $offset = false, $fields = false)
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
					FROM categories as t1
					LEFT JOIN categories_details as t2 ON t1.category_id = t2.category_id
					WHERE 1 ".$where."
					".$orderby." ".$limit."
				 ";									
		$result = $this->db->query($query);
		return  $result->result_array();
		
		//$str = $this->db->last_query();	
		//print '<pre>';print_r($str);print '</pre>';			
	}	
	function get_categories_by($field, $value, $active=false)
	{		
		// selecteaza din tabela categories inregistari
		if($active != false) $where = array('active' => $active, $field => $value);
		else $where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('categories');						
		$query = $this->db->get();						
		return  $query->result_array();
	}	
	function get_categories_details_by($field, $value)
	{		
		// selecteaza din tabela categories_details  inregistari
		$where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('categories_details');						
		$query = $this->db->get();						
		return  $query->result_array();
	}
	function add_category($values = false, $details = false)
	{
		if($values !== false)		
		{
			$this->db->insert('categories',$values);
			$category_id = $this->db->insert_id();
			$this->db->where(array('category_id' => $category_id));
			$this->db->update('categories',array('order'=>$category_id));			
		}					
		if($details && $details !== false)
			$this->add_edit_details($category_id, $details);			
	}
	function edit_category($category_id, $values = false, $details = false)
	{
		if($values !== false)
		{
			$this->db->where(array('category_id' => $category_id));
			$this->db->update('categories',$values);
		}
		if($details && $details !== false)
			$this->add_edit_details($category_id, $details);		
	}
	function add_edit_details($category_id, $details)
	{
		// pt fiecare limba
		foreach($details['lang_id'] as $lang_id)
		{
			$query=$this->db->query("SELECT * FROM categories_details WHERE category_id=".$category_id." AND lang_id = ".$lang_id." "); 
			$exista=$query->row_array();
			
			// pt fiecare camp
			$values = array();
			$values['category_id'] = $category_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];			
			//end

			//concatenez id-ul la url_key	
			$values['url_key'] = $values['url_key'].'-cat'.$category_id;	
			
			if(!$exista)
			{					
				$this->db->insert('categories_details',$values);						
			}
			else 
			{
				$where = array('category_id' => $category_id, 'lang_id' => $lang_id);
				$this->db->where($where);
				$this->db->update('categories_details',$values);
			}							
		}
	}
	function delete_category($category_id)
	{
		// construiesc un array $categories_ids care contine id_ul categoriei care trebuie stearsa si toate id-urile tuturor subcategoriilor care trebuiesc sterse la randul lor
		
		//adaug id-ul categoriei in array
		$categories_ids = array($category_id);
		
		//get all categories
		$where = " AND lang_id = ".$this->admin_default_lang_id." ";
		$fields = "t1.category_id, t1.parent_id";	
		$categories = $this->categories_model->get_categories($where,false,false,false,$fields);		
		
		//make tree from $categories
		$this->load->library('tree');
		$this->tree->id_field_name		  	= 	"category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$categories;		
		$categories = $this->tree->create_tree($category_id);
		
		//adauga in array toti copii categoriei		 
		foreach($categories as $category)		
		array_push($categories_ids, $category['category_id']);

		// pt fiecare id de categorie sterg tot
		foreach($categories_ids as $category_id)
		{
			// delete from categories
			$this->db->where('category_id',$category_id);
			$this->db->delete('categories');
			
			// delete from categories details
			$this->db->where('category_id',$category_id);
			$this->db->delete('categories_details');
			
			// delete 
			$this->db->where('category_id',$category_id);
			$this->db->delete('competitions2categories');

			// delete 
			$this->db->where('category_id',$category_id);
			$this->db->delete('competitions_participants');
			
			//$this->db->where('category_id',$category_id);
			//$this->db->update('competitions_participants', array('category_id'=>'0'));
		}				
	}		
}	
