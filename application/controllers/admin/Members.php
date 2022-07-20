<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

class Members extends Base_controller 
{										
	function __construct()
	{
		parent::__construct();

		$this->load->model('members_model');	
		$this->lang->load('members',$this->admin_default_lang);
	}
		
	function index($offset = 0)
	{																						
		$section_name = "members"; //numele controllerului
		$where = false;
						
		//SEARCH***********************************************		
		//create countries select
		$this->load->library('locations');
		$countries 	=  $this->locations->get_countries();
		foreach($countries as $country)
			$countries_select[$country['country_id']] = $country['country_name']; 

		$search_by = array	
						(	/*array	(	'field_name'	=> 'keyword',
										'field_label' 	=> 'Keyword',
										'field_type'	=> 'input',
										'field_values'	=> array(),																				
									),*/	
							array	(	'field_name'	=> 'member_id',
										'field_label' 	=> 'ID',
										'field_type'	=> 'input',
										'field_values'	=> array()	
									),
							/*array	(	'field_name'	=> 'username',
										'field_label' 	=> $this->lang->line('member_username'),
										'field_type'	=> 'input',
										'field_values'	=> array()	
									),	*/
							array	(	'field_name'	=> 'name',
										'field_label' 	=> $this->lang->line('member_name'),
										'field_type'	=> 'input',
										'field_values'	=> array()	
									),
							array	(	'field_name'	=> 'company_name',
										'field_label' 	=> $this->lang->line('member_company_name'),
										'field_type'	=> 'input',
										'field_values'	=> array()	
									),								
							array	(	'field_name'	=> 'email',
										'field_label' 	=> $this->lang->line('member_email'),
										'field_type'	=> 'input',
										'field_values'	=> array()	
									),
							/*array	(	'field_name'	=> 'country',
										'field_label' 	=> $this->lang->line('member_country_id'),
										'field_type'	=> 'select',
										'field_values'	=> $countries_select	
									),	*/												
							array	(	'field_name'	=> 'status',
										'field_label' 	=> $this->lang->line('status'),
										'field_type'	=> 'checkbox',
										'field_values'	=> array('0' => $this->lang->line('inactive'), '1' => $this->lang->line('active'))	
									),							
							array	(	'field_name'	=> 'type',
										'field_label' 	=> $this->lang->line('member_type'),
										'field_type'	=> 'checkbox',
										'field_values'	=> array('individual' => $this->lang->line('member_individual'), 'juridical' => $this->lang->line('member_juridical'))	
									),	
							/*array	(	'field_name'	=> 'removed',
										'field_label' 	=> 'Marcati ca sterse',
										'field_type'	=> 'checkbox',
										'field_values'	=> array('1' => $this->lang->line('yes'))																				
									),*/									
							);		
		
		//set search session
		if(isset($_POST['Search']))		
		{
			$_SESSION[$section_name]['search_by'] = $_POST;
			header('Location: '.$this->config->item('admin_url').'members');
		}
			
		//reset search session	
		if(isset($_POST['Reset']) && isset($_SESSION[$section_name]['search_by']))		
		{
			unset($_SESSION[$section_name]['search_by']);
			header('Location: '.$this->config->item('admin_url').'members');			
		}
		
		//create search query		
		if(isset($_SESSION[$section_name]['search_by']))
		{					
			$search = $_SESSION[$section_name]['search_by'];		
			
			//if(isset($search['keyword']) && !empty($search['keyword'])) 
				//$where .= " AND first_name LIKE '%".$search['keyword']."%' ";
				
			if(isset($search['member_id']) && !empty($search['member_id'])) 
				$where .= " AND member_id = '".$search['member_id']."' ";

			if(isset($search['username']) && !empty($search['username'])) 
				$where .= " AND LOWER(username) LIKE '%".strtolower($this->db->escape_like_str($search['username']))."%' ";

			if(isset($search['name']) && !empty($search['name'])) 
				$where .= " AND ( 	LOWER(CONCAT(first_name, ' ', last_name)) LIKE '%".strtolower($this->db->escape_like_str($search['name']))."%' OR 
									LOWER(CONCAT(last_name, ' ', first_name)) LIKE '%".strtolower($this->db->escape_like_str($search['name']))."%'  
								)";
			
			if(isset($search['company_name']) && !empty($search['company_name'])) 
				$where .= " AND LOWER(company_name) LIKE '%".strtolower($this->db->escape_like_str($search['company_name']))."%' ";
							
			if(isset($search['email']) && !empty($search['email'])) 
				$where .= " AND LOWER(email) LIKE '%".strtolower($this->db->escape_like_str($search['email']))."%' ";

			if(isset($search['country']) && !empty($search['country'])) 
				$where .= " AND country_id = '".$search['country']."' ";	
				
			if(isset($search['status']) && !empty($search['status'])) 
			{	
				$where .= " AND ( ";
				if(isset($search['status'][0])) 
					$where .= " active = '".$search['status'][0]."' ";
				if(isset($search['status'][1])) 
					$where .= " OR active = '".$search['status'][1]."' ";	
				$where .= " ) ";									
			}
			
			if(isset($search['type']) && !empty($search['type'])) 
			{	
				$where .= " AND ( ";
				if(isset($search['type'][0])) 
					$where .= " type = '".$search['type'][0]."' ";
				if(isset($search['type'][1])) 
					$where .= " OR type = '".$search['type'][1]."' ";	
				$where .= " ) ";									
			}
			
			if(isset($search['removed']) && !empty($search['removed'])) 			
			{	
				$where .= " AND removed = '1' ";					
			}
			
		}	
		if(!isset($_SESSION[$section_name]['search_by']['removed']) || empty($_SESSION[$section_name]['search_by']['removed'])) 
		$where .= " AND removed = '0'";																				
		//end search*********************************************				
		
