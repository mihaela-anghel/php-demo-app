<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Galleries extends Base_controller 
{	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();	

		$this->load->model('pages_model');				
		$this->load->model('galleries_model');
		$this->load->model('galleries_categories_model');	
	}
		
	/**
	 * Listing galleries
	 * @param string $url_key
	 * @param int $offset
	 */
	public function index($current_tag = false)
	{																																
		show_404();			
	}	
	
	/**
	 * proiect details page
	 * @param string $url_key
	 * @param string $action
	 */
	function details($url_key = false)
	{								
		/*//get page info
		//=============================================================		
		$where 	= " AND lang_id = ".$this->default_lang_id." 
					AND active = '1' 
					AND section = 'galleries' ";		
		$pages 	= $this->pages_model->get_pages($where,false,false,false,false);
		if(!$pages) show_404();
		$page 	= $pages[0];*/
		
		$url_key = explode("-",$url_key);	
		
		//get proiect info
		//============================================================		
		$where 		= "	AND t1.active = '1' 
						AND lang_id = ".$this->default_lang_id."  
						AND CONCAT('gal',t1.gallery_id) = '".end($url_key)."'
						";		
		$galleries 	= $this->galleries_model->get_galleries($where,false,false,false,false);
		if(!$galleries) show_404();
		$gallery 	= $galleries[0];	
		
		//get gallery images
		//============================================================		
		$where 				= "AND gallery_id = ".$gallery["gallery_id"]." AND active ='1'";
		$orderby 			= "ORDER BY `order` ASC";	
		$images 			= $this->galleries_model->get_images($where,$orderby,false,false,false);
		$gallery['images']	= $images;	

		//get gallery videos
		//=============================================================		
		$where 			= "AND gallery_id = ".$gallery['gallery_id']." AND active = '1'";
		$orderby 		= "ORDER BY `order` ASC";	
		$videos 		= $this->galleries_model->get_videos($where,$orderby,false,false,false);
		$gallery['videos']	= $videos;	
				
		//set meta tags
		//============================================================
		$this->page_title 				= $gallery['name'];							
		$this->page_meta_title			= ($gallery['meta_title']?$gallery['meta_title']:$gallery['name']);	
		$this->page_meta_description	= ($gallery['meta_description']?$gallery['meta_description']:$gallery['name']);			
		$this->page_meta_keywords		= ($gallery['meta_description']?$gallery['meta_description']:str_replace(" ",",",$gallery['name']));		
		
		//set navigation
		//============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang_url
							  );
//		$navigation[1] 	= array( 	'name' 	=>	$page["name"],
//									'url'	=>	base_url().$this->default_lang_url.($page["section"]?$page["section"]:$page["url_key"])
//								  );						  
		$navigation[1] 	= array( 	'name' 	=>	$this->page_title,
									'url'	=>	""
								  );					  											
		//$navigation	   = array_reverse($navigation);	
		
		//send data to view
		//============================================================
		//unset($this->page_title);
		$data['gallery']			= $gallery;
		$data['navigation']			= $navigation;
		$data['body']				= 'front/galleries/gallery_details';
		$data = array_merge($data, $this->global_variables);		
		$this->load->view('front/template',$data);			
	}			
}