<?php
class galleries_categories_model extends CI_Model 
{
	function __construct()
	{
		  parent::__construct();	 
	}
	function get_galleries_categories($where = false, $orderby = false, $limit = false, $offset = false, $fields = false)
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
					FROM galleries_categories as t1
					LEFT JOIN galleries_categories_details as t2 ON t1.galleries_category_id = t2.galleries_category_id
					WHERE 1 ".$where."
					".$orderby." ".$limit."
				 ";									
		$result = $this->db->query($query);
		return  $result->result_array();
		
		//$str = $this->db->last_query();	
		//print '<pre>';print_r($str);print '</pre>';			
	}	
	function get_galleries_categories_by($field, $value, $active=false)
	{		
		// selecteaza din tabela galleries_categories inregistari
		if($active != false) $where = array('active' => $active, $field => $value);
		else $where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('galleries_categories');						
		$query = $this->db->get();						
		return  $query->result_array();
	}	
	function get_galleries_categories_details_by($field, $value)
	{		
		// selecteaza din tabela galleries_categories_details  inregistari
		$where = array($field => $value);	
		
		$this->db->select('*');		
		$this->db->where($where);
		$this->db->from('galleries_categories_details');						
		$query = $this->db->get();						
		return  $query->result_array();
	}
	function add_galleries_category($values = false, $details = false)
	{
		if($values !== false)		
		{
			$this->db->insert('galleries_categories',$values);
			$galleries_category_id = $this->db->insert_id();
			$this->db->where(array('galleries_category_id' => $galleries_category_id));
			$this->db->update('galleries_categories',array('order'=>$galleries_category_id));			
		}					
		if($details && $details !== false)
			$this->add_edit_details($galleries_category_id, $details);			
	}
	function edit_galleries_category($galleries_category_id, $values = false, $details = false)
	{
		if($values !== false)
		{
			$this->db->where(array('galleries_category_id' => $galleries_category_id));
			$this->db->update('galleries_categories',$values);
		}
		if($details && $details !== false)
			$this->add_edit_details($galleries_category_id, $details);		
	}
	function add_edit_details($galleries_category_id, $details)
	{
		// pt fiecare limba
		foreach($details['lang_id'] as $lang_id)
		{
			$query=$this->db->query("SELECT * FROM galleries_categories_details WHERE galleries_category_id=".$galleries_category_id." AND lang_id = ".$lang_id." "); 
			$exista=$query->row_array();
			
			// pt fiecare camp
			$values = array();
			$values['galleries_category_id'] = $galleries_category_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];			
			//end

			//concatenez id-ul la url_key	
			$values['url_key'] = $values['url_key'].'-gc'.$galleries_category_id;	
			
			if(!$exista)
			{					
				$this->db->insert('galleries_categories_details',$values);						
			}
			else 
			{
				$where = array('galleries_category_id' => $galleries_category_id, 'lang_id' => $lang_id);
				$this->db->where($where);
				$this->db->update('galleries_categories_details',$values);
			}							
		}
	}
	function delete_galleries_category($galleries_category_id)
	{
		// construiesc un array $galleries_categories_ids care contine id_ul categoriei care trebuie stearsa si toate id-urile tuturor subcategoriilor care trebuiesc sterse la randul lor
		
		//adaug id-ul categoriei in array
		$galleries_categories_ids = array($galleries_category_id);
		
		//get all galleries_categories
		$where = " AND lang_id = ".$this->admin_default_lang_id." ";
		$fields = "t1.galleries_category_id, t1.parent_id";	
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,false,false,false,$fields);		
		
		//make tree from $galleries_categories
		$this->load->library('tree');
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;		
		$galleries_categories = $this->tree->create_tree($galleries_category_id);
		
		//adauga in array toti copii categoriei		 
		foreach($galleries_categories as $galleries_category)		
		array_push($galleries_categories_ids, $galleries_category['galleries_category_id']);

		// pt fiecare id de categorie sterg tot
		foreach($galleries_categories_ids as $galleries_category_id)
		{
			// delete from galleries_categories
			$this->db->where('galleries_category_id',$galleries_category_id);
			$this->db->delete('galleries_categories');
			
			// delete from galleries_categories details
			$this->db->where('galleries_category_id',$galleries_category_id);
			$this->db->delete('galleries_categories_details');
			
			$this->db->where('galleries_category_id',$galleries_category_id);
			$this->db->update('galleries', array('galleries_category_id'=>'0'));
		}				
	}		
}	
