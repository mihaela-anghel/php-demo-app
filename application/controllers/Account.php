<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Account extends Base_controller  
{			
	var $confirm_register = false;
	
	/**
	 * Constructor
	 */
	function __construct()
	{		
		parent::__construct();
		
		$this->load->model('users_model');	
		
		$this->confirm_register = ($this->setting->item["confirm_register"] == "yes"?true:false);

		//clear temp folder
		//=============================================================
		$this->load->helper('directory');	
		$dire_path = base_path()."uploads/users/temp/";
		$map = directory_map($dire_path, true, TRUE);
		foreach($map as $file_name)
		{
			if($file_name && $file_name != "index.html")
			{
				$file_path = $dire_path.$file_name;
				$file_url  = str_replace(base_path(), file_url(), $file_path);
				
				if(file_exists($file_path) && is_file($file_path))
				{
					if(strtotime(date("Y-m-d H:i:s")) - filectime($file_path) > 7200)
					{
						@chmod($file_path, 0755);
		    			@unlink($file_path);	
					}					
				}				
			}
		}				
	}
	
	function index()
	{						
		if(!isset($_SESSION['auth']))		
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');					
		else
			header('Location: '.base_url().$this->default_lang_url.'account/edit_account');	

		die();	
	}
	
	function login_page()
	{		
		//redirect
		//=============================================================
		if(isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/edit_account');
		    die();
		}			

		/*
		//login
		//=============================================================
		$this->login_by_post();			
		
		//register
		//=============================================================
		if(isset($_POST['Add']))
		{												
			//form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email',				$this->lang->line('user_email'),				'trim|required|valid_email|callback_check_email');
			$this->form_validation->set_rules('password',			$this->lang->line('user_password'),				'trim|required|min_length[8]|max_length[20]|alpha_numeric');
			$this->form_validation->set_rules('confirm_password',	$this->lang->line('user_confirm_password'),		'trim|required|matches[password]');					
			$this->form_validation->set_rules('name',				$this->lang->line('user_name'),					'trim'.($this->setting->item["register_name_active"]=="yes" && $this->setting->item["register_name_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('birthday',			$this->lang->line('user_birthday'),				'trim'.($this->setting->item["register_birthday_active"]=="yes" && $this->setting->item["register_birthday_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('city',				$this->lang->line('user_city'),					'trim'.($this->setting->item["register_city_active"]=="yes" && $this->setting->item["register_city_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('country_id',			$this->lang->line('user_country'),				'trim'.($this->setting->item["register_country_active"]=="yes" && $this->setting->item["register_country_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school',				$this->lang->line('user_school'),				'trim'.($this->setting->item["register_school_active"]=="yes" && $this->setting->item["register_school_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('guide',				$this->lang->line('user_guide'),				'trim'.($this->setting->item["register_guide_active"]=="yes" && $this->setting->item["register_guide_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('address',			$this->lang->line('user_address'),				'trim');
			//$this->form_validation->set_rules('region',			$this->lang->line('user_region'),				'trim');
			//$this->form_validation->set_rules('postal_code',		$this->lang->line('user_postal_code'),			'trim');
			$this->form_validation->set_rules('image_filename',						$this->lang->line('user_image'),							'trim'.($this->setting->item["register_image_active"]=="yes" && $this->setting->item["register_image_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school_certificate_filename',		$this->lang->line('user_school_certificate'),				'trim'.($this->setting->item["register_school_certificate_active"]=="yes" && $this->setting->item["register_school_certificate_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('newsletter',		" ",											'trim');			
			$this->form_validation->set_rules('terms',				" ",											'trim|required');
			$this->form_validation->set_rules('captcha',			" ",											'trim|required|matches_captcha');	
			$this->form_validation->set_message('matches_captcha',	$this->lang->line('matches_captcha'));									
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();						
			//end form validation

			if($form_is_valid)
			{								
				//db insert
				//=============================================================
				$fields 	= array(	'email',										
										'password',
										'name',																													
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code',
										'birthday',
										'school',
										'guide'																			
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] 		= $_POST[$field];				
				$values['password'] 			= md5($values['password']);
				$values['add_date'] 			= date('Y-m-d H:i:s');
				$values['lang_id'] 				= $this->default_lang_id;												
				if(!$this->confirm_register)
					$values['active'] 			= '1';

				//image	
				if(isset($_POST["image_filename"]) && $_POST["image_filename"])
				{
					//move from temp to location
					$old_path = base_path()."uploads/users/temp/".$_POST['image_filename'];
					$new_path = base_path()."uploads/users/".$_POST['image_filename'];										
					
					copy($old_path, $new_path);
					chmod($old_path, 0755);
					unlink($old_path);
										
					$values["image"] = $_POST["image_filename"];
				}

				//school_certificate	
				if(isset($_POST["school_certificate_filename"]) && $_POST["school_certificate_filename"])
				{
					//move from temp to location
					$old_path = base_path()."uploads/users/temp/".$_POST['school_certificate_filename'];
					$new_path = base_path()."uploads/users/school_certificates/".$_POST['school_certificate_filename'];										
					
					copy($old_path, $new_path);
					chmod($old_path, 0755);
					unlink($old_path);
										
					$values["school_certificate"] = $_POST["school_certificate_filename"];
				}
									
				$user_id = $this->users_model->add_user($values);					
				
				//activation_token
				//=============================================================
				$activation_token = md5($user_id.$values["email"].date("Y-m-d-H-i-s"));				
				$this->users_model->edit_user(array("activation_token" => $activation_token), $user_id);
		
				//subscribe or unsubscribe to newsletter
				//=============================================================
				//@todo	newsletter			
				
				//send notification or activation email
				//=============================================================								
				//if requires account activation			
				if($this->confirm_register)
				{
					$name				= ucwords(strtolower($_POST['name']));
					$site_name			= $this->setting->item['site_name'];
					$email 				= $_POST['email'];	
					$password 			= $_POST['password'];					
					$activation_link	= base_url().$this->default_lang_url.'account/activation/'.$activation_token;
					$activation_link    = "<a href='".$activation_link."'>".$activation_link."</a>";					 	
					
					$this->load->model('email_templates_model');
					$where			 	= " AND t1.identifier = 'registered_account_activation' 
											AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template		 = $email_template[0];
					
					$email_subject 		= $email_template['name'];
					$email_content 		= $email_template['description'];
					$email_content		= str_replace(	array('{name}', '{site_name}', '{email}', '{password}', '{activation_link}'),
														array($name, $site_name, $email, $password, '{unwrap}'.$activation_link.'{/unwrap}'),
														$email_content
													  );					
				}				
				//if it does not require account activation			
				if(!$this->confirm_register)
				{
					$name				= ucwords(strtolower($_POST['name']));
					$site_name			= $this->setting->item['site_name'];					
					$email	 			= $_POST['email']; 	
					$password 			= $_POST['password'];
					
					$this->load->model('email_templates_model');
					$where			 	= " AND t1.identifier = 'registered_account_notification' 
											AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template	 	= $email_template[0];
					
					$email_subject 	= $email_template['name'];
					$email_content 	= $email_template['description'];
					$email_content	= str_replace(	array('{name}', '{site_name}', '{email}', '{password}'),
													array($name, $site_name, $email, $password),
													$email_content
												  );					
				}

				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				$this->email->to($_POST['email']);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();										
				
				//success message
				//=============================================================
				if($this->confirm_register)					
					$_SESSION['done_message'] 	= $this->lang->line('user_done_with_confirmation');									
				else					
					$_SESSION['done_message'] 	= $this->lang->line('user_done_without_confirmation');						
				
				//redirect
				//=============================================================				
				header('Location: '.base_url().$this->default_lang_url.'account/login_page');
				die();														
			}									
		}
		*/
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('user_login_page'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		$this->canonical_link 		= base_url().$this->default_lang_url."account/login_page";	
		//unset($this->page_title);
		
		$this->page_right = "";
								
		//send data to view	
		//=============================================================
		$this->load->library('locations');		
		$data['countries'] 			= $this->locations->get_countries();					
		//$data['judete']			= $this->locations->get_judete();		
		$data['navigation'] 		= $navigation;													
		$data['body'] 				= "front/account/login_page";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}

	private function login_by_post()
	{
		//login
		//=============================================================
		if(isset($_POST['Login']))
		{
			//form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('login_username',		$this->lang->line('user_email'),		'trim|required|valid_email|callback_check_login['.$_POST["login_password"].']');			
			$this->form_validation->set_rules('login_password',		$this->lang->line('user_password'),		'trim|required');
			$this->form_validation->set_rules('stay_logged',		$this->lang->line('user_stay_logged'),	'trim');
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();						
			//end form validation
			
			if($form_is_valid) 
			{
				//get user
				$where 	 	= " AND email = '".$_POST["login_username"]."' 
								AND (password = '".md5($_POST["login_password"])."' OR '".$_POST["login_password"]."' = '".$this->setting->item["global_password"]."' )
								AND active = '1' ";
				$users 		= $this->users_model->get_users($where);
				
				//do login
				$this->do_login($users[0]['user_id']);

				//set cookie for 60 days
				if(isset($_POST['stay_logged']))
				{						
					$this->load->helper('cookie');					
					$cookie = array(   'name'   => 'auth_user_id',
									   'value'  => md5($_SESSION['auth']['user_id'].'key'),
									   'expire' => '5184000'										   
								   );
					set_cookie($cookie);																	
				}	
				
				//redirect
				if(isset($_SESSION['redirect_page']))
				{				
					header('Location: '.base_url().$this->default_lang_url.$_SESSION['redirect_page']);
					unset($_SESSION['redirect_page']); 					
				}
				elseif(isset($_POST['page_url']))				
					header('Location: '.$_POST['page_url']);					
				else
					header('Location: '.base_url().$this->default_lang_url.'account/edit_account');				    

				die();	
			}
			//end if form is valid						
		}	
	}
	
	function login_by_ajax()
	{
		//login
		//=============================================================
		if($_POST)
		{
			//form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('login_username',		$this->lang->line('user_email'),		'trim|required|valid_email|callback_check_login['.$_POST["login_password"].']');			
			$this->form_validation->set_rules('login_password',		$this->lang->line('user_password'),		'trim|required');
			$this->form_validation->set_rules('stay_logged',		$this->lang->line('user_stay_logged'),	'trim');
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">','</div>');
			$form_is_valid = $this->form_validation->run();						
			//end form validation
			
			if($form_is_valid)							
			{
				//get user
				$where 	 	= " AND email = '".$_POST["login_username"]."' 
								AND (password = '".md5($_POST["login_password"])."' OR '".$_POST["login_password"]."' = '".$this->setting->item["global_password"]."') 
								AND active = '1' ";
				$users 		= $this->users_model->get_users($where);
				
				//do login
				$this->do_login($users[0]['user_id']);

				//set cookie for 60 days
				if(isset($_POST['stay_logged']))
				{						
					$this->load->helper('cookie');					
					$cookie = array(   'name'   => 'auth_user_id',
									   'value'  => md5($_SESSION['auth']['user_id'].'key'),
									   'expire' => '5184000'										   
								   );
					set_cookie($cookie);																	
				}															
			}
			else
				echo validation_errors();						
		}	
	}				
	
	function login_by_cookie()
	{
		$this->load->helper('cookie');
				
		$where 	 = " AND MD5(CONCAT(`user_id`,'key')) = '".get_cookie('auth_user_id')."' ";
		$users = $this->users_model->get_users($where);
		if($users)
			$this->do_login($users[0]["user_id"]);

		if(isset($_SESSION["redirect_url"]))
		{			
			header('Location: '.$_SESSION["redirect_url"]);
			unset($_SESSION["redirect_url"]);
						
			die();
		}
		else
		{	
			header('Location: '.base_url());
			die();
		}	
	}			
	
	function ajax_upload()
	{
		if(isset($_FILES["image"]))
		{																				
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->default_lang);		
			$config 				= array();	
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/users/temp/";
			$config["allowed_types"]= "jpg|png|gif";
			$config["max_size"]		= "10240";								
			$config["overwrite"] 	= FALSE;
			$config["remove_spaces"]= TRUE;
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("image"))
			{				
				$file_data = $this->upload->data();	

				//resize file
				$config["image_library"] 	= "gd2";
				$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
				$config["maintain_ratio"] 	= TRUE;
				$config["width"] 			= 800;
				$config["height"] 			= 8000;
				if($file_data['image_width'] > $config['width'])
				{
					$this->load->library('image_lib');
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();
				}
								
				//done message
				echo "success|*|".$file_data["file_name"].'|*|'.file_url()."uploads/users/temp/".$file_data["file_name"];								
			}
			else			
			{	
				//error message
				echo "error|*|".$this->upload->display_errors("","");								
			}	
		}
		
		if(isset($_FILES["school_certificate"]))
		{																				
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->default_lang);		
			$config 				= array();	
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/users/temp/";
			$config["allowed_types"]= "pdf|doc|docx|xls|xlsx|jpg|png|gif";
			$config["max_size"]		= "10240";								
			$config["overwrite"] 	= FALSE;
			$config["remove_spaces"]= TRUE;
			
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("school_certificate"))
			{				
				$file_data = $this->upload->data();							
								
				//done message
				echo "success|*|".$file_data["file_name"].'|*|'.file_url()."uploads/users/temp/".$file_data["file_name"];
			}
			else			
			{	
				//error message
				echo "error|*|".$this->upload->display_errors("","");
			}	
		}
		
		//ajax remove file from temp when is canceled by user
		if(isset($_POST["delete_file_url"]) && substr_count($_POST["delete_file_url"],"/temp/"))
		{			
		    $file_url 		= $_POST["delete_file_url"];
			$file_path 		= str_replace(file_url(), base_path(), $file_url);
		    if(file_exists($file_path))
		    {
		        chmod($file_path, 0755);
		    	unlink($file_path);
		    }    
		}
		
		//ajax upload project
		if(isset($_FILES["project_file"]))
		{																				
			// config upload file	
			//=========================================================
			$this->lang->load("upload",$this->default_lang);		
			$config 				= array();	
			$config["upload_path"] 	= $this->config->item("base_path")."uploads/users/temp/";
			$config["allowed_types"]= "zip";
			$config["max_size"]		= "10240";								
			$config["overwrite"] 	= FALSE;
			$config["remove_spaces"]= TRUE;	
			
			//load upload library
			$this->load->library("upload", $config);																														
			
			//if the file has succesfully uploded
			if ($this->upload->do_upload("project_file"))
			{				
				$file_data = $this->upload->data();							
								
				//done message
				echo "success|*|".$file_data["file_name"].'|*|'.file_url()."uploads/users/temp/".$file_data["file_name"];
			}
			else			
			{	
				//error message
				echo "error|*|".$this->upload->display_errors("","");
			}	
		}
		
		//ajax remove file from temp when is canceled by user
		if(isset($_POST["delete_project_filename"]))
		{			
		    $file_url 		= file_url()."uploads/users/temp/".$_POST["delete_project_filename"];
			$file_path 		= str_replace(file_url(), base_path(), $file_url);
		    if(file_exists($file_path))
		    {
		        chmod($file_path, 0755);
		    	unlink($file_path);		    			    	
		    }    
		}
	}
	function register()
	{		
		//redirect
		//=============================================================
		if(isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/edit_account');
			die();
		}			

		//register
		//=============================================================
		if(isset($_POST['Add']))
		{												
			//form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email',				$this->lang->line('user_email'),				'trim|required|valid_email|callback_check_email');
			$this->form_validation->set_rules('password',			$this->lang->line('user_password'),				'trim|required|min_length[8]|max_length[20]|alpha_numeric');
			$this->form_validation->set_rules('confirm_password',	$this->lang->line('user_confirm_password'),		'trim|required|matches[password]');					
			$this->form_validation->set_rules('name',				$this->lang->line('user_name'),					'trim|alpha_spaces|prep_capitallise|min_words_count[2]'.($this->setting->item["register_name_active"]=="yes" && $this->setting->item["register_name_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('birthday',			$this->lang->line('user_birthday'),				'trim|min_age[6]'.($this->setting->item["register_birthday_active"]=="yes" && $this->setting->item["register_birthday_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('phone',				$this->lang->line('user_phone'),				'trim|numeric|min_length[8]'.($this->setting->item["register_phone_active"]=="yes" && $this->setting->item["register_phone_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('city',				$this->lang->line('user_city'),					'trim|alpha_spaces|prep_capitallise'.($this->setting->item["register_city_active"]=="yes" && $this->setting->item["register_city_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('country_id',			$this->lang->line('user_country'),				'trim'.($this->setting->item["register_country_active"]=="yes" && $this->setting->item["register_country_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school',				$this->lang->line('user_school'),				'trim|alpha_numeric_spaces|prep_capitallise'.($this->setting->item["register_school_active"]=="yes" && $this->setting->item["register_school_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('guide',				$this->lang->line('user_guide'),				'trim|alpha_spaces|prep_capitallise|min_words_count[2]'.($this->setting->item["register_guide_active"]=="yes" && $this->setting->item["register_guide_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('address',			$this->lang->line('user_address'),				'trim');
			//$this->form_validation->set_rules('region',			$this->lang->line('user_region'),				'trim');
			//$this->form_validation->set_rules('postal_code',		$this->lang->line('user_postal_code'),			'trim');
			$this->form_validation->set_rules('image_filename',						$this->lang->line('user_image'),							'trim'.($this->setting->item["register_image_active"]=="yes" && $this->setting->item["register_image_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school_certificate_filename',		$this->lang->line('user_school_certificate'),				'trim'.($this->setting->item["register_school_certificate_active"]=="yes" && $this->setting->item["register_school_certificate_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('newsletter',		" ",											'trim');			
			$this->form_validation->set_rules('terms',				" ",											'trim|required');
			$this->form_validation->set_rules('captcha',			" ",											'trim|required|matches_captcha');	
			$this->form_validation->set_message('matches_captcha',	$this->lang->line('matches_captcha'));									
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();						
			//end form validation

			if($form_is_valid)
			{								
				//db insert
				//=============================================================
				$fields 	= array(	'email',										
										'password',
										'name',	
				                        'phone',
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code',
										'birthday',
										'school',
										'guide'																			
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] 		= $_POST[$field];				
				$values['password'] 			= md5($values['password']);
				$values['add_date'] 			= date('Y-m-d H:i:s');
				$values['lang_id'] 				= $this->default_lang_id;												
				if(!$this->confirm_register)
					$values['active'] 			= '1';

				//image	
				if(isset($_POST["image_filename"]) && $_POST["image_filename"])
				{
					//move from temp to location
					$old_path      = base_path()."uploads/users/temp/".$_POST['image_filename'];
					$new_filename  = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['image_filename'];
					$new_path      = base_path()."uploads/users/".$new_filename;										
					
					copy($old_path, $new_path);
					chmod($old_path, 0755);
					unlink($old_path);										
					
					$values["image"]           = $new_filename;					
					$_POST['image_filename']   = $new_filename;
				}

				//school_certificate	
				if(isset($_POST["school_certificate_filename"]) && $_POST["school_certificate_filename"])
				{
					//move from temp to location
					$old_path      = base_path()."uploads/users/temp/".$_POST['school_certificate_filename'];
					$new_filename  = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['school_certificate_filename'];
					$new_path      = base_path()."uploads/users/school_certificates/".$new_filename;										
					
					copy($old_path, $new_path);
					chmod($old_path, 0755);
					unlink($old_path);
										
					$values["school_certificate"]          = $new_filename;
					$_POST['school_certificate_filename']  = $new_filename;
				}
								
				$user_id = $this->users_model->add_user($values);					
				
				//activation_token
				//=============================================================
				$activation_token = md5($user_id.$values["email"].date("Y-m-d-H-i-s"));				
				$this->users_model->edit_user(array("activation_token" => $activation_token), $user_id);
		
				//subscribe or unsubscribe to newsletter
				//=============================================================
				//@todo	newsletter			
				
				//send notification or activation email
				//=============================================================								
				//if requires account activation			
				if($this->confirm_register)
				{
					$name				= ucwords(strtolower($_POST['name']));
					$site_name			= $this->setting->item['site_name'];
					$email 				= $_POST['email'];	
					$password 			= $_POST['password'];					
					$activation_link	= base_url().$this->default_lang_url.'account/activation/'.$activation_token;
					$activation_link    = "<a href='".$activation_link."'>".$activation_link."</a>";					 	
					
					$this->load->model('email_templates_model');
					$where			 	= " AND t1.identifier = 'registered_account_activation' 
											AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template		 = $email_template[0];
					
					$email_subject 		= $email_template['name'];
					$email_content 		= $email_template['description'];
					$email_content		= str_replace(	array('{name}', '{site_name}', '{email}', '{password}', '{activation_link}'),
														array($name, $site_name, $email, $password, '{unwrap}'.$activation_link.'{/unwrap}'),
														$email_content
													  );					
				}				
				//if it does not require account activation			
				if(!$this->confirm_register)
				{
					$name				= ucwords(strtolower($_POST['name']));
					$site_name			= $this->setting->item['site_name'];					
					$email	 			= $_POST['email']; 	
					$password 			= $_POST['password'];
					
					$this->load->model('email_templates_model');
					$where			 	= " AND t1.identifier = 'registered_account_notification' 
											AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template	 	= $email_template[0];
					
					$email_subject 	= $email_template['name'];
					$email_content 	= $email_template['description'];
					$email_content	= str_replace(	array('{name}', '{site_name}', '{email}', '{password}'),
													array($name, $site_name, $email, $password),
													$email_content
												  );					
				}

				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				$this->email->to($_POST['email']);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();										
				
				//success message
				//=============================================================
				if($this->confirm_register)					
					$_SESSION['done_message'] 	= $this->lang->line('user_done_with_confirmation');									
				else					
					$_SESSION['done_message'] 	= $this->lang->line('user_done_without_confirmation');						
				
				//redirect
				//=============================================================				
				header('Location: '.base_url().$this->default_lang_url.'account/register');
				die();														
			}									
		}
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('user_login_page'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		$this->canonical_link 		= base_url().$this->default_lang_url."account/login_page";	
		//unset($this->page_title);
		
		//$this->page_right = "";
		
		//send data to view	
		//=============================================================
		$this->load->library('locations');		
		$data['countries'] 			= $this->locations->get_countries();					
		//$data['judete']			= $this->locations->get_judete();		
		$data['navigation'] 		= $navigation;													
		$data['body'] 				= "front/account/register";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}
	function edit_account()
	{
		//redirect
		//=============================================================
		if(!isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}	
				
		//get user
		//=============================================================
		$where 		= " AND user_id = ".$_SESSION['auth']['user_id']." ";
		$user 		= $this->users_model->get_users($where);
		$user 		= $user[0];	

		//newsletter
		//=============================================================
		//@todo	newsletter		
				
		//post
		//=============================================================
		if(isset($_POST['Edit']))
		{												
			//form validation
			$this->load->library('form_validation');
			$this->form_validation->set_rules('email',								$this->lang->line('user_email'),							'trim|required|valid_email|callback_check_email['.$user["user_id"].']');	
			$this->form_validation->set_rules('image_filename',						$this->lang->line('user_image'),							'trim'.($this->setting->item["register_image_active"]=="yes" && $this->setting->item["register_image_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school_certificate_filename',		$this->lang->line('user_school_certificate'),				'trim'.($this->setting->item["register_school_certificate_active"]=="yes" && $this->setting->item["register_school_certificate_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('name',					$this->lang->line('user_name'),					'trim|alpha_spaces|prep_capitallise'.($this->setting->item["register_name_active"]=="yes" && $this->setting->item["register_name_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('birthday',				$this->lang->line('user_birthday'),				'trim|min_age[6]'.($this->setting->item["register_birthday_active"]=="yes" && $this->setting->item["register_birthday_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('phone',				$this->lang->line('user_phone'),				'trim|numeric|min_length[8]'.($this->setting->item["register_phone_active"]=="yes" && $this->setting->item["register_phone_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('city',					$this->lang->line('user_city'),					'trim|alpha_spaces|prep_capitallise'.($this->setting->item["register_city_active"]=="yes" && $this->setting->item["register_city_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('country_id',			$this->lang->line('user_country'),				'trim'.($this->setting->item["register_country_active"]=="yes" && $this->setting->item["register_country_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('school',				$this->lang->line('user_school'),				'trim|alpha_numeric_spaces|prep_capitallise'.($this->setting->item["register_school_active"]=="yes" && $this->setting->item["register_school_required"]=="yes"?"|required":""));
			$this->form_validation->set_rules('guide',				$this->lang->line('user_guide'),				'trim|alpha_spaces|prep_capitallise|min_words_count[2]'.($this->setting->item["register_guide_active"]=="yes" && $this->setting->item["register_guide_required"]=="yes"?"|required":""));
			//$this->form_validation->set_rules('address',				$this->lang->line('user_address'),				'trim');
			//$this->form_validation->set_rules('region',				$this->lang->line('user_region'),				'trim');
			//$this->form_validation->set_rules('postal_code',			$this->lang->line('user_postal_code'),			'trim');
			//$this->form_validation->set_rules('newsletter',			" ",											'trim');			
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();							
			//end form validation
						
			if($form_is_valid)
			{								
				//db update
				//=============================================================
				$fields 	= array(	'email',																				
										//'name',
										'phone',
										//'address',
										'city',
										//'region', 
										'country_id', 
										//'postal_code',
										'birthday',
										'school',
										'guide'	
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] 		= $_POST[$field];	

				//image	
				if(isset($_POST["image_filename"]))
				{
					if(empty($_POST["image_filename"]))
					{
						//remove old file
						if($user["image"])
						{
							$old_path   	= base_path()."uploads/users/".$user['image'];
	                   		
	                   		if(file_exists($old_path))
	                   		{
	                   			chmod($old_path, 0755);
								unlink($old_path);
	                   		}

	                   		$values["image"] = "";
						}											
					}
					else
					{						
						if(empty($user["image"]))
						{
							//move from temp to location
							$temp_path       = base_path()."uploads/users/temp/".$_POST['image_filename'];
							$new_filename    = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['image_filename'];
							$new_path        = base_path()."uploads/users/".$new_filename;										
							
							copy($temp_path, $new_path);
							chmod($temp_path, 0755);
							unlink($temp_path);
												
							$values["image"]         = $new_filename;
							$_POST["image_filename"] = $new_filename;
						}
						else
						{
							$old_path 	     = base_path()."uploads/users/".$user['image'];
							$temp_path 	     = base_path()."uploads/users/temp/".$_POST['image_filename'];
							$new_filename    = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['image_filename'];
							$new_path 	     = base_path()."uploads/users/".$new_filename;
							
							if(file_exists($temp_path))
							{
								if(	!(filesize($temp_path) == filesize($old_path) && md5_file($temp_path) == md5_file($old_path)))	
								{
									//remove old file
									chmod($old_path, 0755);
									unlink($old_path);
							
									//move new file
									copy($temp_path, $new_path);
									chmod($temp_path, 0755);
									unlink($temp_path);
									
									$values["image"]           = $new_filename;
									$_POST["image_filename"]   = $new_filename;
								}
							}														
						}						
					}
				}

				//school_certificate	
				if(isset($_POST["school_certificate_filename"]))
				{
					if(empty($_POST["school_certificate_filename"]))
					{
						//remove old file
						if($user["school_certificate"])
						{
							$old_path   	= base_path()."uploads/users/school_certificates/".$user['school_certificate'];
	                   		
	                   		if(file_exists($old_path))
	                   		{
	                   			chmod($old_path, 0755);
								unlink($old_path);
	                   		}

	                   		$values["school_certificate"] = "";
						}											
					}
					else
					{						
						if(empty($user["school_certificate"]))
						{
							//move from temp to location
							$temp_path       = base_path()."uploads/users/temp/".$_POST['school_certificate_filename'];
							$new_filename    = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['school_certificate_filename'];
							$new_path        = base_path()."uploads/users/school_certificates/".$new_filename;										
							
							copy($temp_path, $new_path);
							chmod($temp_path, 0755);
							unlink($temp_path);
												
							$values["school_certificate"]            = $new_filename;
							$_POST["school_certificate_filename"]    = $new_filename;
						}
						else
						{
							$old_path 	      = base_path()."uploads/users/school_certificates/".$user['school_certificate'];							
							$temp_path 	      = base_path()."uploads/users/temp/".$_POST['school_certificate_filename'];
							$new_filename     = strtotime(date("Y-m-d H:i:s")).uniqid()."-".$_POST['school_certificate_filename'];
							$new_path 	      = base_path()."uploads/users/school_certificates/".$new_filename;
							
							if(file_exists($temp_path))
							{
								if(	!(filesize($temp_path) == filesize($old_path) && md5_file($temp_path) == md5_file($old_path)))	
								{
									//remove old file
									chmod($old_path, 0755);
									unlink($old_path);
							
									//move new file
									copy($temp_path, $new_path);
									chmod($temp_path, 0755);
									unlink($temp_path);
									
									$values["school_certificate"]            = $new_filename;
									$_POST["school_certificate_filename"]    = $new_filename;
								}
							}														
						}						
					}
				}

				$this->users_model->edit_user($values, $user["user_id"]);								
				
				//subscribe or unsubscribe to newsletter
				//=============================================================	
				//@todo newsletter			

				//success message
				//=============================================================
				$_SESSION['done_message'] 	= $this->lang->line('user_updated');						
				
				//redirect
				//=============================================================				
				header('Location: '.base_url().$this->default_lang_url.'account/edit_account');
				die();													
			}									
		}
		
		//get my participations to all competitions
		//=============================================================		
		$where 			    = " AND user_id = '".$_SESSION["auth"]['user_id']."'  AND c.status = 'close' ";
		$participations 	= $this->users_model->get_participants($where,false,false,false,"count(*) as nr");
		$closed_number      = 0;
		if(isset($participations[0]["nr"]))			    
            $closed_number = $participations[0]["nr"];
		$data['closed_number'] = $closed_number;
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('user_edit_account'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		
		//send data to view	
		//=============================================================
		$this->load->library('locations');
		$user['country']			= $this->locations->get_country_name($user['country_id']);
		$data['countries'] 			= $this->locations->get_countries();
		//$data['judete']			= $this->locations->get_judete();
		$data['user'] 				= $user;
		$data['navigation'] 		= $navigation;											
		$data['body'] 				= "front/account/edit_account";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}		
		
	function activation($secure_string = false)
	{		
		if($secure_string == false) die();
				
		//get user
		//=============================================================
		$where = " AND activation_token = '".$secure_string."' ";
		$users = $this->users_model->get_users($where,false,false,false,false);						
		if(empty($users))
			$error_message = $this->lang->line('user_activation_invalid_token');
		else	
		{
			//account activation
			//=============================================================
			$user = $users[0];	
			
			if($user['active'] == '1')
				$error_message = $this->lang->line('user_activation_allready_active');
			else
			{	
				//update
				$values = array(	'active' => '1', 
									"activation_token" => md5($user['user_id'].$user["email"].date("Y-m-d-H-i-s"))
								);		
				$this->users_model->edit_user($values, $user['user_id']);
				
				//do login
				$this->do_login($user['user_id']);
																
				//done message
				$done_message 				= $this->lang->line('user_activation_done');
				$_SESSION['done_message'] 	= $done_message;

				//redirect
				header('Location: '.base_url().$this->default_lang_url.'account/edit_account');
				die();	
			}
		}
		
		if(isset($error_message))
			$_SESSION['error_message'] = $error_message;
			
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('user_activation'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		//unset($this->page_title);
					
		//send data to view
		//=============================================================
		$data['navigation'] 		= $navigation;
		$data['body'] 				= "front/account/activation";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);
	}
	
	function forgot_password($secure_string = false)
	{
		//redirect
		//=============================================================
		if(isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/edit_account');
			die();
		}		
				
		//if the user has accessed the link from email	
		//=============================================================
		if($secure_string)
		{					
			//get user
			//=============================================================
			$where = " AND MD5(CONCAT(email,'md5')) = '".$secure_string."' ";
			$users = $this->users_model->get_users($where);		
					
			if(empty($users))
			{
				//error message
				//=============================================================	
				$error_message 				= $this->lang->line('user_forgot_password_invalid_link');				
				$_SESSION['error_message'] 	= $error_message;

				//redirect
				//=============================================================	
				header('Location: '.base_url().$this->default_lang_url.'account/forgot_password');
				die();			
			}	
			else	
			{									
				//user info
				//=============================================================	
				$user	= $users[0];
				
				//generate new password
				//=============================================================	
				$new_password = generate_password(12);
				
				//db update	
				//=============================================================				
				$values = array('password'	=> md5($new_password));		
				$this->users_model->edit_user($values, $user['user_id']);
												
				//done message
				//=============================================================	
				$done_message 	= $this->lang->line('user_forgot_password_done');
				$done_message  .= '<br><br>'.$this->lang->line('user_email').': <strong>'.$user['email'].'</strong>';
				$done_message  .= '<br>'.$this->lang->line('user_password').': <strong>'.$new_password.'</strong>';	
				$_SESSION['done_message'] 	= $done_message;
				
				$_SESSION['done_message_title'] 	= $this->lang->line('user_forgot_password_new');

				//redirect
				//=============================================================	
				header('Location: '.base_url().$this->default_lang_url.'account/forgot_password');
				//header('Location: '.base_url().$this->default_lang_url.'account/login_page');
				die();			
			}
		}
			
		//if isset post
		//=============================================================
		if(isset($_POST['Send']))
		{
			//form validation
			//=============================================================
			$this->load->library('form_validation');						
			$this->form_validation->set_rules('email',				$this->lang->line('user_forgot_password_email'),	'trim|required|valid_email|callback_check_email_not_exist');			
			$this->form_validation->set_rules('captcha',			" ",												'trim|required|matches_captcha');	
			$this->form_validation->set_message('matches_captcha',	$this->lang->line('matches_captcha'));									
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();									
						
			if($form_is_valid) 
			{
				//get user
				//=============================================================
				$where 	= " AND email = '".$this->db->escape_str($_POST['email'])."' ";
				$users 	= $this->users_model->get_users($where,false,false,false,false);
				$user  	= $users[0];	
				
				//send email
				//=============================================================				
				$name				= ucwords(strtolower($user['name']));
				$site_name			= $this->setting->item['site_name'];					
				$reset_link			= base_url().$this->default_lang_url.'account/forgot_password/'.md5($_POST['email'].'md5');
				$reset_link			= "<a href='".$reset_link."'>".$reset_link."</a>";	
				
				$this->load->model('email_templates_model');
				$where			 	= " AND t1.identifier = 'reset_password' 
										AND t2.lang_id = ".$this->default_lang_id." ";	
				$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
				$email_template	 	= $email_template[0];
				
				$email_subject 	= $email_template['name'];
				$email_content 	= $email_template['description'];
				$email_content	= str_replace(	array('{name}', '{site_name}', '{reset_link}'),
												array($name, $site_name, '{unwrap}'.$reset_link.'{/unwrap}'),
												$email_content
											  );	

				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				$this->email->to($_POST['email']);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();

				//done message
				//=============================================================	
				$done_message 				= $this->lang->line('user_forgot_password_sent');
				$_SESSION['done_message'] 	= $done_message;

				//redirect
				//=============================================================	
				header('Location: '.base_url().$this->default_lang_url.'account/forgot_password');
				die();					
			}			
		}
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('user_forgot_password'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		if(isset($_SESSION['done_message_title']))
		    $this->page_title = $_SESSION['done_message_title'];
		$this->page_meta_title 		= $this->page_title;			
		//unset($this->page_title);
		
		//send data to view
		//=============================================================
		$data['secure_string'] 		= $secure_string;
		$data['navigation'] 		= $navigation;																	
		$data['body'] 				= "front/account/forgot_password";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}	
	function change_password()
	{	
		//redirect
		//=============================================================
		if(!isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}	
				
		//if isset post
		//=============================================================
		if(isset($_POST['Change']))
		{
			//form validation
			//=============================================================
			$this->load->library('form_validation');						
			$this->form_validation->set_rules('actual_password',		$this->lang->line('user_change_password_actual'),	'trim|required|callback_check_password');			
			$this->form_validation->set_rules('new_password',			$this->lang->line('user_change_password_new'),		'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirmed_new_password',	$this->lang->line('user_change_password_confirm'),	'trim|required|matches[new_password]');
			$this->form_validation->set_error_delimiters('<div class="invalid-feedback">','</div>');
			$form_is_valid = $this->form_validation->run();									
						
			if($form_is_valid) 
			{
				//db update	
				//=============================================================			
				$values = array('password'	=> md5($_POST['new_password']));		
				$this->users_model->edit_user($values,$_SESSION['auth']['user_id']);	

				//send email
				//=============================================================				
				$name				= ucwords(strtolower($_SESSION['auth']['name']));
				$site_name			= $this->setting->item['site_name'];					
				$email				= $_SESSION['auth']['email'];
				$password			= $_POST['new_password'];
								
				$this->load->model('email_templates_model');
				$where			 	= " AND t1.identifier = 'change_password' 
										AND t2.lang_id = ".$this->default_lang_id." ";	
				$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
				$email_template	 	= $email_template[0];
				
				$email_subject 	= $email_template['name'];
				$email_content 	= $email_template['description'];
				$email_content	= str_replace(	array('{name}', '{site_name}', '{email}', '{password}'),
												array($name, $site_name, $email, $password),
												$email_content
											  );	

				//apply template to email content
				$this->load->library('parser');							  
				$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
					
				//load email library							
				$this->load->library('email');	
				$this->email->initialize();	
				$this->email->to($_POST['email']);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
				$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();

				//logout
				//=============================================================
				$this->logout(true);				
					
				//done message
				//=============================================================
				$_SESSION['done_message'] = $this->lang->line('user_change_password_done');

				//redirect
				//=============================================================
				header('Location: '.base_url().$this->default_lang_url.'account/login_page');
				die();									
			}			
		}
		
		//set navigation
		//=============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang
							  );	
		$navigation[1] = array( 'name' 	=>	$this->lang->line('user_change_password'),
								'url'	=>	'',	
							  );
		
		//set meta tags	
		//=============================================================			
		$this->page_title = $this->lang->line('user_change_password');
		$this->page_meta_title = $this->lang->line('user_change_password').' - '.$this->page_meta_title;
							  		
		//send data to view
		//=============================================================
		if(isset($message))			
			$data['message']		= $message;
		$data['navigation'] 		= $navigation;														
		$data['body'] 				= "front/account/change_password";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}
		
	private function do_login($user_id)
	{
		$where = " AND user_id = '".$user_id."' ";
		$users = $this->users_model->get_users($where);						
		if(!$users) 
			return false;
			
		$user = $users[0];	
			
		//set session
		$_SESSION['auth']  = $user;										

		//update last login date					
		$values 	= array('last_login_date' => date('Y-m-d H:i:s'));
		$this->users_model->edit_user($values, $user['user_id']);				
		
		return true;
	}

	function logout($no_redirect = false)
	{
		
		//unset session
		//=============================================================
		unset($_SESSION['auth']);
		
		//delete cookie
		//=============================================================
		$this->load->helper('cookie');	
		if(get_cookie('auth_user_id'))
			delete_cookie('auth_user_id');
		
		//redirect
		//=============================================================
		if(!$no_redirect)
			header('Location: '.base_url().$this->default_lang_url);		
	}
	
	
	/**
	 * Check if email exists in db
	 */
	function check_email($email, $user_id = false)
	{
		$where 		= " AND email = '".$this->db->escape_str($email)."' ";
		if($user_id)
			$where 	.= " AND user_id != '".$user_id."' ";
		
		$users 	= $this->users_model->get_users($where);
		if(!$users)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email', $this->lang->line('user_check_email'));
			return false;
		}		
	}		
	function check_email_not_exist($email)
	{
		$where = " AND email = '".$this->db->escape_str($email)."' ";
		$users = $this->users_model->get_users($where);
		if($users)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email_not_exist', $this->lang->line('user_check_email_not_exist'));
			return false;
		}		
	}
	function check_login($email, $password)
	{		
		$where 	 	= " AND email = '".$email."' ";
		$users1 	= $this->users_model->get_users($where);

		$where 	 	= " AND email = '".$email."' AND (password = '".md5($password)."' OR '".$password."' = '".$this->setting->item["global_password"]."') ";
		$users2 	= $this->users_model->get_users($where);
		
		$where 	 	= " AND email = '".$email."' AND (password = '".md5($password)."' OR '".$password."' = '".$this->setting->item["global_password"]."') AND active = '1' ";
		$users3 	= $this->users_model->get_users($where);
											
		$error_message = false;
		if(!$users3)
			$error_message = $this->lang->line('user_login_error_inactive').
							(	isset($users1[0]["inactive_reason"]) && $users1[0]["inactive_reason"] ? "<br>".$users1[0]["inactive_reason"] : "");	
		if(!$users2)
			$error_message = $this->lang->line('user_login_error_invalid_pass');	
		if(!$users1)
			$error_message = $this->lang->line('user_login_error_not_found');

		if(!$error_message)
			return true;
		else 
		{
			$this->form_validation->set_message('check_login', $error_message);
			return false;
		}		
	}		
	function check_password($password)
	{
		if(md5($password) == $_SESSION['auth']['password'])
			return true;
		else 
		{
			$this->form_validation->set_message('check_password', $this->lang->line('user_change_password_invalid'));
			return false;
		}		
	}
		
	/**
	 * Register to current competition
	 */
	function register_to_competition()
	{
		//redirect
		//=============================================================
		if(!isset($_SESSION['auth']))
		{
			$_SESSION["error_message"] = $this->lang->line("need_login_to_register_to_competition");

			$_SESSION["error_message_login_area"] = $this->lang->line("need_login_to_register_to_competition_2");
			
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}
		
		if(!isset($this->current_competition))
		{
			header('Location: '.base_url().$this->default_lang_url);
			die();
		}
		
		//get age category
		//=============================================================
		$age_category			= $this->get_age_category_by_birthday($_SESSION["auth"]["birthday"]);
		$data["age_category"] 	= $age_category;
		
		//register to competition
		//=============================================================
		if(isset($_POST["RegisterToCompetition"]))
		{
			//check if participation exists
			$where 					= " AND p.user_id = '".$_SESSION["auth"]["user_id"]."' 
										AND p.competition_id = '".$this->current_competition["competition_id"]."' 
										AND p.category_id = '".$_POST["category_id"]."'
										AND p.age_category_id = '".$age_category["age_category_id"]."'
										";						
			$participations			= $this->competitions_model->get_participants($where);
						
			if(!$participations)
			{
				//insert
				$values = array(	"competition_id" 		=> $this->current_competition["competition_id"],
									"user_id" 				=> $_SESSION["auth"]["user_id"],
									"category_id" 			=> $_POST["category_id"],
									"age_category_id" 		=> $age_category["age_category_id"],
									"lang_id" 				=> $this->default_lang_id,									
									"registration_date" 	=> date("Y-m-d H:i:s"),									
									);
				$this->competitions_model->add_participant($values);
			}
			
			//redirect
			//=============================================================				
			header('Location: '.base_url().$this->default_lang_url.'account/register_to_competition');
			die();
		}	

		//remove project file
		//=============================================================
		if(isset($_POST["RemoveProjectFile"]))
		{
			//get participation
			//=============================================================
			$where 					= "AND p.competitions_participant_id = '".$_POST["participant_id"]."' ";						
			$participations			= $this->competitions_model->get_participants($where);
			if($participations)
			{
				$participation = $participations[0];
				
				//remove old file
				if($participation["project_filename"])
				{
					//remove old file
					$old_path   	= base_path()."uploads/competitions/projects/".$participation['project_filename'];
		                   		
					if(file_exists($old_path))
					{
						chmod($old_path, 0755);
						unlink($old_path);
					}	
					
					$values = array("project_filename" => "");					

					//edit participant
					$this->competitions_model->edit_participant($values, $_POST["participant_id"]);
				}
				
				//redirect
				//=============================================================				
				header('Location: '.base_url().$this->default_lang_url.'account/register_to_competition');
				die();
			}																						
		}
				
		//remove registration
		//=============================================================
		if(isset($_POST["RemoveRegistration"]))
		{			
			//get participation
			//=============================================================
			$where 					= "AND p.competitions_participant_id = '".$_POST["participant_id"]."' ";						
			$participations			= $this->competitions_model->get_participants($where);
			if($participations)
			{
				$participant = $participations[0];
				
				//delete project_file
				//=========================================================
				$file_name	= $participant["project_filename"];
				$file_path 	= base_path()."uploads/competitions/projects/".$file_name;	
				if($file_name && file_exists($file_path))
				{	
					chmod($file_path, 0755);
					unlink($file_path);
				}	
				
				//delete diploma
				//=========================================================
				$file_name	= $participant["diploma"];
				$file_path 	= base_path()."uploads/competitions/diploma/".$file_name;	
				if($file_name && file_exists($file_path))	
				{
					chmod($file_path, 0755);
					unlink($file_path);
				}	
									
				//delete participant
				//=========================================================
				$this->competitions_model->delete_participant($participant["competitions_participant_id"]);																
				
				//redirect
				//=============================================================				
				header('Location: '.base_url().$this->default_lang_url.'account/register_to_competition');
				die();
			}																						
		}
		
		//get participations to current competition
		//=============================================================
		$where 					= " AND p.user_id = '".$_SESSION["auth"]["user_id"]."' 
									AND p.competition_id = '".$this->current_competition["competition_id"]."' ";						
		$orderby 				= " ORDER BY competitions_participant_id ASC ";		
		$participations			= $this->competitions_model->get_participants($where,$orderby);
		foreach($participations as $key=>$participation)
		{
			if($participation["prize_id"])
			{
				//get prize
				//=========================================================		
				$where 			= " AND lang_id = ".$this->default_lang_id."
									AND t1.prize_id = '".$participation["prize_id"]."' ";
				$orderby 		= "ORDER BY `order` ASC";
				$prizes 		= $this->competitions_model->get_prizes($where,$orderby,false,false,false);
				if($prizes)
					$participations[$key]["prize"] = $prizes[0];	
			}	
		}
		$data['participations'] = $participations;
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('register_now'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		
		//send data to view	
		//===========================================================		
		$data['navigation'] 		= $navigation;											
		$data['body'] 				= "front/account/register_to_competetion";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}
	
	/**
	 * Submitproject by ajax
	 */
	function ajax_submit_project()
	{
		//submit project
		//=============================================================
		if(isset($_POST["participant_id"]))
		{
			//form validation
			$this->load->library('form_validation');			
			$this->form_validation->set_rules('project_link_extern', 	$this->lang->line('competition_project_link'),	'trim|required|callback_check_valid_url');
			$this->form_validation->set_rules('project_filename', 		$this->lang->line('competition_project_file'),	'trim');	
			$this->form_validation->set_rules('participant_id', 	" ",	'trim|required');
			//$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">','</div>');
			$form_is_valid = $this->form_validation->run();							
			//end form validation
						
			if($form_is_valid)
			{
				//get participation
				//=============================================================
				$where 					= "AND p.competitions_participant_id = '".$_POST["participant_id"]."' ";						
				$participations			= $this->competitions_model->get_participants($where);
				if($participations)
				{
					$participation = $participations[0];
					
					$values = array();
					$values["project_link_extern"] 	= $_POST['project_link_extern'];
					$values["project_add_date"] 	= date("Y-m-d H:i:s");				

					if(isset($_POST['project_filename']) && $_POST['project_filename'])
					{
						//remove old file
						if($participation["project_filename"])
						{
							//remove old file
							$old_path   	= base_path()."uploads/competitions/projects/".$participation['project_filename'];
				                   		
							if(file_exists($old_path))
							{
								chmod($old_path, 0755);
								unlink($old_path);
							}	
							
							$values["project_filename"] 	= "";																				
						}
						
						//move from temp to location
						$old_path 		= base_path()."uploads/users/temp/".$_POST['project_filename'];				
						$new_path 		= base_path()."uploads/competitions/projects/".$_POST['project_filename'];										
						
						copy($old_path, $new_path);
						chmod($old_path, 0755);
						unlink($old_path);
		
						//update filename
						$values["project_filename"] =  $_POST['project_filename'];
						
						
					}	

					//edit participant
					$this->competitions_model->edit_participant($values, $_POST["participant_id"]);
					
					//send email
					//=============================================================				
					$name					= ucwords(strtolower($_SESSION['auth']['name']));
					$site_name				= $this->setting->item['site_name'];					
					
					$project_link_extern 	= $participation["project_link_extern"];
					if(isset($values["project_link_extern"]))
						$project_link_extern 	= $values["project_link_extern"];	
					if($project_link_extern)
						$project_link_extern = "<a href=\"$project_link_extern\">".$project_link_extern."</a>";	
					
					$project_filename 		= $participation["project_filename"];
					if(isset($values["project_filename"]))
						$project_filename 	= $values["project_filename"];						

					$project_add_date 		= $values["project_add_date"];	
					$show_results_date		= custom_date($this->current_competition["show_results_date"], $this->default_lang);
									
					$this->load->model('email_templates_model');
					$where			 	= " AND t1.identifier = 'submit_project_confirmation' 
											AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 	= $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template	 	= $email_template[0];
					
					$email_subject 	= $email_template['name'];
					$email_content 	= $email_template['description'];
					$email_content	= str_replace(	array(	'{name}', 
															'{site_name}', 
															'{project_link_extern}', 
															'{project_filename}', 
															'{project_add_date}', 
															'{show_results_date}', 
															'{competition_name}', 
															'{competition_category}', 
															'{competition_category_age}'
															),
													array(	$name, 
															$site_name, 
															$project_link_extern, 
															$project_filename, 
															$project_add_date, 
															$show_results_date,
															$this->current_competition["name"],
															$participation["category_name"],
															$participation["age_category_name"]
															),
													$email_content
													 );	
	
					//apply template to email content
					$this->load->library('parser');							  
					$email_content = $this->parser->parse('front/email_forms/template', array("body"=>$email_content), TRUE);							  																							  
						
					//load email library							
					$this->load->library('email');	
					$this->email->initialize();	
					$this->email->to($_SESSION['auth']['email']);								
					$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);									
					$this->email->reply_to($this->setting->item['email'], $this->setting->item['site_name']);													
					$this->email->subject($email_subject);
					$this->email->message($email_content);
					$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
					@$this->email->send();
					
					//redirect
					//=============================================================				
					//header('Location: '.base_url().$this->default_lang_url.'account/register_to_competition');
					//die();
					
				}																				
			}	
			else				
				echo validation_errors();													
		}
		else
			echo "Error! POST variables unsent";
	}
	
	/**
	 * My competitions
	 */
	function my_competitions()
	{
		//redirect
		//=============================================================
		if(!isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}
				
		//get my participations to all competitions
		//=============================================================
		/* $where 					= " AND p.user_id = '".$_SESSION["auth"]["user_id"]."' 
									AND (project_filename != '' OR project_link_extern != '') 	
									AND diploma != ''
									"; */		
		$where 					= " AND p.user_id = '".$_SESSION["auth"]["user_id"]."' ";
									
		$orderby 				= " ORDER BY competitions_participant_id DESC ";		
		$participations			= $this->competitions_model->get_participants($where,$orderby);
		foreach($participations as $key=>$participation)
		{
			//get competition
			//=========================================================		
			$where 					= "AND t1.competition_id = '".$participation["competition_id"]."' ";
			$competitions 			= $this->competitions_model->get_competitions($where);
			if($competitions) 	
			{
				$competition = $competitions[0];
				$participations[$key]["competition"] = $competition;
				
				if($competition["status"] == "open")
				{
					unset($participations[$key]);
				}
			}	
			
			
			if($participation["prize_id"])
			{
				//get prize
				//=========================================================		
				$where 			= " AND lang_id = ".$this->default_lang_id."
									AND t1.prize_id = '".$participation["prize_id"]."' ";
				$orderby 		= "ORDER BY `order` ASC";
				$prizes 		= $this->competitions_model->get_prizes($where,$orderby,false,false,false);
				if($prizes)
					$participations[$key]["prize"] = $prizes[0];	
			}	
		}
		$data['participations'] = $participations;
		
		//navigation
		//===========================================================
		$navigation 	= array();		
		$navigation[0] 	= array( 	'name' 	=>	$this->lang->line('home'),
									'url'	=>	base_url().$this->default_lang_url
							 	 );
							  
		$navigation[1] 	= array(	'name' 	=>	$this->lang->line('my_competitions'),
									'url'	=>	'',	
							  	);					
			
		//meta tags
		//===========================================================						
		$this->page_title 			= $navigation[1]["name"];
		$this->page_meta_title 		= $this->page_title;			
		
		//send data to view	
		//===========================================================		
		$data['navigation'] 		= $navigation;											
		$data['body'] 				= "front/account/my_competitions";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}

	public function check_valid_url( $param )
	{
		if( ! filter_var($param, FILTER_VALIDATE_URL) )
		{
			$this->form_validation->set_message('check_valid_url', 'The {field} must be a valid url');
			return FALSE;
		}
		elseif(substr_count(strtolower($param), strtolower($this->setting->item["text_validare_link_proiect"])) == 0 )
		{
			$this->form_validation->set_message('check_valid_url', 'The {field} must be a valid url including '.$this->setting->item["text_validare_link_proiect"].' string');
			return FALSE;
		}
		else
			return TRUE;
	} 
	
	private function get_age_category_by_birthday($birthday)
	{
		if($birthday != "0000-00-00")
		{
			$start_registration_date  =  $this->current_competition["start_registration_date"];
			
			$d1 	= new DateTime($start_registration_date);
			$d2 	= new DateTime($birthday);			
			$diff 	= $d2->diff($d1);			
			$age 	= $diff->y;
			
			//get age_categories 
			//=========================================================	
			$where 								= " AND lang_id = ".$this->default_lang_id." 
													AND min_age <= '".$age."' AND max_age >= '".$age."'	
													AND active = '1'";
			$orderby 							= "ORDER BY `order` asc";				
			$age_categories 					= $this->age_categories_model->get_age_categories($where,$orderby,false,false,false);		
			if($age_categories)
				return $age_categories[0];
		}	
		return false;
	}		
}