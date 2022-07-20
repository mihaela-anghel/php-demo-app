<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Contact extends Base_controller 
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
		$where 	= " AND lang_id = ".$this->default_lang_id."  
					AND section = 'contact' 
					AND active 	= '1' ";		
		$pages 	= $this->pages_model->get_pages($where);
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
		
		//get subpages
		//=============================================================
		$where		= " AND lang_id = ".$this->default_lang_id." 
						AND active = '1' 
						AND parent_id = '".$page['page_id']."' ";
		$orderby 	= "ORDER BY `order` ASC";		
		$subpages 	= $this->pages_model->get_pages($where,$orderby,false,false,false);

		//post
		//===========================================================			
		if(isset($_POST['Send']))
		{				
			//form validation
			$this->load->library('form_validation');				
			$this->form_validation->set_rules('contact_name',		$this->lang->line('contact_name'),		'trim|required');				
			$this->form_validation->set_rules('contact_phone',		$this->lang->line('contact_phone'),		'trim|required');											
			$this->form_validation->set_rules('contact_email',		$this->lang->line('contact_email'),		'trim|required|valid_email');
			$this->form_validation->set_rules('contact_subject',	$this->lang->line('contact_subject'),	'trim|required');			
			$this->form_validation->set_rules('contact_message',	$this->lang->line('contact_message'),	'trim|required');								 				
			$this->form_validation->set_rules('g-recaptcha-response', ' ', 'required|recaptcha');				
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();						
						
			//if form valid
			if($form_is_valid)
			{																																			
				$email_content =  "
<p>".$this->lang->line('contact_name').":	".$_POST['contact_name']."</p>
<p>".$this->lang->line('contact_phone').": 	".$_POST['contact_phone']."</p>
<p>".$this->lang->line('contact_email').": 	".$_POST['contact_email']."</p>
<p>".$this->lang->line('contact_subject').": ".$_POST['contact_subject']."</p>
<p>".$this->lang->line('contact_message').": ".$_POST['contact_message']."</p>"
;		
				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//send email
				//===========================================================
				$this->load->library('email');
				$this->email->initialize();
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($_POST['contact_email'], $_POST['contact_name']);				
				$this->email->to($this->setting->item['email']);										
				$this->email->subject($this->lang->line('contact_form')." - ".$this->setting->item["site_name"]);
				$this->email->message($email_content);
				$this->email->set_alt_message(strip_tags($email_content));				
				if($this->email->send())
				{
					$data['done_message'] = $this->lang->line('contact_sent');
					unset($_POST);
				}	
				else
					$data['error_message'] = $this->lang->line('contact_unsent');					
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
			$item['name'] 	=	$parent['name'];
			$item['url'] 	=	base_url().$this->default_lang_url.($parent["section"]?$parent["section"]:$parent["url_key"]);
			array_push($navigation,$item);
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
		unset($this->page_title);
		
		//send data to view
		//=============================================================
		$data['page'] 		= $page;
		$data['subpages'] 	= $subpages;		
		$data['navigation'] = $navigation;	
		$data['body'] 		= "front/contact";						
		$data 				= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);
	}	
}