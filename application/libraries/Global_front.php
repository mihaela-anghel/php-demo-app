<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Global_front
{				
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance(); 		
		$this->ci->load->model('languages_model');						
	}		
		
	/**
	 * Get site language
	 * 
	 * @return false or array
	 */
	function get_default_language()
	{
		//preluam primul segment al URI
		$first_uri_segment = $this->ci->uri->segment(1);
		
		//get all active languages	
		//======================================================
		$where	 			= "AND active_site = '1' ";
		$orderby			= "ORDER BY `order` ASC";
		$active_languages 	= $this->ci->languages_model->get_languages($where, $orderby);
				
		//set default_language from $active_languages
		//======================================================
		foreach($active_languages as $active_language)
		{		
			if($active_language['default_site'] == '1')
			{
				$default_language = $active_language;
				break;
			}
		}
				
		//check if lang code from uri exists and is active language
		//======================================================
		foreach($active_languages as $active_language)
		{		
			if($first_uri_segment == $active_language['code'])
			{
				$default_language = $active_language;
				break;
			}
		}			
				
		//return default language
		//======================================================
		if(isset($default_language))
			return $default_language;
		else
			return false;				
	}
		
	/**
	 * Get globals variables 
	 * Common data in all pages, such as menu, etc.
	 * 
	 * @return array
	 */
	function get_global_variables()
	{
		$data = array();
		$this->ci->load->library("setting");
		
		//get pages
		//======================================================									
		$this->ci->load->model('pages_model');
		$where 		= " AND lang_id = ".$this->ci->default_lang_id." 
						AND active = '1' ";
		$orderby	= " ORDER BY `order` ASC ";
		$fields 	= false;
		$pages 		= $this->ci->pages_model->get_pages($where,$orderby,false,false,$fields);
		foreach($pages as $key=>$page)	
		{	
			if($page["section"] == "home")
				$pages[$key]["url"] = base_url().$this->ci->default_lang_url;
			else
				$pages[$key]["url"] = base_url().$this->ci->default_lang_url.($page["section"]?$page["section"]:$page["url_key"]);
		}	
		
		$menu_pages = array();
		foreach($pages as $key=>$page)
			if($page["on_menu"] == "1")
				$menu_pages[] = $page;
				
		//create tree
		$this->ci->load->library('tree');		
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $pages;
		$pages 							= $tree->create_tree(0);
		$data['global_pages'] 			= $pages;				
		
		//create tree
		$this->ci->load->library('tree');		
		$tree							= new Tree();
		$tree->id_field_name		  	= "page_id";
		$tree->parent_id_field_name 	= "parent_id";
		$tree->input_array 				= $menu_pages;
		$menu_pages 					= $tree->create_tree(0);
		$data['menu_pages'] 			= $menu_pages;
		//$data['menu_tree']  			= $this->ci->tree->print_tree($pages);

		//get current competition
		//======================================================
		$this->ci->load->model('competitions_model');
		$this->ci->load->model('categories_model');
		$this->ci->load->model('age_categories_model');
		$this->ci->load->library('competition');	
		$where 		= " AND active = '1' 
						AND lang_id = '".$this->ci->default_lang_id."'
						AND on_home = '1'";						
		$orderby 	= " ORDER BY t1.competition_id DESC ";		
		$competitions	= $this->ci->competitions_model->get_competitions($where,$orderby, 1, 0);
		if($competitions)
		{		
			$competition_obj 				= new Competition();			
			$competition 					= $competition_obj->get_competition($competitions[0]);
			unset($competition_obj);
			
			$current_competition 			= $competition;
			$data['current_competition'] 	= $current_competition;
			
		}	
		
		//get comming soon competition
		//======================================================
		$where 		= " AND active = '1' 
						AND lang_id = '".$this->ci->default_lang_id."'
						AND on_home = '0'
						AND status = 'open'
						AND on_comming_soon = '1'        
						";						
		$orderby 	= " ORDER BY t1.start_registration_date ASC ";		
		$competitions	= $this->ci->competitions_model->get_competitions($where,$orderby);
		foreach($competitions as $key=>$competition)
		{
			$competition_obj 				= new Competition();			
			$competitions[$key]				= $competition_obj->get_competition($competition);
			unset($competition_obj);						
		}	
		$data['next_competitions'] 	= $competitions;
		
				
		//get last 4 closed competitions
		//======================================================
		if($this->ci->setting->item["show_results_on_right"] == "yes")
		{
    		$where 			= " AND active = '1' 
    							AND lang_id = '".$this->ci->default_lang_id."'
    							AND status = 'close'
    							";
    		if(isset($current_competition))
    			$where .= " AND t1.competition_id != '".$current_competition["competition_id"]."' ";
    									
    		$orderby 		= " ORDER BY t1.end_registration_date DESC ";		
    		$competitions	= $this->ci->competitions_model->get_competitions($where,$orderby, $this->ci->setting->item["nr_right_archive"], 0);				
    		foreach($competitions as $key=>$competition)
    		{
    			$competition_obj 				= new Competition();			
    			$competitions[$key]				= $competition_obj->get_competition($competition);
    			unset($competition_obj);						
    		}	
    		$data['right_competitions'] 	= $competitions;
		}
		
		//get all active languages
		//======================================================
		$where	 					= "AND active_site = '1'";
		$orderby					= "ORDER BY `order` ASC";
		$fields 					= false;
		$data['global_languages']	= $this->ci->languages_model->get_languages($where, $orderby, false, false, $fields);		
		
		if($this->ci->uri->rsegment(1) == "home")
		{
			//get banners
			//======================================================
			$this->ci->load->model('banners_model');
			$where 					= " AND active = '1' 
										AND position IN ('slider')";
			$orderby				= " ORDER BY `order` ASC ";
			$fields 				= false;		
			$data['global_banners'] = $this->ci->banners_model->get_banners($where, $orderby, false, false, $fields);										
		}
		
		//get banners
		//======================================================
		$this->ci->load->model('banners_model');
		$where 					= " AND active = '1' 
									AND position IN ('right')";
		$orderby				= " ORDER BY `order` ASC ";
		$fields 				= false;		
		$data['right_banners'] = $this->ci->banners_model->get_banners($where, $orderby, false, false, $fields);
	
		//get partners
		//======================================================
		$this->ci->load->model('partners_model');
		$where 							= " AND active = '1' ";
		$orderby						= " ORDER BY `order` ASC ";
		$fields 						= false;		
		$data['global_partners']   = $this->ci->partners_model->get_partners($where, $orderby, false, false, $fields);	
		
		//get partners link
		//======================================================
		$this->ci->load->model('partner_links_model');
		$where 							= " AND active = '1' ";
		$orderby						= " ORDER BY `order` ASC ";
		$fields 						= false;		
		$data['global_partner_links']   = $this->ci->partner_links_model->get_partner_links($where, $orderby, false, false, $fields);								
		
		//get users number
		//======================================================
		if($this->ci->setting->item["show_users_number"] == "yes")
		{
    		$this->ci->load->model('users_model');
    		$where 	      = false;
    		$results      = $this->ci->users_model->get_users($where, false, false, false, "count(*) as nr");								
    		$data['users_number'] = (isset($results[0]["nr"])?$results[0]["nr"]:0);
    		
    		$where 	      = false;
    		$results      = $this->ci->users_model->get_users($where, false, false, false, "count(distinct country_id) as nr");								
    		$data['countries_number'] = (isset($results[0]["nr"])?$results[0]["nr"]:0);
    		
    		$query = "  SELECT c.country_name
                		FROM users as u                                             
                		LEFT JOIN countries as c ON u.country_id = c.country_id                                           
                		GROUP BY u.country_id ORDER BY c.country_name ";                                
            $results = $this->ci->db->query($query);
            $results = $results->result_array();            
    		$data['registered_countries'] = $results;
		}
		
		//get articles	
		//============================================================
		$this->ci->load->model('articles_model');
		$where = "  AND active = '1' 
		            AND on_slider = '1'
					AND lang_id = '".$this->ci->default_lang_id."' ";		
		$orderby = "ORDER BY published_date DESC, `order` DESC";
		$articles			    = $this->ci->articles_model->get_articles($where,$orderby);
		$articles = array_merge($articles,$articles,$articles,$articles,$articles,$articles,$articles);
		$data['slider_articles']= $articles;	
		
		return $data;
	}

	/**
	 * Show sort arrows and set the current sort direction session
	 * 
	 * @param string $sort_fields
	 * @param string $default_sort_field
	 * @param string $default_sort_dir
	 * @param string $label
	 * @return string
	 */
	function set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $label)
	{		
		foreach($sort_fields as $sort_field)
		{											
			if(isset($_SESSION[$label]["sort_field"]))
			{
				if($_SESSION[$label]["sort_field"] == $sort_field) 
					$dir = $_SESSION[$label]["sort_order"];
				else 
					$dir = "asc";					
			}
			else
			{
				if($default_sort_field == $sort_field) 
					$dir = $default_sort_dir;
				else 
					$dir = "asc";				
			}
									
			if($dir == "asc")	{	$src = "up.gif"; 	$new_dir = "desc";	}
			if($dir == "desc")	{	$src = "down.gif";	$new_dir = "asc";	}
			
			//set selected sort field and direction with diffrent color arraw
			if(isset($_SESSION[$label]["sort_field"]) && $_SESSION[$label]["sort_field"] == $sort_field || (!isset($_SESSION[$label]["sort_field"]) && $default_sort_field == $sort_field )  )			
			{
				if($dir == "asc")		
					$src = "up_on.gif"; 		
				if($dir == "desc")		
					$src = "down_on.gif"; 	
			}	
			
			//if we have a function such as a home controller index takes its name from variable controllers section 
			$array = explode("-",$label);
			$controller_name = $array[0];
		
			$sort_label[$sort_field] = '<a href = "'.base_url().$this->ci->default_lang_url.$controller_name.'/set_session/'.$label.'/sort/'.str_replace(".","___",$sort_field).'-'.$new_dir.'">
										<img src = "'.base_url().'images/admin/arrows/'.$src.'" alt="" border = "0"/>
										</a>';
		}
		return $sort_label;
	}	
	
	/**
	 * Show per page select options
	 * 
	 * @param string $section
	 * @param int $default_value
	 * @return string
	 */
	function show_per_page_select($section,$default_value)
	{
		$default_values = array(1,5,10,15,20,30,50,100);		
		$values = explode(',',$this->ci->setting->item('admin_number_per_page'));			
		if(!is_array($values)) 
			$values = $default_values;
				
		//if we have a function such as a home controller index takes its name from variable controllers section
		$array = explode("-",$section);
		$controller_name = $array[0];
		
		$select = '<span style = "padding-left:20px;">&nbsp;</span>';		
		$select .= "<select onchange=\"window.location='".base_url().$this->ci->default_lang_url.$controller_name."/set_session/".$section."/per_page/'+this.value\" style=\"min-width:50px; width:auto; height:22px;\">";
				
		foreach($values as $value)
		{
			if(isset($_SESSION[$section]['per_page']) && $_SESSION[$section]["per_page"] == $value) 
				$selected = 'selected="selected"';
			elseif($default_value == $value)
				$selected = 'selected="selected"';
			else
				$selected = ''; 
			$select .= '<option value = "'.$value.'" '.$selected.'>'.$value.' / page</option>';			
		}	
		$select .= "</select>";
		return $select;
	}
}	
