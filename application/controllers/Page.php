<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Page extends Base_controller 
{	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();		
	}
	
	/**
	 * Main page
	 */
	public function index($url_key = false, $iframe_template=false, $download=false)
	{		
		if($url_key == false) 
			show_404();	
		$url_key = explode("-",$url_key);	
						
		//get page
		//=============================================================
		$where 	= " AND lang_id = ".$this->default_lang_id."  
					AND CONCAT('pag',t1.page_id) = '".end($url_key)."' 
					AND active = '1' 					
					";		
		$pages 	= $this->pages_model->get_pages($where);
		if(!$pages) show_404();
		$page 	= $pages[0];	
		
		if(in_array($page["page_id"], array(4,14)) && !isset($_SESSION['auth']))
		{
			$_SESSION["error_message"] = $this->lang->line("need_login_to_register_to_competition");

			$_SESSION["error_message_login_area"] = $this->lang->line("need_login_to_register_to_competition_2");
			
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}    
		
		//get page images
		//=============================================================		
		$where 			= "AND page_id = ".$page['page_id']." AND active = '1'";
		$orderby 		= "ORDER BY `order` ASC";	
		$images 		= $this->pages_model->get_images($where,$orderby,false,false,false);
		$page['images']	= $images;
		
		//get page files
		//=============================================================		
		$where 			= "AND page_id = ".$page['page_id']." AND active = '1'";
		$orderby 		= "ORDER BY `order` ASC";	
		$files 			= $this->pages_model->get_files($where,$orderby,false,false,false);
		$page['files']	= $files;
		
		//get page videos
		//=============================================================		
		$where 			= "AND page_id = ".$page['page_id']." AND active = '1'";
		$orderby 		= "ORDER BY `order` ASC";	
		$videos 		= $this->pages_model->get_videos($where,$orderby,false,false,false);
		$page['videos']	= $videos;			
		
		//get subpages
		//=============================================================
		$subpages = array();
		$where		= " AND lang_id = ".$this->default_lang_id." 
						AND active = '1' 
						AND parent_id = '".$page['page_id']."' ";
		$orderby 	= "ORDER BY `order` ASC";		
		$subpages 	= $this->pages_model->get_pages($where,$orderby,false,false,false);

		//get page parents
		//============================================================			
		$parametru 		= array(	'table_name'		=>	'pages',	
									'id_field_name'		=>	'page_id',
									'id_field_value'	=>	$page['page_id'],
									'fields'			=>	't2.page_id, name, url_key',	
									);						
		$parents = $this->tree->get_all_parents($parametru, $this->default_lang_id);			
		$page["parents_ids"] = array();
		foreach($parents as $parent)
			array_push($page["parents_ids"], $parent["page_id"]);
			
		//get page childs
		//============================================================			
		$parametru 		= array(	'table_name'		=>	'pages',	
									'id_field_name'		=>	'page_id',
									'id_field_value'	=>	$page['page_id'],
									'fields'			=>	't2.page_id, name, url_key',	
									);						
		$childs = $this->tree->get_all_children($parametru, $this->default_lang_id);			
		$page["childs_ids"] = array();
		foreach($childs as $child)
			array_push($page["childs_ids"], $child["page_id"]);	

		//galleries	
		if($page["page_id"] == 6)
		{
			//get galleries_categories			
			$this->load->model('galleries_categories_model');					
			$where 					= " AND lang_id = ".$this->default_lang_id." 
										AND active = '1'";	
			$orderby 				= "ORDER BY `order` ASC ";
			$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby);
			
			$this->load->model('galleries_model');
			foreach($galleries_categories as $key=>$galleries_category)
			{
				//get galleries
				$where 		= " AND lang_id = ".$this->default_lang_id." 
								AND active = '1'
								AND t1.galleries_category_id = '".$galleries_category["galleries_category_id"]."'
								";	
				$orderby 	= "ORDER BY `order` DESC ";
				$galleries	= $this->galleries_model->get_galleries($where,$orderby);				
				$galleries_categories[$key]["galleries"] = $galleries;
			}
			
			$data["galleries_categories"] = $galleries_categories;
		}

		//results
		if($page["page_id"] == 5)
		{			
			//arhiva
			if(!$iframe_template)
			{
				//get old competitions
				$where 			= " AND active = '1' 
									AND lang_id = '".$this->default_lang_id."'
									AND status = 'close'
									";
				//if(isset($this->current_competition))
					//$where .= " AND t1.competition_id != '".$this->current_competition["competition_id"]."' ";
											
				$orderby 		= " ORDER BY t1.end_registration_date DESC ";		
				$competitions	= $this->competitions_model->get_competitions($where,$orderby);				
				foreach($competitions as $key=>$competition)
				{
					$competition_obj 				= new Competition();			
					$competitions[$key]				= $competition_obj->get_competition($competition);
					unset($competition_obj);						
				}	
				$data['archive_competitions'] 	= $competitions;
			}
			else
			{				
				$url_key_array = explode("-",$iframe_template);	
				
				//get competition				
				$where 			= " AND active = '1' 
									AND lang_id = '".$this->default_lang_id."'
									AND status = 'close'
									AND CONCAT('com',t1.competition_id) = '".end($url_key_array)."'
									";											
				$competitions	= $this->competitions_model->get_competitions($where);	
				if($competitions)
				{			
					$competition 					= $competitions[0];
					
					$competition_obj 				= new Competition();			
					$competition					= $competition_obj->get_competition($competition);
					unset($competition_obj);

					$data['competition'] 			= $competition;
				}
				
				if($download)
				{				   					
            		$html     = $this->load->view('front/results_download',$data, true);            						           		
            		$html     = str_replace(file_url()."image/rb_100x100_auto/",base_path(),$html);            		            		            		
            		//echo $html; die();
            		
        			//genereaza pdf		
        			require($this->config->item('base_path')."myclasses/dompdf-0.8.3/autoload.inc.php");
        			
        			$dompdf = new Dompdf\Dompdf();		
        	        $dompdf->load_html($html);
        	        $dompdf->set_paper('a4', 'portrait');
        	        $dompdf->set_option( 'dpi' , '72' );   
        	        $dompdf->render();        			
        			$dompdf->stream("results.pdf", array("Attachment" => true));		
        			die();		 				   
				}
			}
		}
		
				
		//navigation
		//===========================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang_url
							  );
							  
		$this->load->library('tree');					  
		$parametru 	= array(	'table_name'		=>	'pages',	
								'id_field_name'		=>	'page_id',
								'id_field_value'	=>	$page['page_id'],
								'fields'			=>	't2.page_id, section, name, url_key',	
								);
		$parents = $this->tree->get_all_parents($parametru, $this->default_lang_id);		
		foreach($parents as $parent)
		{
			if($parent['section'] != 'home')
			{
				$item['name'] 	=	$parent['name'];
				$item['url'] 	=	base_url().$this->default_lang_url.($parent["section"]?$parent["section"]:$parent["url_key"]);
				array_push($navigation,$item);
			}
		}
		
		$item['name']	=	$page['name'];
		$item['url']	=	"";
		array_push($navigation,$item);						
			
		//meta tags
		//===========================================================						
		$this->page_title 					= $page['name'];
		$this->page_meta_title 				= $this->page_title;
		if($page['meta_title'])	
			$this->page_meta_title 			= $page['meta_title'];		
		if($page['meta_description'])
			$this->page_meta_description	= $page['meta_description'];		
		if($page['meta_keywords'])	
			$this->page_meta_keywords		= $page['meta_keywords'];		
		$this->canonical_link 				= base_url().$this->default_lang_url.($page["section"]?$page["section"]:$page["url_key"]);	
		
		//send data to view
		//=============================================================
		$data['page'] 		= $page;		
		$data['subpages'] 	= $subpages;
		$data['navigation'] = $navigation;	
		$data['body'] 		= "front/page";						
		$data 				= array_merge($data, $this->global_variables);
		if($iframe_template && $iframe_template == "popup")
			$this->load->view('front/template_iframe',$data);			
		else
			$this->load->view('front/template',$data);
	}		
}