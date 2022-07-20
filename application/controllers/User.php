<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

class User extends Base_controller  
{			
	function __construct()
	{
		
		parent::__construct();
		$this->load->model('users_model');	
		$this->lang->load('users',$this->default_lang);					
	}
	
	function index()
	{						
		if(!isset($_SESSION['auth']))		
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');					
		else
			header('Location: '.base_url().$this->default_lang_url.'account/edit_account');	

		die();	
	}	
	function logout($redirect = false)
	{
		
		//unset session
		//=============================================================
		unset($_SESSION['auth']);
		
		//delete cookie
		//=============================================================
		$this->load->helper('cookie');	
		if(get_cookie('auth_member_id'))
			delete_cookie('auth_member_id');
		
		//redirect
		//=============================================================
		if($redirect == false)
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
		else	
			header('Location: '.$redirect);
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
				
		//get member
		//=============================================================
		$member_id = $_SESSION['auth']['member_id'];		
		$where = " AND member_id = ".$member_id." ";
		$member = $this->users_model->get_members($where);
		$member = $member[0];		
		$this->load->model('newsletter_model');
		if($this->newsletter_model->check_exist($member['email'], '0'))
			$member['newsletter'] = '1';
		else
			$member['newsletter'] = '0';
				
		//post
		//=============================================================
		if(isset($_POST['Edit']))
		{												
			if($this->validate_add_edit_form())
			{								
				//db update
				//=============================================================
				$fields 	= array(	'type',
										'first_name',										
										'last_name',
										'cnp',										
										'mobile', 
										'company_name', 
										'company_vat_number', 
										'company_reg_com',
										'company_bank',
										'company_bank_account', 
										'company_phone',										
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code',
										'delivery_address',
										'delivery_city',
										'delivery_region', 
										'delivery_country_id', 
										'delivery_postal_code'										
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] = $_POST[$field];						

				//update
				$where = array('member_id' => $member_id);							
				$this->users_model->edit_member($values,$where);								
				
				//subscribe or unsubscribe to newsletter
				//=============================================================				
				if(isset($_POST['newsletter']) && $_POST['newsletter'] == '1')
				{																						
					if($this->newsletter_model->check_exist($member['email']))
					{
						if($this->newsletter_model->check_exist($member['email'],'1'))
							$this->newsletter_model->edit_subscriber($member['email'],array('is_unsubscribed' => '0'));						
					}
					else					
					{							
						$values = array(	'email'				=> $member['email'],
											'lang_id'			=> $member['lang_id'],									
											'subscribe_date' 	=> date('Y-m-d H:i:s'), 
											'subscribe_ip'		=> $_SERVER['REMOTE_ADDR'],
						 					'name'				=> $member["first_name"]." ".$member["last_name"],						
										);				
						$this->newsletter_model->subscribe($values);
						
						//get voucher newsletter
						//$this->load->library("order_lib");
						//$done_message_voucher = $this->order_lib->get_voucher_newsletter($member['email']);
					}					
				}
				else
				{																						
					if($this->newsletter_model->check_exist($member['email']))
					{
						if($this->newsletter_model->check_exist($member['email'],'0'))
						{	
							$values = array(	'unsubscribe_date' 	=> date('Y-m-d H:i:s'),
												'unsubscribe_ip' 	=> $_SERVER['REMOTE_ADDR'],  
												'is_unsubscribed' 	=> '1',										
											);							
							$this->newsletter_model->edit_subscriber($member['email'],$values);														
						}						
					}					
				}
																								
				
				//mesaj de update cu succes
				//=============================================================
				$data['done_message'] = $this->lang->line('member_updated');

				if(isset($done_message_voucher))
					$data['done_message'] .= $done_message_voucher;
			}									
		}
		
		//set navigation
		//=============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang
							  );	
		$navigation[1] = array( 'name' 	=>	$this->lang->line('member_edit_account'),
								'url'	=>	'',	
							  );