		//SORT***************************************************				 	
		$sort_fields 			= array('member_id', 'username', 'first_name', 'company_name' , 'active');
		$default_sort_field 	= 'member_id'; 
		$default_sort_dir 		= 'desc';			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);				
		
		if(isset($_SESSION[$section_name]['sort_field']))		
			$orderby = "ORDER BY `".$_SESSION[$section_name]['sort_field']."` ".$_SESSION[$section_name]['sort_order'];
		else 
			$orderby = "ORDER BY `".$default_sort_field."` ".$default_sort_dir;	
		//end sort***********************************************		
		
		//PAGINATION*********************************************				
		$rows = $this->members_model->get_members($where,false,false,false,"count(member_id) as nr");
		$row = $rows[0];
		$total_rows = $row['nr'];							
		$this->load->library('pagination');		
		$config['base_url'] 	= $this->config->item('admin_url').'members/index';
		$config['total_rows']	= $total_rows;				
		if(isset($_SESSION[$section_name]['per_page']))	
			$config['per_page'] = $_SESSION[$section_name]['per_page'];
		else											
			$config['per_page'] = $this->setting->item('default_admin_number_per_page');							
		$config['uri_segment']	= 3;
		$config['cur_page']		= $offset;
		$config['first_link']	= $this->lang->line('first');		
		$config['last_link'] 	= $this->lang->line('last');									
		$this->pagination->initialize($config);		
		$pagination = $this->pagination->create_links();				
		//end pagination******************************************						

		//get members
		$members = $this->members_model->get_members($where,$orderby,$config['per_page'],$offset,false);								
		foreach($members as $key=>$member)
		{														
			//get country
			$this->load->library('locations');
			$members[$key]['country']	= $this->locations->get_country_name($member['country_id']);

			//get orders number
//			$query 			= " SELECT count(distinct order_id) as total_numbers 
//								FROM orders					
//								WHERE member_id = '".$member["member_id"]."'						
//								";									
//			$result 		= $this->db->query($query);
//			$result 		= $result->result_array();
//			$orders_number 	= $result[0]["total_numbers"];										
//			$members[$key]['orders_number']	= $orders_number;				
		}	
				
		//NUMBER RESULTS DISPLAYED******************************** 
		$display_from 		=	(count($members)) ? $offset+1 : 0; 
		$display_to 		= 	$offset + count($members);
		$display_total 		= 	$total_rows;		
		$results_displayed  = 	$this->lang->line('results').' '.$display_from.' - '.$display_to.' '.$this->lang->line('from').' '.$display_total;
		//end***************************************************** 
				
		//admin acces
		$admin_acces = array();
		$admin_acces['add_member'] 		= $this->global_admin->has_access('right','add_member');
		$admin_acces['edit_member']		= $this->global_admin->has_access('right','edit_member');
		$admin_acces['delete_member']	= $this->global_admin->has_access('right','delete_member');
		
		//send data variables to view
		$data['section_name']		= $section_name;		//controller name
		$data['search_by']			= $search_by; 			//search		
		$data['per_page_select'] 	= $this->global_admin->show_per_page_select($section_name,$config['per_page']); //per_page		
		$data['sort_label']			= $sort_label; 			//sort		
		$data['results_displayed'] 	= $results_displayed;	//number results displayed	
		$data['pagination']			= $pagination;			//pagination		
		$data['members'] 			= $members;
		$data['admin_acces']		= $admin_acces;
		$data['body'] 				= 'admin/members/list_members';
		$this->load->view('admin/template',$data);	
	}	
	function add_member()
	{		
		$data = array();		
		$this->page_title = $this->lang->line('member_add_member');						
		
		if(isset($_POST['Add']))
		{												
			if($this->validate_add_edit_form())
			{				
				
				//inserez in tabela de members
				$fields 	= array(	//'username',
										'password',
										'email',	
										'type',
										'first_name',										
										'last_name',
										'cnp',
										'mobile', 
										'phone',
										'company_name', 
										'company_vat_number', 
										'company_reg_com',
										'company_bank',
										'company_bank_account', 
										'company_phone',
										'company_fax',
										'company_web',
										'company_position', 
										'company_description', 
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code'										
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] 		= $_POST[$field];				
				$values['password'] 			= md5($values['password']);
				$values['registration_date'] 	= date('Y-m-d h:i:s');
				$values['lang_id'] 				= $this->admin_default_lang_id;

				//insert
				$this->members_model->add_member($values);
				
				//subscribe or unsubscribe to newsletter
				/*$this->load->model('newsletter_model');
				if(isset($_POST['newsletter']) && $_POST['newsletter'] == '1')
				{																						
					if($this->newsletter_model->check_exist($_POST['email']))
					{
						if($this->newsletter_model->check_exist($_POST['email'],'1'))
							$this->newsletter_model->edit_subscriber($_POST['email'],array('is_unsubscribed' => '0'));						
					}
					else					
					{	
						$values = array(	'email'		=> $_POST['email'],
											'lang_id'	=> $this->admin_default_lang_id
										);
						$this->newsletter_model->subscribe($values);
					}
				}
				else
				{																						
					if($this->newsletter_model->check_exist($_POST['email']))
					{
						if($this->newsletter_model->check_exist($_POST['email'],'0'))
							$this->newsletter_model->edit_subscriber($_POST['email'],array('is_unsubscribed' => '1'));						
					}					
				}*/
				//end newsletter
				
				//@todo - de trimis mail dupa inregistrare  eventual cu activare cont
				
				header('Location: '.$this->config->item('admin_url').'members');
				die();														
			}									
		}

		$this->load->library('locations');
		$data['countries'] = $this->locations->get_countries();				
		$data['judete'] = $this->locations->get_judete();
		
		$data['body'] = 'admin/members/add_member';
		$this->load->view('admin/template',$data);		
	}	
	function edit_member($member_id = false)
	{		
		if($member_id == false) die(); 		
		$data = array();		
		$this->page_title = $this->lang->line('member_edit_member');						
		
		//get member******************************		
		$where = " AND member_id = ".$member_id." ";
		$member = $this->members_model->get_members($where);
		$member = $member[0];		
		$this->load->model('newsletter_model');
		if($this->newsletter_model->check_exist($member['email'], '0'))
			$member['newsletter'] = '1';
		else
			$member['newsletter'] = '0';
		//end*************************************
		
		if(isset($_POST['Edit']))
		{												
			if($this->validate_add_edit_form())
			{								
				//inserez in tabela de members
				$fields 	= array(	'type',
										'first_name',										
										'last_name',
										'cnp',
										'mobile', 
										//'phone',
										'company_name', 
										'company_vat_number', 
										'company_reg_com',
										'company_bank',
										'company_bank_account', 
										'company_phone',
										//'company_fax',
										//'company_web',
										//'company_position', 
										//'company_description', 
										'address',
										'city',
										'region', 
										'country_id', 
										'postal_code'										
									);	
				
				foreach($fields as $field)
					if(isset($_POST[$field]))
						$values[$field] = $_POST[$field];						

				//update
				$where = array('member_id' => $member_id);							
				$this->members_model->edit_member($values,$where);
				
				//subscribe or unsubscribe to newsletter				
				/*if(isset($_POST['newsletter']) && $_POST['newsletter'] == '1')
				{																						
					if($this->newsletter_model->check_exist($member['email']))
					{
						if($this->newsletter_model->check_exist($member['email'],'1'))
							$this->newsletter_model->edit_subscriber($member['email'],array('is_unsubscribed' => '0'));						
					}
					else					
					{	
						$values = array(	'email'		=> $member['email'],
											'lang_id'	=> $member['lang_id']
										);
						$this->newsletter_model->subscribe($values);
					}					
				}
				else
				{																						
					if($this->newsletter_model->check_exist($member['email']))
					{
						if($this->newsletter_model->check_exist($member['email'],'0'))
							$this->newsletter_model->edit_subscriber($member['email'],array('is_unsubscribed' => '1'));						
					}					
				}*/
				//end newsletter								
				
				//mesaj de update cu succes
				$message = $this->lang->line('member_updated');																		
			}									
		}

		$this->load->library('locations');
		$data['countries'] 	= $this->locations->get_countries();			
		$data['judete'] = $this->locations->get_judete();
		
		if(isset($message))
			$data['message']= $message;	
		$data['member'] 	= $member;
		$data['body'] 		= 'admin/members/edit_member';
		$this->load->view('admin/template',$data);		
	}	
	function delete_member($member_id = false)
	{
		if($member_id == false) die();

		//get member******************************		
		$where = " AND member_id = ".$member_id." ";
		$member = $this->members_model->get_members($where);
		if(!$member) die();
		$member = $member[0];		
		//end*************************************
		
		//get orders number
		$query 			= " SELECT count(distinct order_id) as total_numbers 
							FROM orders					
							WHERE member_id = '".$member["member_id"]."'						
							";									
		$result 		= $this->db->query($query);
		$result 		= $result->result_array();
		$orders_number 	= $result[0]["total_numbers"];										
		if($orders_number > 0)
		{
			//daca are comenzi marchez ca sters
			$where = array('member_id' => $member_id);							
			$this->members_model->edit_member(array("removed" => "1"),$where);
		}
		else
		{
			//delete image
			$this->delete_file_script('image',$member_id);
			
			//delete from db
			$this->members_model->delete_member($member_id);
		}					

		//pt fiecare membru stergs comenzile		
		/*$this->load->model('orders_model');													
		$where 								= " AND member_id = '".$member_id."' ";						
		$comenzi							= $this->orders_model->get_orders($where, false, false, false, false);			
		foreach($comenzi as $comanda)
		{				
			//delete from db
			$this->orders_model->delete_order($comanda['comanda_id']);
		}	*/		
		
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php
	}	
	function change($member_id, $field, $initial_value)
	{
		if($initial_value == '1')
			$new_value = '0';
		else if($initial_value == '0') 
			$new_value = '1';					
			
		$where = array('member_id' => $member_id);	
		$values = array($field => $new_value);		
		$this->members_model->edit_member($values,$where);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
	function change_discount($member_id, $new_discount)
	{
		//update
		$values = array('`discount`' => $new_discount);	
		$where = array('member_id' => $member_id);							
		$this->members_model->edit_member($values,$where);						
		
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php
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
		$this->form_validation->set_rules('phone',					$this->lang->line('member_phone'),					'trim');
		
		if($_POST['type'] == 'juridical')
		{
			$this->form_validation->set_rules('company_name',		$this->lang->line('member_company_name'),			'trim|required');
			$this->form_validation->set_rules('company_vat_number',	$this->lang->line('member_company_vat_number'),		'trim|required');
			$this->form_validation->set_rules('company_reg_com',	$this->lang->line('member_company_reg_com'),		'trim|required');
			$this->form_validation->set_rules('company_bank',		$this->lang->line('member_company_bank'),			'trim');
			$this->form_validation->set_rules('company_bank_account',$this->lang->line('member_company_bank_account'),	'trim');
			$this->form_validation->set_rules('company_phone',		$this->lang->line('member_company_phone'),			'trim');
			$this->form_validation->set_rules('company_fax',		$this->lang->line('member_company_fax'),			'trim');
			$this->form_validation->set_rules('company_web',		$this->lang->line('member_company_web'),			'trim');
			$this->form_validation->set_rules('company_position',	$this->lang->line('member_company_position'),		'trim');
			$this->form_validation->set_rules('company_description',$this->lang->line('member_company_description'),	'trim');
		}
		else
		{						
			$_POST['company_name'] 			= "";
			$_POST['company_vat_number']	= "";
			$_POST['company_reg_com']		= "";
			$_POST['company_bank'] 			= "";
			$_POST['company_bank_account']  = "";
			$_POST['company_phone'] 		= "";
			$_POST['company_fax'] 			= "";
			$_POST['company_web'] 			= "";
			$_POST['company_position'] 		= "";
			$_POST['company_description'] 	= "";					
		}
		$this->form_validation->set_rules('address',				$this->lang->line('member_address'),				'trim');
		$this->form_validation->set_rules('city',					$this->lang->line('member_city'),					'trim|required');
		$this->form_validation->set_rules('region',					$this->lang->line('member_region'),					'trim|required');
		$this->form_validation->set_rules('country_id',				$this->lang->line('member_country_id'),				'trim|required');
		$this->form_validation->set_rules('postal_code',			$this->lang->line('member_postal_code'),			'trim');
		if(isset($_POST['Add']))
		{
			$this->form_validation->set_rules('terms',				$this->lang->line('member_terms'),					'trim|required');
		}
		$this->form_validation->set_rules('newsletter',				$this->lang->line('member_newsletter'),			'trim');
							
		$this->form_validation->set_error_delimiters('<div class="error">','</div>');
		return $this->form_validation->run();	
	}		
	function check_email($email)
	{
		$where = " AND email = '".$this->db->escape_str($email)."' ";
		$members = $this->members_model->get_members($where);
		if(!$members)
			return true;
		else 
		{
			$this->form_validation->set_message('check_email', $this->lang->line('member_check_email'));
			return false;
		}		
	}	
	function check_username($username)
	{
		$where = " AND username = '".$this->db->escape_str($username)."' ";
		$members = $this->members_model->get_members($where);
		if(!$members)
			return true;
		else 
		{
			$this->form_validation->set_message('check_username', $this->lang->line('member_check_username'));
			return false;
		}		
	}	
	function upload_file($type, $id)
	{		
		$data = array();			
		$this->lang->load('upload',$this->admin_default_lang);		
		
		if(isset($_POST['Upload']))
		{																													
			//upload image
			if($type  == 'image') 
			{																					
				//config upload file
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/members/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '1024';
				$config['max_width'] 	= '5000';
				$config['max_height'] 	= '5000';
				$config['file_name'] 	= "member_".$id;
				$config['overwrite'] 	= TRUE;
				
				//load upload library
				$this->load->library('upload', $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload('file'))
				{
					$file_data = $this->upload->data();																								
					
					/*****************************upload image*******************************/					
					//config image
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $config['upload_path'].$file_data['file_name'];
					$config['maintain_ratio']	= TRUE;
					$config['width'] 			= 500;
					$config['height'] 			= 500;
											 											
					//load image manupulation library
					$this->load->library('image_lib');
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();
					
					/*****************************create thumb*******************************/
					//config image
					$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $config['upload_path'].$file_data['file_name'];
					$config['create_thumb'] 	= TRUE;
					$config['thumb_marker'] 	= "_th";
					$config['maintain_ratio'] 	= TRUE;
					$config['width'] 			= 90;
					$config['height'] 			= 90;
																																								
					//load image manupulation library
					$this->load->library('image_lib');					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();	

					//db update			
					$values = array('image' => $file_data['file_name'], 'thumb' => get_thumb_name($file_data['file_name']));
					$where 	= array('member_id' => $id);							
					$this->members_model->edit_member($values,$where);	
					
					?><script type="text/javascript" language="javascript">
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
				}
				else
				{
					//error message
					$data['message']=$this->upload->display_errors('','');						
				}																
			}//end image
						
		}//end post
				
		$this->load->view('admin/members/upload_file',$data);		
	}
	function delete_file($type,$member_id)
	{		
		$this->delete_file_script($type,$member_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php	
	}	
	function delete_file_script($type,$member_id)
	{		
		$where 		= " AND member_id = ".$member_id." ";			
		$fields 	= "*";
		$members 	= $this->members_model->get_members($where,false,false,false,$fields);
		$member 	= $members[0];										
			
		//delete image
		if($type == 'image')
		{
			//delete image
			$image 		= $member['image'];
			$image_path = $this->config->item('base_path').'uploads/members/'.$image;
			if($image && file_exists($image_path))	
				unlink($image_path);
			
			// delete thumb
			$thumb 		= $member['thumb'];
			$thumb_path = $this->config->item('base_path').'uploads/members/'.$thumb;
			if($thumb && file_exists($thumb_path))	
				unlink($thumb_path);	
										
			//db update
			$this->db->where(array('member_id' => $member_id));
			$this->db->update('members',array('image' => '', 'thumb' => ''));
		}				
	}
	function set_search_session($section, $search_field, $search_value)
	{			
		//set search session
		if(isset($_SESSION[$section]['search_by']))
			unset($_SESSION[$section]['search_by']);
			
		$_SESSION[$section]['search_by'][$search_field] = urldecode($search_value);	
		
		header('Location: '.$this->config->item('admin_url').$section);			
	}
	function set_session($section, $type, $parameters = false)
	{
		// $section este numele sectiunii (a controllerului)
		// $type poate lua valorile 'sort', 'per_page' 
		// $parameters este un sir care contine diferiti parametri separati prin -							
		
		// setez sesiunea de sortare		
		if($type == 'sort')
		{						
			$parameters = str_replace("___",".",$parameters);
			$parameters = explode('-',$parameters);			
			$field = $parameters[0];
			$order = $parameters[1];
			
			$_SESSION[$section]['sort_field'] = $field;						
			$_SESSION[$section]['sort_order'] = $order;
			
			?><script type="text/javascript" language="javascript">		
			window.history.go(-1);											
			</script><?php
		}
			
		// setez sesiunea pt items_per_page
		if($type == 'per_page')
		{			
			$per_page = $parameters;						
			$_SESSION[$section]['per_page'] = $per_page;	
			header('Location: '.$this->config->item('admin_url').$section);
		}		
	}			
}