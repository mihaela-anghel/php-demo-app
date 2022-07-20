<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Tree
{		
	var $id_field_name;					//is the name of field which is primary key in db table
	var $parent_id_field_name;			//is the name of field which is parent id in db table
	var $input_array 	= array();		//is an input array which is unordered and must be the tree source
	var $output_array 	= array();		//output array recursively created
		
	/**
	 * Constrctor
	 */
	function __construct()
	{
		$this->ci =& get_instance();		
	}

	/**
	 * Create tree
	 * 
	 * @param 	int $parent_id
	 * @return 	array
	 */
	public function create_tree($parent_id)
	{																						
		$inputarray = $this->input_array;

		$i=0;	
		foreach( $inputarray as $key=>$value )
		{
			if( $value[$this->parent_id_field_name] == $parent_id )
			{					
				$child 						= $inputarray[$key]; 
				$child['level']			 	= $this->get_level( $child[$this->id_field_name] );							
				$child['parents_number'] 	= $child['level'];				
				
				$i++;
				array_push($this->output_array,$child);	
				unset($inputarray[$key]);		
				$this->create_tree($child[$this->id_field_name], $inputarray);								
			}			
		}

		foreach( $this->output_array as $key=>$value )
			if( isset($value[$this->id_field_name]) && $value[$this->id_field_name] == $parent_id )
				$this->output_array[$key]["childs_number"] = $i;
				
		return $this->output_array;						
	}
	
	/**
	 * Get all childrens
	 * 
	 * @param int $category_id
	 * @return array
	 */
	function get_children($category_id)
	{
		$childs = array();
		foreach( $this->input_array as $key=>$value )
		{
			if( $value[$this->parent_id_field_name] == $category_id )
			{					
				$child 					 = $this->input_array[$key]; 
				$child['level']			 = $this->get_level( $child[$this->id_field_name]);
				$child['childs_number']	 = count($this->get_children( $child[$this->id_field_name]));
				$child['parents_number'] = count($this->get_parents( $child[$this->id_field_name]));		
				array_push( $childs , $child );				
			}
		}
		return $childs;			
	}
	
	/**
	 * Get parents
	 * 
	 * @param int $category_id
	 * @param array $output_array
	 * @return array
	 */
	function get_parents( $category_id, &$output_array=array())
	{				
		foreach($this->input_array as $value)			
		{						   
			if($category_id == $value[$this->id_field_name])
			{										
				$parent_id = $value[$this->parent_id_field_name];																							
				array_push($output_array, $value);	
					
				//call recursive														
				$this->get_parents( $value[$this->parent_id_field_name] , $output_array ); 				
			}			
		}					
		//remove element that we are searching its parents
		unset($output_array[0]);						
				
		//sort in reverse order
		$result_array = array_reverse($output_array);	
								
		//for each parent get the level
		foreach($result_array as $key=>$value)						
			$result_array[$key]['level']  = $key;
														
		return $result_array;										
	}
	
	
	/**
	 * Get level
	 * 
	 * @param int $category_id
	 * @return number
	 */
	function get_level($category_id)					
	{		
		return count($this->get_parents($category_id));
	}
	
	/**
	 * Get all parents hierarchy
	 * this function can be called independent
	 * get all parents hierarchy of given record from any table containing n levels parent_id field,
	 * 
	 * @param array $parametru
	 * 				$parametru  	= array(	'table_name'		=>	'pages',	
	 *											'id_field_name'		=>	'page_id',
	 *											'id_field_value'	=>	2,
	 *											'fields'			=>	't2.page_id, title, url_key',	
	 *											);
	 * 				give the table name (table_name)
	 * 				field name that contains id (id_field_name)
	 * 				ID for which you wish to find parents (id_field_value)
	 * 				name of the table fields that need to be selected (fields)
	 * 
	 * @param int 	$lang_id
	 * @param array $output
	 * @return array
	 */
	function get_all_parents($parametru = array(), $lang_id, &$output = array())
	{										
		//get parent_id
		$query = $this->ci->db->query(" SELECT parent_id
										FROM ".$parametru['table_name']." 
										WHERE ".$parametru['id_field_name']." = ".$parametru['id_field_value']."					
									 ");
		$results = $query->result_array();
		if($results)
		{		
			$result = $results[0];
			$parent_id = $result['parent_id'];				
			
			$query = $this->ci->db->query(" SELECT 		".$parametru['fields']."
											FROM 		".$parametru['table_name']." as t1
											LEFT JOIN 	".$parametru['table_name']."_details as t2 
											ON 			t1.".$parametru['id_field_name']." = t2.".$parametru['id_field_name']."
											WHERE 		t1.".$parametru['id_field_name']." = ".$parent_id." 
											AND 		lang_id = ".$lang_id."					
										  ");								
			$results = $query->result_array();
					
			if($results)
			{			
				$result = $results[0];			
				array_push($output,$result);
				$parametru['id_field_value'] = $result[$parametru['id_field_name']];
				
				//call recursive
				$this->get_all_parents($parametru, $lang_id, $output);
			}
		}	
		return array_reverse($output);				 		
	}		

	/**
	 * Get all children hierarchy
	 * this function can be called independent
	 * get all childrens hierarchy of given record from any table containing n levels parent_id field,
	 * 
	 * @param array $parametru
	 * 				$parametru  	= array(	'table_name'		=>	'pages',	
	 *											'id_field_name'		=>	'page_id',
	 *											'id_field_value'	=>	2,
	 *											'fields'			=>	't2.page_id, title, url_key',	
	 *											);
	 * 				give the table name (table_name)
	 * 				field name that contains id (id_field_name)
	 * 				ID for which you wish to find parents (id_field_value)
	 * 				name of the table fields that need to be selected (fields)
	 * 
	 * @param int 	$lang_id
	 * @param array $output
	 * @return array
	 */
	function get_all_children($parametru = array(), $lang_id, &$output = array())
	{												
		$query = $this->ci->db->query(" SELECT 		".$parametru['fields']."
										FROM 		".$parametru['table_name']." as t1
										LEFT JOIN 	".$parametru['table_name']."_details as t2 
										ON 			t1.".$parametru['id_field_name']." = t2.".$parametru['id_field_name']."
										WHERE 		t1.parent_id = ".$parametru['id_field_value']." 
										AND 		lang_id = ".$lang_id."											
									 ");
		$results = $query->result_array();

		if($results)
		{
			foreach($results as $result)
			{
				array_push($output,$result);
				$parametru['id_field_value'] = $result[$parametru['id_field_name']];
				
				//call recursive
				$this->get_all_children($parametru, $lang_id, $output);
			}
		}
		return array_reverse($output);				 		
	}
	
	/**
	 * Print TREE ul
	 * 
	 * @param array $array_tree
	 *				$array_tree = array(    "id" 	= > "ID",
	 *										"name" 	= > "Link anchor",
	 *										"url" 	= > "Link url",
	 *										"level" = > 0,
	 *										);
	 * @return string
	 */
	function print_tree($array_tree)
	{				
		$tree = '<ul class="navbar-nav nav-fill">';
		if($array_tree)
		{
			foreach($array_tree as $key => $value)
			{
				$li_attributes = "";
				foreach($value["li_attributes"] as $attr=>$val)
					$li_attributes .= " ".$attr.'="'.$val.'"';
					
				$a_attributes = "";
				foreach($value["a_attributes"] as $attr=>$val)
					$a_attributes .= " ".$attr.'="'.$val.'"';	
				
				$tree  .= '<li'.$li_attributes.'>';				
				$tree  .= '<a href="'.$value["url"].'" title="'.$value["name"].'" '.$a_attributes.'>'.$value["name"].'</a>';	
				
				if(isset($array_tree[$key+1]['level']) && $value['level']<$array_tree[$key+1]['level'])							
					$tree  .= '<ul class="dropdown-menu" aria-labelledby="menu-'.$value["page_id"].'">';			
				else 						
					$tree  .= '</li>';
					
				if(isset($array_tree[$key+1]['level']) && $value['level']>$array_tree[$key+1]['level'])
				{
					$lenght = $value['level']-$array_tree[$key+1]['level'];
					for($i=0;$i<=$lenght-1;$i++)
						$tree  .= '</ul>
					</li>';
				}
				if(!isset($array_tree[$key+1]['level']) && $value['level']>0)
				{
					$lenght = $value['level']-0;
					for($i=0;$i<=$lenght-1;$i++)
						$tree  .= '</ul>
					</li>';
				}			
			}	
		}		
		return $tree.'</ul>';					
	}				
}	