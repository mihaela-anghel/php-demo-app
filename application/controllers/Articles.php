<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Articles extends Base_controller 
{	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();	

		$this->load->model('pages_model');				
		$this->load->model('articles_model');					
	}
		
	/**
	 * Listing articles
	 * @param string $url_key
	 * @param int $offset
	 */
	public function index($offset=0)
	{																																
		//set section name
		//=============================================================
		if(!$offset) $offset = 0;		
		
		//set section name
		//=============================================================
		$section_name 	= "articles";		
		
		//get page info
		//=============================================================		
		$where 	= " AND lang_id = ".$this->default_lang_id." 
					AND active = '1' 
					AND section = 'articles' ";		
		$pages 	= $this->pages_model->get_pages($where,false,false,false,false);
		if(!$pages) show_404();
		$page 	= $pages[0];
		
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
	  			
		//$where for articles
		//============================================================
		$where = "  AND active = '1' 
					AND lang_id = '".$this->default_lang_id."' ";
		
		//SORT
		//============================================================						 		
		$orderby = "ORDER BY `order` DESC";
		
		//PAGINATION
		//======================================================						
		$rows 					= $this->articles_model->get_articles($where,false,false,false,"count(t1.article_id) as nr");		
		$total_rows 			= $rows[0]["nr"];							
		$this->load->library("pagination");		
		$config["base_url"] 	= base_url().$this->default_lang_url.$page["section"];
		$config["total_rows"]	= $total_rows;				
		$config["per_page"] 	= $this->setting->item("number_per_page");							
		$config["uri_segment"]	= 1;
		$config["cur_page"]		= $offset;		
		$config["first_link"]	= $this->lang->line("first");		
		$config["last_link"] 	= $this->lang->line("last");		
		$this->pagination->initialize($config);		
		$pagination 			= $this->pagination->create_links();
						
		//get articles	
		//============================================================	
		$articles			= $this->articles_model->get_articles($where,$orderby,$config['per_page'],$offset,false);
		foreach($articles as $key=>$article)
		{						
			/*
			//get article images		
			$where 			= "AND article_id = ".$article['article_id']." AND active = '1' ";
			$orderby 		= "ORDER BY `order` ASC";	
			$images 		= $this->articles_model->get_images($where,$orderby,1,0,false);
			$articles[$key]['images']	= $images;										
			*/													
		}
				
		//set meta tags
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
		
		//set navigation
		//============================================================		
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
								  );
		$navigation[1] 	= array( 	'name' 	=>	$page["name"],
									'url'	=>	""
								  );							  
		
		//send data to view
		//============================================================
		$data['page']				= $page;
		$data['articles']	        = $articles;
		$data['pagination']			= $pagination;	
		$data['navigation'] 		= $navigation;
		$data['body'] 				= "front/articles/articles";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);				
	}	
	
	/**
	 * article details page
	 * @param string $url_key
	 * @param string $action
	 */
	function details($url_key = false)
	{								
		$url_key = explode("-",$url_key);	
		
		//get page info
		//=============================================================		
		$where 	= " AND lang_id = ".$this->default_lang_id." 
					AND active = '1' 
					AND section = 'articles' ";		
		$pages 	= $this->pages_model->get_pages($where,false,false,false,false);
		if(!$pages) show_404();
		$page 	= $pages[0];
				
		//get article info
		//============================================================		
		$where 		= "	AND t1.active = '1' 
						AND lang_id = ".$this->default_lang_id."  
						AND CONCAT('article',t1.article_id) = '".end($url_key)."'
						";		
		$articles 	= $this->articles_model->get_articles($where,false,false,false,false);
		if(!$articles) show_404();
		$article 	= $articles[0];					
		
		//get article images
		//============================================================		
		$where 				= "AND article_id = ".$article["article_id"]." AND active ='1'";
		$orderby 			= "ORDER BY `order` ASC";	
		$images 			= $this->articles_model->get_images($where,$orderby,false,false,false);
		$article['images']	= $images;	
		
		//get article files
		//============================================================		
		$where 				= "AND article_id = ".$article["article_id"]." AND active ='1'";
		$orderby 			= "ORDER BY `order` ASC";	
		$files 			    = $this->articles_model->get_files($where,$orderby,false,false,false);
		$article['files']	= $files;
		
		//get article videos
		//============================================================		
		$where 				= "AND article_id = ".$article["article_id"]." AND active ='1'";
		$orderby 			= "ORDER BY `order` ASC";	
		$videos 			= $this->articles_model->get_videos($where,$orderby,false,false,false);
		$article['videos']	= $videos;	
		
		//get articles	
		//============================================================	
		$where = "  AND active = '1' 
					AND lang_id = '".$this->default_lang_id."' ";		
		$orderby = "ORDER BY `order` ASC";									
		$articles			    = $this->articles_model->get_articles($where,$orderby);		
		$data['articles']	    = $articles;
		
		//set meta tags
		//============================================================
		$this->page_title 				= $article['name'];							
		$this->page_meta_title			= ($article['meta_title']?$article['meta_title']:$article['name']);	
		$this->page_meta_description	= ($article['meta_description']?$article['meta_description']:$article['name']);			
		$this->page_meta_keywords		= ($article['meta_description']?$article['meta_description']:str_replace(" ",",",$article['name']));		
		
		//set navigation
		//============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang_url
							  );
		$navigation[1] 	= array( 	'name' 	=>	$page["name"],
									'url'	=>	base_url().$this->default_lang_url.($page["section"]?$page["section"]:$page["url_key"])
								  );						  
		$navigation[2] 	= array( 	'name' 	=>	$this->page_title,
									'url'	=>	""
								  );					  											
		//$navigation	   = array_reverse($navigation);	
		
		//send data to view
		//============================================================
		//unset($this->page_title);
		$data['page']				= $page;
		$data['article']			= $article;
		$data['navigation']			= $navigation;
		$data['body']				= 'front/articles/article_details';
		$data = array_merge($data, $this->global_variables);		
		$this->load->view('front/template',$data);			
	}			
}