		//set meta tags	
		//=============================================================			
		$this->page_title 		= $this->lang->line('member_edit_account');
		$this->page_meta_title 	= $this->lang->line('member_edit_account').' - '.$this->page_meta_title;																 

		//send data to view	
		//=============================================================
		$this->load->library('locations');
		$data['countries'] 			= $this->locations->get_countries();
		$data['judete']				= $this->locations->get_judete();
		$data['member'] 			= $member;
		$data['navigation'] 		= $navigation;											
		$data['body'] 				= "front/account/edit_account";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}
	function validate_add_edit_form()
	{					
		$this->load->library('form_validation');						
		
		if(isset($_POST['Add']))
		{								
			//$this->form_validation->set_rules('username',			$this->lang->line('member_username'),				'trim|required|min_length[6]|max_length[25]|callback_check_username');
			$this->form_validation->set_rules('password',			$this->lang->line('member_password'),				'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirm_password',	$this->lang->line('member_confirm_password'),		'trim|required|matches[password]');					
			$this->form_validation->set_rules('email',				$this->lang->line('member_email'),					'trim|required|valid_email|callback_check_email');
		}	
		$this->form_validation->set_rules('type',					$this->lang->line('member_type'),					'trim');			
		$this->form_validation->set_rules('first_name',				$this->lang->line('member_first_name'),				'trim|required');
		$this->form_validation->set_rules('last_name',				$this->lang->line('member_last_name'),				'trim|required');
		$this->form_validation->set_rules('cnp',					$this->lang->line('member_cnp'),					'trim');		
		$this->form_validation->set_rules('mobile',					$this->lang->line('member_mobile'),					'trim|required');
		
		if($_POST['type'] == 'juridical')
		{
			$this->form_validation->set_rules('company_name',		$this->lang->line('member_company_name'),			'trim|required');
			$this->form_validation->set_rules('company_vat_number',	$this->lang->line('member_company_vat_number'),		'trim|required');
			$this->form_validation->set_rules('company_reg_com',	$this->lang->line('member_company_reg_com'),		'trim|required');
			$this->form_validation->set_rules('company_bank',		$this->lang->line('member_company_bank'),			'trim');
			$this->form_validation->set_rules('company_bank_account',$this->lang->line('member_company_bank_account'),	'trim');
			$this->form_validation->set_rules('company_phone',		$this->lang->line('member_company_phone'),			'trim');			
		}
		else
		{								
			$_POST['company_name'] 			= "";
			$_POST['company_vat_number']	= "";
			$_POST['company_reg_com']		= "";
			$_POST['company_bank'] 			= "";
			$_POST['company_bank_account']  = "";
			$_POST['company_phone'] 		= "";										
		}
		$this->form_validation->set_rules('address',				$this->lang->line('member_address'),			'trim|required');
		$this->form_validation->set_rules('city',					$this->lang->line('member_city'),				'trim|required');
		$this->form_validation->set_rules('region',					$this->lang->line('member_region'),				'trim|required');
		$this->form_validation->set_rules('country_id',				$this->lang->line('member_country_id'),			'trim');
		$this->form_validation->set_rules('postal_code',			$this->lang->line('member_postal_code'),		'trim');
		
		if(isset($_POST['Edit']))
		{
			$this->form_validation->set_rules('delivery_address',				$this->lang->line('member_address'),			'trim');
			$this->form_validation->set_rules('delivery_city',					$this->lang->line('member_city'),				'trim');
			$this->form_validation->set_rules('delivery_region',				$this->lang->line('member_region'),				'trim');
			$this->form_validation->set_rules('delivery_country_id',			$this->lang->line('member_country_id'),			'trim');
			$this->form_validation->set_rules('delivery_postal_code',			$this->lang->line('member_postal_code'),		'trim');			
		}
		
		if(isset($_POST['Add']))
		{
			$this->form_validation->set_rules('captcha',			' ',											'trim|required|matches_captcha');	
			$this->form_validation->set_message('matches_captcha',	$this->lang->line('captcha_error'));
			$this->form_validation->set_rules('terms',				$this->lang->line('member_terms'),				'trim|required');
		}
		$this->form_validation->set_rules('newsletter',				$this->lang->line('member_newsletter'),			'trim');
							
		$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
		return $this->form_validation->run();	
	}		
	function check_email($email)
	{
		$where = " AND email = '".$this->db->escape_str($email)."' ";
		$members = $this->users_model->get_members($where);
		if(!$members)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email', $this->lang->line('member_check_email'));
			return false;
		}		
	}	
	function check_email_not_exist($email)
	{
		$where = " AND email = '".$this->db->escape_str($email)."' ";
		$members = $this->users_model->get_members($where);
		if($members)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email_not_exist', $this->lang->line('member_check_email_not_exist'));
			return false;
		}		
	}	
	function check_username($username)
	{
		$where = " AND username = '".$this->db->escape_str($username)."' ";
		$members = $this->users_model->get_members($where);
		if(!$members)
			return true;
		else 
		{
			$this->form_validation->set_message('check_username', $this->lang->line('member_check_username'));
			return false;
		}		
	}	
	function check_password($password)
	{
		if(md5($password) == $_SESSION['auth']['password'])
			return true;
		else 
		{
			$this->form_validation->set_message('check_password', $this->lang->line('member_change_password_invalid'));
			return false;
		}		
	}	
	function activation($secure_string = false)
	{		
		if($secure_string == false) die();
		
		//set meta tags
		//=============================================================				
		$this->page_title 		= $this->lang->line('member_activation');
		$this->page_meta_title 	= $this->lang->line('member_activation').' - '.$this->page_meta_title;
		
		//get member
		//=============================================================
		$where = " AND activation_token = '".$secure_string."' ";
		$members = $this->users_model->get_members($where,false,false,false,false);						
		if(empty($members))
			$message = $this->lang->line('member_activation_not_valid');
		else	
		{
			//account activation
			//=============================================================
			$member = $members[0];	
			if($member['active'] == '1')
				$message = $this->lang->line('member_activation_allready_active');
			else
			{	
				//update
				//=============================================================
				$values = array('active' => '1', "activation_token" => md5($member['member_id'].$member["email"].date("Y-m-d-h-i-s")));		
				$where 	= array('member_id' => $member['member_id']);							
				$this->users_model->edit_member($values,$where);
								
				$message = $this->lang->line('member_activation_welcome').', '.ucfirst(strtolower($member['last_name'])).'. ';
				$message .= $this->lang->line('member_activation_done');
				
				//set session for display done message in login_page
				//=============================================================
				$_SESSION['password_change_done'] = $message;

				//redirect
				//=============================================================
				header('Location: '.base_url().$this->default_lang_url.'account/login_page');
				die();	
			}
		}
			
		//send data to view
		//=============================================================
		$data['message']			= $message;																	
		$data['body'] 				= "front/account/activation";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);
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

		//login
		//=============================================================
		if(isset($_POST['Login']))
		{
			$login_by = "email"; //$login_by = "email"; 
		
			//form validation
			$this->load->library('form_validation');
			if($login_by == 'username')
				$this->form_validation->set_rules('login_username',	$this->lang->line('username'),		'trim|required');
			if($login_by == 'email')
				$this->form_validation->set_rules('login_username',	$this->lang->line('email'),			'trim|required|valid_email');			
			$this->form_validation->set_rules('login_password',		$this->lang->line('password'),		'trim|required');
			$this->form_validation->set_rules('stay_logged',		$this->lang->line('stay_logged'),	'trim');
			$this->form_validation->set_error_delimiters('','');
			$form_is_valid = $this->form_validation->run();						
			//end form validation
						
			if(!$form_is_valid) 
			{
				if(isset($_SESSION['auth']))	
					unset($_SESSION['auth']);	
				
				$this->load->helper('form');			
				$error_message = form_error('login_username');
				$error_message .= "<br/>".form_error('login_password');									
			}
			else
			{																							
				$where 	 = " AND ".$login_by." = '".$_POST['login_username']."' ";
				$members1 = $this->users_model->get_members($where,false,false,false,false);
	
				$where 	 = " AND ".$login_by." = '".$_POST['login_username']."' AND password = '".md5($_POST['login_password'])."' ";
				$members2 = $this->users_model->get_members($where,false,false,false,false);
				
				$where 	 = " AND ".$login_by." = '".$_POST['login_username']."' AND password = '".md5($_POST['login_password'])."' AND active = '1' ";
				$members3 = $this->users_model->get_members($where,false,false,false,false);
											
				if(empty($members1))
					$error_message = $this->lang->line('login_error_not_found');
				else if(empty($members2))
					$error_message = $this->lang->line('login_error_invalid_pass');
				else if(empty($members3))
					$error_message = $this->lang->line('login_error_inactive');					
				else
				{																								
					//set session
					$_SESSION['auth']  = $members3[0];										

					//update last login date					
					$values = array('last_login' => date('Y-m-d H:i:s'));
					$where = array('member_id' => $members3[0]['member_id']);							
					$this->users_model->edit_member($values,$where);	
				
					//set cookie for 60 days
					if(isset($_POST['stay_logged']) &&  $_POST['stay_logged'] == '1')
					{						
						$this->load->helper('cookie');					
						$cookie = array(   'name'   => 'auth_member_id',
										   'value'  => md5($_SESSION['auth']['member_id'].'key'),
										   'expire' => '5184000'										   
									   );
						set_cookie($cookie);																	
					}
									
					$error_message = '';				
				}											
			}//end if form is valid

			//redirect
			if(isset($error_message) && $error_message == '') //if login has done
			{
				if(isset($_SESSION['return_link']))
				{				
					header('Location: '.base_url().$this->default_lang_url.$_SESSION['return_link']);
					unset($_SESSION['return_link']); exit();
				}
				elseif(isset($_POST['current_url']))
					header('Location: '.$_POST['current_url']);
				else
					header('Location: '.base_url().$this->default_lang_url.'account/edit_account');	
			}	
			else //if login error
			{
				if(isset($error_message))
					$_SESSION['login_error_message'] = $error_message;
				else
					header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			}	
			
		}//end if isset post
		
		//register
		//=============================================================
		if(isset($_POST['Add']))
		{												
			if($this->validate_add_edit_form())
			{								
				//inserez in tabela de members
				//=============================================================
				$fields 	= array(	//'username',										
										'password',
										'email',	
										'type',
										'first_name',										
										'last_name',
										'cnp',										
										'mobile',
										'company_name', 
										'company_vat_number', 
										'company_reg_com',
										'company_bank',
										'company_bank_account', 
										'company_phone',										
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code',
										'image',
										'thumb'										
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] 		= $_POST[$field];				
				$values['password'] 			= md5($values['password']);
				$values['registration_date'] 	= date('Y-m-d H:i:s');
				$values['lang_id'] 				= $this->default_lang_id;												
				
				//daca este dezactivata confirmarea la inregistrare se activeaza automat
				if($this->setting->item('confirmare_inregistrare') != 'yes')
				$values['active'] 	= '1';

				//insert
				$inserted_id = $this->users_model->add_member($values);								
				
				//set activation_token
				$activation_token = md5($inserted_id.$values["email"].date("Y-m-d-H-i-s"));
				$this->db->where(array("member_id" => $inserted_id));
				$this->db->update('members',array("activation_token" => $activation_token));
		
				//subscribe or unsubscribe to newsletter
				//=============================================================
				$this->load->model('newsletter_model');
				if(isset($_POST['newsletter']) && $_POST['newsletter'] == '1')
				{																						
					if($this->newsletter_model->check_exist($_POST['email']))
					{
						if($this->newsletter_model->check_exist($_POST['email'],'1'))
							$this->newsletter_model->edit_subscriber($_POST['email'],array('is_unsubscribed' => '0'));						
					}
					else					
					{	
						$values = array(	'email'				=> $_POST['email'],
											'lang_id'			=> $this->default_lang_id,										
											'subscribe_date' 	=> date('Y-m-d H:i:s'), 
											'subscribe_ip'		=> $_SERVER['REMOTE_ADDR'],// ia id-ul
						 					'name'				=> $_POST["first_name"]." ".$_POST["last_name"],						
										);
						$this->newsletter_model->subscribe($values);
						
						//get voucher newsletter
						//$this->load->library("order_lib");
						//$done_message_voucher = $this->order_lib->get_voucher_newsletter($_POST['email']);
					}
				}
				else
				{																						
					if($this->newsletter_model->check_exist($_POST['email']))
					{
						if($this->newsletter_model->check_exist($_POST['email'],'0'))
						{	
							$values = array(	'unsubscribe_date' 	=> date('Y-m-d H:i:s'),
												'unsubscribe_ip' 	=> $_SERVER['REMOTE_ADDR'],  
												'is_unsubscribed' 	=> '1',										
											);							
							$this->newsletter_model->edit_subscriber($_POST['email'],$values);
						}						
					}					
				}				
				
				//SEND NOTIFICATION OR ACTIVATION EMAIL
				//=============================================================								
				//daca necesita activare cont				
				if($this->setting->item('confirmare_inregistrare') == 'yes') //daca necesita activare cont
				{
					$name				= ucfirst(strtolower($_POST['first_name']));
					$site_name			= $this->setting->item('site_name');
					$activation_link	= base_url().$this->default_lang_url.'account/activation/'.$activation_token;
					$activation_link    = "<a href='".$activation_link."'>".$activation_link."</a>";
					$email 				= $_POST['email']; 	
					$password			= $_POST['password'];
					
					$this->load->model('email_templates_model');
					$where			 = " AND t1.identificator = 'registered_account_activation' AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 = $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template	 = $email_template[0];
					
					$email_subject 	= $email_template['title'];
					$email_content 	= $email_template['content'];
					$email_content	= str_replace(	array('{name}','{site_name}','{activation_link}','{email}','{password}'),
													array($name, $site_name, '{unwrap}'.$activation_link.'{/unwrap}', $email, $password),
													$email_content
												  );					
				}				
				//daca nu necesita activare cont				
				if($this->setting->item('confirmare_inregistrare') != 'yes')
				{
					$name				= ucfirst(strtolower($_POST['first_name']));
					$site_name			= $this->setting->item('site_name');					
					$email	 			= $_POST['email']; 	
					$password			= $_POST['password'];
					
					$this->load->model('email_templates_model');
					$where			 = " AND t1.identificator = 'registered_account_notification' AND t2.lang_id = ".$this->default_lang_id." ";	
					$email_template	 = $this->email_templates_model->get_email_templates($where,false,false,false,false);
					$email_template	 = $email_template[0];
					
					$email_subject 	= $email_template['title'];
					$email_content 	= $email_template['content'];
					$email_content	= str_replace(	array('{name}','{site_name}','{email}','{password}'),
													array($name, $site_name, $email, $password),
													$email_content
												  );					
				}				
				//load email library							
				$this->load->library('email');								
				$config['protocol'] = 'mail';					
				$config['charset']  = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html'; // text or html
				$config['newline']  = '\r\n'; // "\r\n" or "\n" or "\r"					
				$this->email->initialize($config);								
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);					
				$to = $_POST['email'];
				$this->email->to($to);										
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));				
				@$this->email->send();										
				
				//done
				//=============================================================
				if($this->setting->item('confirmare_inregistrare') == 'yes')					
					$data['done_message'] = $this->lang->line('member_done_with_confirmation');									
				else	
				{
					$link_to_login_page = base_url().$this->default_lang_url."account/login_page";
					$data['done_message'] = str_replace(array("%href"),array($link_to_login_page),$this->lang->line('member_done_without_confirmation'));
				}		
				
				if(isset($done_message_voucher))
					$data['done_message'] .= $done_message_voucher;
					
				//unset post
				//=============================================================
				//unset($_POST);
				$_SESSION["register_done"] = $data['done_message'];
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
		$navigation[1] = array( 'name' 	=>	$this->lang->line('member_authentification_page'),
								'url'	=>	'',	
							  );	
				
		//set meta tags	
		//=============================================================			
		$this->page_title		= $this->lang->line('member_authentification_page');
		$this->page_meta_title 	= $this->lang->line('member_authentification_page').' - '.$this->page_meta_title;
		
		//send data to view	
		//=============================================================
		$this->load->library('locations');		
		$data['countries'] 			= $this->locations->get_countries();					
		$data['judete']				= $this->locations->get_judete();		
		$data['navigation'] 		= $navigation;													
		$data['body'] 				= "front/account/login_page";		
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
		if($secure_string != false)
		{					
			//get member
			$where = " AND MD5(CONCAT(email,'md5')) = '".$secure_string."' ";
			$members = $this->users_model->get_members($where,false,false,false,false);		
					
			if(empty($members))
				$message = $this->lang->line('member_forgot_password_invalid_link');
			else	
			{									
				//member info
				$member	= $members[0];
				
				//generate new password
				$new_password = generate_password(10);
				
				//db update				
				$values = array('password'	=> md5($new_password));		
				$where 	= array('member_id'	=> $member['member_id']);							
				$this->users_model->edit_member($values,$where);
												
				//done message
				$message = $this->lang->line('member_forgot_password_done');
				$message .= '<br/>'.$this->lang->line('member_email').': '.$member['email'].'';
				$message .= '<br/>'.$this->lang->line('member_password').': '.$new_password.'';				
			}
		}
			
		//if isset post
		//=============================================================
		if(isset($_POST['Send']))
		{
			//form validation
			//=============================================================
			$this->load->library('form_validation');						
			$this->form_validation->set_rules('email',	$this->lang->line('member_forgot_password_email'),	'trim|required|valid_email|callback_check_email_not_exist');			
			$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
			$form_is_valid = $this->form_validation->run();									
						
			if($form_is_valid) 
			{
				//get member
				//=============================================================
				$where = " AND email = '".$_POST['email']."' ";
				$members = $this->users_model->get_members($where,false,false,false,false);		
						
				//send email
				//=============================================================																				
				//get email template
				$member				= $members[0];
				$name				= ucwords(strtolower($member['first_name'])).' '.ucwords(strtolower($member['last_name']));
				$site_name			= $this->setting->item('site_name');
				$reset_link			= base_url().$this->default_lang_url.'account/forgot_password/'.md5($_POST['email'].'md5');
				$reset_link			= "<a href='".$reset_link."'>".$reset_link."</a>";										
				
				$this->load->model('email_templates_model');
				$where			 = " AND t1.identificator = 'reset_password' AND t2.lang_id = ".$this->default_lang_id." ";	
				$email_template	 = $this->email_templates_model->get_email_templates($where,false,false,false,false);
				$email_template	 = $email_template[0];
				
				$email_subject 	= $email_template['title'];
				$email_content 	= $email_template['content'];
				$email_content	= str_replace(	array('{name}','{reset_link}'),
												array($name, '{unwrap}'.$reset_link.'{/unwrap}'),
												$email_content
											  );				
				//load email library							
				$this->load->library('email');										
				//config
				$config['protocol'] = 'mail';					
				$config['charset']  = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html'; // text or html
				$config['newline']  = '\r\n'; // "\r\n" or "\n" or "\r"					
				$this->email->initialize($config);										
				//send parametters
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);					
				$to = $_POST['email'];
				$this->email->to($to);										
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));										
				//send mail
				@$this->email->send();											
				
				//done message
				//=============================================================
				$message = $this->lang->line('member_forgot_password_sent');				
				unset($_POST);
			}			
		}
		
		//set navigation
		//=============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang
							  );	
		$navigation[1] = array( 'name' 	=>	$this->lang->line('member_forgot_password'),
								'url'	=>	'',	
							  );
			
		//set meta tags
		//=============================================================				
		$this->page_title		= $this->lang->line('member_forgot_password');
		$this->page_meta_title 	= $this->lang->line('member_forgot_password').' - '.$this->page_meta_title;			
		
		//send data to view
		//=============================================================
		if(isset($message))			
			$data['message']		= $message;
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
			$this->form_validation->set_rules('actual_password',		$this->lang->line('member_change_password_actual'),		'trim|required|callback_check_password');			
			$this->form_validation->set_rules('new_password',			$this->lang->line('member_change_password_new'),		'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirmed_new_password',	$this->lang->line('member_change_password_confirm'),	'trim|required|matches[new_password]');
			$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
			$form_is_valid = $this->form_validation->run();									
						
			if($form_is_valid) 
			{
				//db update	
				//=============================================================			
				$values = array('password'	=> md5($_POST['new_password']));		
				$where 	= array('member_id'	=> $_SESSION['auth']['member_id']);							
				$this->users_model->edit_member($values,$where);																
						
				//send email
				//=============================================================																				
				//get email template				
				$name				= ucwords(strtolower($_SESSION['auth']['first_name'])).' '.ucwords(strtolower($_SESSION['auth']['last_name']));
				$site_name			= $this->setting->item('site_name');
				$email				= $_SESSION['auth']['email'];
				$password			= $_POST['new_password'];										
				
				$this->load->model('email_templates_model');
				$where			 = " AND t1.identificator = 'change_password' AND t2.lang_id = ".$this->default_lang_id." ";	
				$email_template	 = $this->email_templates_model->get_email_templates($where,false,false,false,false);
				$email_template	 = $email_template[0];
				
				$email_subject 	= $email_template['title'];
				$email_content 	= $email_template['content'];
				$email_content	= str_replace(	array('{name}','{email}','{password}'),
												array($name, $email, $password),
												$email_content
											  );				
				//load email library							
				$this->load->library('email');										
				//config
				$config['protocol'] = 'mail';					
				$config['charset']  = 'utf-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html'; // text or html
				$config['newline']  = '\r\n'; // "\r\n" or "\n" or "\r"					
				$this->email->initialize($config);										
				//send parametters
				$this->email->from($this->setting->item['email_from'], $this->setting->item['site_name']);					
				$to = $_SESSION['auth']['email'];
				$this->email->to($to);										
				$this->email->subject($email_subject);
				$this->email->message($email_content);
				$this->email->set_alt_message(nl2br(strip_tags($email_content)));										
				//send mail
				@$this->email->send();			
													
				//logout
				//=============================================================				
				unset($_SESSION['auth']); //unset session							
				$this->load->helper('cookie');	
				if(get_cookie('auth_member_id'))
					delete_cookie('auth_member_id'); //delete cookie					
					
				//set session for display done message in login_page
				//=============================================================
				$_SESSION['password_change_done'] = $this->lang->line('member_change_password_done');

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
		$navigation[1] = array( 'name' 	=>	$this->lang->line('member_change_password'),
								'url'	=>	'',	
							  );
		
		//set meta tags	
		//=============================================================			
		$this->page_title = $this->lang->line('member_change_password');
		$this->page_meta_title = $this->lang->line('member_change_password').' - '.$this->page_meta_title;
							  		
		//send data to view
		//=============================================================
		if(isset($message))			
			$data['message']		= $message;
		$data['navigation'] 		= $navigation;														
		$data['body'] 				= "front/account/change_password";		
		$data 						= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}
	
	function order_history()
	{
		//redirect
		//=============================================================
		if(!isset($_SESSION['auth']))
		{
			header('Location: '.base_url().$this->default_lang_url.'account/login_page');
			die();
		}	
		
		if(isset($_POST["PayMobilpay1"]))
		{
			$this->load->library("order_lib");
			$this->order_lib->do_payment_mobilpay($_POST["order_id"]);
			exit();
		}
				
		//load
		//=============================================================
		$this->load->model('orders_model');	
		//$this->load->library('orders');	
		
		//get member orders			
		//=============================================================
		$where 			= " AND member_id = '".$_SESSION['auth']['member_id']."' ";
		$order_by		= "ORDER BY order_id DESC";
		$orders 		= $this->orders_model->get_orders($where,$order_by,false,false,false);
		
		//set navigation
		//=============================================================
		$navigation = array();		
		$navigation[0] = array( 'name' 	=>	$this->lang->line('home'),
								'url'	=>	base_url().$this->default_lang
							  );	
		$navigation[1] = array( 'name' 	=>	$this->lang->line('order_history'),
								'url'	=>	'',	
							  );
							  
		//set meta tags	
		//=============================================================			
		$this->page_title 		= $this->lang->line("order_history");
		$this->page_meta_title 	= $this->lang->line("order_history");														 
					
		//send data to view	
		//=============================================================		
		$data['orders']		= $orders;
		$data['navigation'] = $navigation;												
		$data['body'] 		= "front/account/order_history";		
		$data 				= array_merge($data, $this->global_variables);
		$this->load->view('front/template',$data);	
	}	
	function tracking_awb($encripted_order_id = false)
	{											
		if($encripted_order_id == false) die(); 
		$data = array();

		$this->page_title 	= "Traking AWB";						

		//get order
		//======================================================================
		$this->load->model('orders_model');			
		$where = "AND MD5(CONCAT(`order_id`,'key')) = '".$encripted_order_id."' ";
		$orders = $this->orders_model->get_orders($where,false,false,false,false);
		if(!$orders) die();
		$order = $orders[0];
		
		//get order firma
		//======================================================================					
		//fan curier + cargus
		$fan_user 			= $this->setting->item("fan_user"); 	
		$fan_parola		 	= $this->setting->item("fan_parola"); 	
		$fan_cod_client 	= $this->setting->item("fan_cod_client");
		$fan_cont 	 		= $this->setting->item("fan_cont");
		$cargus_user 	 	= $this->setting->item("cargus_user");
		$cargus_parola 		= $this->setting->item("cargus_parola");
		$cargus_cod_client  = $this->setting->item("cargus_cod_client");
		$banca				= $this->setting->item("billing_company_bank");
		$cont_bancar		= $this->setting->item("billing_company_bank_account");
			
		$order["firma"]["fan_user"] 		= $fan_user;
		$order["firma"]["fan_parola"] 		= $fan_parola;
		$order["firma"]["fan_cod_client"]	= $fan_cod_client;
		$order["firma"]["fan_cont"] 		= $fan_cont;
		$order["firma"]["cargus_user"] 		= $cargus_user;
		$order["firma"]["cargus_parola"] 	= $cargus_parola;
		$order["firma"]["cargus_cod_client"]= $cargus_cod_client;											
		$order["firma"]["banca"]			= $banca;
		$order["firma"]["cont_bancar"]		= $cont_bancar;		
		
		echo "<h1>".$this->page_title." ".$order["awb"]."</h1>";
		
		if($order["awb"] && $order["awb_tip_generare"] == "fan")
		{
			$this->load->library("fan_courier");			
			$this->fan_courier->tracking_awb($order);
		}
		if($order["awb"] && $order["awb_tip_generare"] == "cargus")
		{
			$this->load->library("cargus");			
			$this->cargus->tracking_awb($order);
		}			
	}
}