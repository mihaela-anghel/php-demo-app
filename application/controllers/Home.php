<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Home extends Base_controller 
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
	public function index()
	{
		//get page
		//=============================================================
		$where 	= " AND lang_id  = ".$this->default_lang_id."  
					AND section  = 'home' 
					AND active 	 = '1' 					
					";		
		$pages 	= $this->pages_model->get_pages($where);
		if(!$pages) die('page inactive');
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

		/*
		//get subpages
		//=============================================================
		$where		= " AND lang_id = ".$this->default_lang_id." 
						AND active = '1' 
						AND parent_id = '".$page['page_id']."' ";
		$orderby 	= "ORDER BY `order` ASC";		
		$subpages 	= $this->pages_model->get_pages($where,$orderby,false,false,false);	
		$data['subpages'] 	= $subpages;
		
		//get home pages
		//=============================================================
		$where		= " AND lang_id = ".$this->default_lang_id." 
						AND active = '1' 
						AND on_home = '1' ";
		$orderby 	= "ORDER BY `order` ASC";		
		$home_pages	= $this->pages_model->get_pages($where,$orderby,false,false,false);
		$data['home_pages'] = $home_pages;	
		
		//get banners
		//======================================================
		$this->load->model('banners_model');
		$where 					= " AND active = '1' 
									AND position = 'home' ";
		$orderby				= " ORDER BY `order` ASC ";
		$fields 				= false;		
		$data['home_banners'] = $this->banners_model->get_banners($where, $orderby, 3, 0, $fields);
		*/
		
		if($this->setting->item["show_arbiters"] == "yes")
		{
			//get arbiters
			//======================================================
			$this->load->model('arbiters_model');
			$where 						= " AND active = '1' ";
			$orderby					= " ORDER BY `order` ASC ";
			$fields 					= false;		
			$data['home_arbiters']    = $this->arbiters_model->get_arbiters($where, $orderby, false, false, $fields);
		}

		if($this->setting->item["show_testimonials"] == "yes")
		{
			//get testimonials
			//======================================================
			$this->load->model('testimonials_model');
			$where 						= " AND lang_id = ".$this->default_lang_id." 
											AND active = '1' ";
			$orderby					= " ORDER BY `order` ASC ";
			$fields 					= false;		
			$data['home_testimonials']  = $this->testimonials_model->get_testimonials($where, $orderby, false, false, $fields);
		}
		
		if($this->setting->item["show_winners"] == "yes")
		{
			//get last x competitions for winners
		    $competition_ids = array(0);
			$where 					= "AND t1.status = 'close' 
			                           AND lang_id = ".$this->default_lang_id." ";
			$orderby                = "ORDER BY start_registration_date DESC";
			$competitions 			= $this->competitions_model->get_competitions($where, $orderby, $this->setting->item["winnrers_competitions_number"], 0, "t1.competition_id");
			foreach ($competitions as $competition)
			    $competition_ids[] = $competition["competition_id"];
			
			//get winners
			//======================================================					    
			$where						= " AND c.status = 'close'
                					        AND p.on_home = '1'
                					        AND p.diploma != '' 
                					        AND p.prize_id > 0
			                                AND pr.type = 'prize'
			                                AND c.competition_id IN (".implode(",",$competition_ids).") 
			                                 ";
			$orderby					= " ORDER BY c.start_registration_date DESC, 
			                                cat.order ASC,
			                                 age_cat.order ASC,
			                                 pr.order ASC 
			                                 ";
			$fields                     = "   u.image, u.name, countries.country_name,
                    		                  cd.name as competition,
                    		                  prd.certificate as prize,
			                                  p.project_filename, p.project_link_extern,
			                                  catd.category_name,
			                                  age_catd.age_category_name
                    						";	
			$data['home_winners']       = $this->competitions_model->get_winners($where, $orderby, false, false, $fields);						
		}				
				
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
		$this->canonical_link 				= base_url().$this->default_lang_url;
		unset($this->page_title);
		
		//send data to view
		//=========================================================
		$data["page"]		= $page;		
		$data["body"] 		= "front/home";
		$data 				= array_merge($data, $this->global_variables);
		$this->load->view("front/template",$data);	
	}	
	
	function verify_certificate()
	{
        if(isset($_POST))
        {            
            //form validation
    		$this->load->library('form_validation');				
    		$this->form_validation->set_rules('serial_number',		"s/n",	  'trim|required');				
    		$this->form_validation->set_rules('captcha',			" ",											'trim|required|matches_captcha');	
			$this->form_validation->set_message('matches_captcha',	$this->lang->line('matches_captcha'));																
    		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">','</div>');
    		$form_is_valid = $this->form_validation->run();						
    						
    		//if form valid
    		if($form_is_valid)
    		{																																							
    			//get participants		
        		//=========================================================	
        		$where 			= " AND project_number = '".$this->db->escape_str($_POST["serial_number"])."'
        		                    AND diploma != ''
        		                  ";
        		$participants 	= $this->competitions_model->get_participants($where);	
        		if(!$participants) 
        		{        		    
        		    ?>
        		    <div class="alert alert-danger mt-3">
            		    <div><b><?php echo "S/N invalid";?></b></div>            		    
        		    </div>
        		    <?php
        		}
        		else
        		{
        		    $participant = $participants[0];
        		    
        		    //get competition
            		//=========================================================		
            		$where 					= "AND t1.competition_id = '".$participant["competition_id"]."' ";
            		$competitions 			= $this->competitions_model->get_competitions($where);
            		if(!$competitions) die();	
            		$competition 			= $competitions[0];
            		            		
            		//get prize
            		//=========================================================		
            		$where 			= " AND lang_id = '".$this->default_lang_id."'
            							AND t1.prize_id = '".$participant["prize_id"]."'  ";
            		$orderby 		= "ORDER BY `order` ASC";
            		$prizes = $this->competitions_model->get_prizes($where,$orderby,false,false,false);
            		if($prizes)            		
            			$participant["prize"] = $prizes[0];	
            		
        		    ?>
        		    <div class="alert alert-success mt-3">
            		    <div><b><?php echo $this->lang->line('verify_certificate_valid');?></b></div>
            		    <div><?php echo custom_date(date("Y-m-d"), $this->default_lang)?></div>
            		    <div><?php echo $participant["name"]?></div>
            		    <div><?php echo $participant["country_name"]?></div>
            		    <div><?php echo $competition["name"]?></div>
            		    <?php
            		    if(isset($participant["prize"]))
            		    {
            		        ?><div><b><?php echo $participant["prize"]["certificate"]?></b></div><?php
            		    }
            		    else
            		    {
            		        ?><div><b><?php echo ucfirst($this->lang->line('competition_certificate'));?></b></div><?php
            		    }
            		    ?>
        		    </div>
        		    <?php
        		}        						
    		}
    		else 
    		    echo validation_errors();	
        }
	}
	
	function resolution($type = "desktop")
	{				
		if($type == "desktop")
			$_SESSION["hide_mobile_style"] = true;
		else if(isset($_SESSION["hide_mobile_style"]))
			unset($_SESSION["hide_mobile_style"]); 

		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php	
	}		
}