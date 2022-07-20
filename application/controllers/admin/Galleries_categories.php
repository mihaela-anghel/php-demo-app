<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

class galleries_categories extends Base_controller 
{				
	function __construct()
	{
		parent::__construct();

		$this->load->model('galleries_categories_model');	
		$this->load->library('tree');	
		$this->lang->load('admin/galleries_categories',$this->admin_default_lang);

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());	
	}
	
	function index($offset = 0)
	{																
		$section_name 	= "galleries_categories"; // ( numele controllerului)	
		$where = " AND lang_id = ".$this->admin_default_lang_id." ";													
		
		//SORT*******************************************				 	
		$sort_fields 			= array('t1.galleries_category_id', 't2.galleries_category_name', 't1.order' ,'t1.active');
		$default_sort_field 	= 't1.order'; 
		$default_sort_dir 		= 'asc';			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);				
		
		if(isset($_SESSION[$section_name]['sort_field']))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]['sort_field']." ".$_SESSION[$section_name]['sort_order'];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;	
		//end sort***************************************										
			
		//get galleries_categories	
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby,false,false,false);
		
		//make tree from $galleries_categories
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;
		$galleries_categories = $this->tree->create_tree(0);
		
		foreach($galleries_categories as $key=>$galleries_category)
		{
			//get number of articles
			$this->db->where('galleries_category_id', $galleries_category['galleries_category_id']);
			$this->db->from('galleries');
			$products_number =  $this->db->count_all_results();
			$galleries_categories[$key]['galleries_articles_number'] = $products_number;			
		}					
		
		//send data to view
		$data['section_name']	= $section_name;		
		$data['sort_label'] 	= $sort_label;		
		$data['galleries_categories'] 	= $galleries_categories;
		$data['body'] 			= 'admin/galleries_categories/list_galleries_categories';
		$this->load->view('admin/template',$data);		
	}
	function add_galleries_category()
	{		
		$data = array();
		$this->page_title = $this->lang->line('add_galleries_category');
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		// stabilesc daca afisez limba in dreptul campurilor
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
		
		//daca se face submit la forumar	
		if(isset($_POST['Add']))
		{			
			// form validation			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('parent_id',$this->lang->line('galleries_category'),'trim|required');
			$this->form_validation->set_rules('active',$this->lang->line('status'),'trim|required');
			foreach($languages as $language)
			{
				// daca sunt mai multe limbi active afisez limba in dreptul campurilor in mesajele de eroare
				if($show_label_language) 
					$label_language = ' ('.$language['code'].')';
				else
					$label_language = '';
						
				$this->form_validation->set_rules('name['.$language['lang_id'].']',$this->lang->line('galleries_category_name').$label_language,'trim|required');
				$this->form_validation->set_rules('description['.$language['lang_id'].']',$this->lang->line('galleries_category_description').$label_language,'trim');
				$this->form_validation->set_rules('meta_description['.$language['lang_id'].']',$this->lang->line('meta_description').$label_language,'trim');
				$this->form_validation->set_rules('meta_keywords['.$language['lang_id'].']',$this->lang->line('meta_keywords').$label_language,'trim');					
			}					
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			// end
			
			if($form_is_valid)
			{				
				$where_exist = " AND t2.galleries_category_name = '".$_POST['name'][$this->admin_default_lang_id]."'
								 AND t1.parent_id = ".$_POST['parent_id']." 
								 AND t2.lang_id = ".$this->admin_default_lang_id." 
							   ";					
				$exist = $this->galleries_categories_model->get_galleries_categories($where_exist);				
				
				if(!$exist)
				{
					$values = array(	'parent_id'	=> $_POST['parent_id'],
										'active' 	=> $_POST['active'],
										'add_date'	=> date('Y-m-d h:i:s')
									);
					
					foreach($languages as $language)					
					{
						$url_key[$language['lang_id']] 	= url_key($_POST['name'][$language['lang_id']],'-');						
						$lang_ids[$language['lang_id']]	= $language['lang_id'];					
					}
										
					$details = array(   'galleries_category_name'			=> $_POST['name'],
									    'galleries_category_description'	=> $_POST['description'],
										'meta_description' 		=> $_POST['meta_description'],
										'meta_keywords' 		=> $_POST['meta_keywords'],
										'url_key' 				=> $url_key,										
										'lang_id'				=> $lang_ids	
									);															
					$this->galleries_categories_model->add_galleries_category($values, $details);
					header('Location: '.$this->config->item('admin_url').'galleries_categories');
				}				
				else $data['error_message'] = $this->lang->line('galleries_category_exist');							
			}						
		}
		
		// get galleries_categories for parent select and make tree from $galleries_categories
		$orderby 	= "ORDER BY `order` asc";	
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields 	= " t1.galleries_category_id, t1.parent_id, t2.galleries_category_name ";
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby,false,false,$fields);		
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;
		$galleries_categories = $this->tree->create_tree(0);	
		
		//send data to view
		$data['galleries_categories'] = $galleries_categories;
		$data['show_label_language'] = $show_label_language;
		$data['languages'] = $languages;		
		$data['body'] = 'admin/galleries_categories/add_galleries_category';
		$this->load->view('admin/template',$data);		
	}
	function edit_galleries_category($galleries_category_id = false)
	{		
		if($galleries_category_id == false) die(); 		
		$data = array();						
		$this->page_title = $this->lang->line('edit_galleries_category');
		
		//get languages active in admin
		//=========================================================
		$this->load->model("languages_model");
		$languages = $this->languages_model->get_languages("AND active_admin = '1' ", " ORDER BY default_admin DESC, `order` ASC" ); 
		
		//get galleries_category and galleries_category details******************************		
		$where = array('galleries_category_id' => $galleries_category_id);
		$galleries_category = $this->galleries_categories_model->get_galleries_categories_by('galleries_category_id',$galleries_category_id);
		$galleries_category = $galleries_category[0];
		$array_galleries_category_details  = $this->galleries_categories_model->get_galleries_categories_details_by('galleries_category_id',$galleries_category_id);
		foreach($array_galleries_category_details as $array_galleries_category_detail)				
			foreach($array_galleries_category_detail as $field=>$value)			
				$galleries_category_details[$field][$array_galleries_category_detail['lang_id']] = $value;			
		//end****************************************************	
		
		// stabilesc daca afisez limba in dreptul campurilor
		if(count($languages) > 1) 
			$show_label_language = true;
		else 	
			$show_label_language = false;
		
		// daca se face submit la formular
		if(isset($_POST['Edit']))
		{			
			//begin form validation			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('parent_id',$this->lang->line('galleries_category'),'trim|required');
			$this->form_validation->set_rules('active',$this->lang->line('status'),'trim|required');
			foreach($languages as $language)
			{
				// daca sunt mai multe limbi active afisez limba in dreptul campurilor in mesajele de eroare
				if($show_label_language) 
					$label_language = ' ('.$language['code'].')';
				else
					$label_language = '';
						
				$this->form_validation->set_rules('name['.$language['lang_id'].']',$this->lang->line('galleries_category_name').$label_language,'trim|required');
				$this->form_validation->set_rules('description['.$language['lang_id'].']',$this->lang->line('galleries_category_description').$label_language,'trim');
				$this->form_validation->set_rules('meta_description['.$language['lang_id'].']',$this->lang->line('meta_description').$label_language,'trim');
				$this->form_validation->set_rules('meta_keywords['.$language['lang_id'].']',$this->lang->line('meta_keywords').$label_language,'trim');					
			}						
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();				
			//end form validation
			
			if($form_is_valid)
			{												
				$where_exist = " AND t2.galleries_category_name = '".$_POST['name'][$this->admin_default_lang_id]."'
								 AND t1.parent_id = ".$_POST['parent_id']." 
								 AND t2.lang_id = ".$this->admin_default_lang_id."
								 AND t2.galleries_category_id != ".$galleries_category_id." 
							   ";	
									
				$exist = $this->galleries_categories_model->get_galleries_categories($where_exist);
				
				if(!$exist)
				{
					$values = array(	'active' 	=> $_POST['active'],
										'parent_id'	=> $_POST['parent_id']
									);
					
					foreach($languages as $language)					
					{
						$url_key[$language['lang_id']] 	= url_key($_POST['name'][$language['lang_id']],'-');						
						$lang_ids[$language['lang_id']]	= $language['lang_id'];					
					}
										
					$details = array(   'galleries_category_name' 		=> $_POST['name'],
									    'galleries_category_description' 	=> $_POST['description'],
										'meta_description' 		=> $_POST['meta_description'],
										'meta_keywords' 		=> $_POST['meta_keywords'],
										'url_key' 				=> $url_key,
										'lang_id'				=> $lang_ids										
									);															
					$this->galleries_categories_model->edit_galleries_category($galleries_category_id, $values, $details);
					header('Location: '.$this->config->item('admin_url').'galleries_categories');
				}				
				else $data['error_message'] = $this->lang->line('galleries_category_exist');							
			}						
		}
		
		// get galleries_categories for parent select and make tree from $galleries_categories
		$orderby 	= "ORDER BY `order` asc";	
		$where 		= " AND lang_id = ".$this->admin_default_lang_id." ";	
		$fields 	= " t1.galleries_category_id, t1.parent_id, t2.galleries_category_name ";
		$galleries_categories = $this->galleries_categories_model->get_galleries_categories($where,$orderby,false,false,$fields);		
		$this->tree->id_field_name		  	= 	"galleries_category_id";
		$this->tree->parent_id_field_name 	= 	"parent_id";
		$this->tree->input_array 			=	$galleries_categories;
		$galleries_categories = $this->tree->create_tree(0);	
		
		//send data to view
		$data['galleries_categories'] = $galleries_categories;
		$data['galleries_category'] = $galleries_category;
		$data['galleries_category_details'] = $galleries_category_details;		
		$data['show_label_language'] = $show_label_language;
		$data['languages'] = $languages;		
		$data['body'] = 'admin/galleries_categories/edit_galleries_category';
		$this->load->view('admin/template',$data);		
	}
	function delete_galleries_category($galleries_category_id = false)
	{		
		if($galleries_category_id == false) die(); 
		$this->galleries_categories_model->delete_galleries_category($galleries_category_id);
		?>
		<script type="text/javascript" language="javascript">		
		window.history.go(-1);											
		</script><?php	
	}	
	function change_galleries_category($galleries_category_id, $field, $new_value)
	{
		$values = array($field => $new_value);		
		$this->galleries_categories_model->edit_galleries_category($galleries_category_id, $values);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
	function upload_file($type, $id)
	{		
		$data = array();			
		$this->lang->load('upload',$this->admin_default_lang);		
		
		if(isset($_POST['Upload']))
		{			
			// upload banner
			if($type  == 'banner') 
			{																					
				// config upload file
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/galleries_categories/banners/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '3072';
				$config['max_width'] 	= '5000';
				$config['max_height'] 	= '5000';
				$config['file_name'] 	= 'banner_'.$id;
				$config['overwrite'] 	= TRUE;
				
				//load upload library
				$this->load->library('upload', $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload('file'))
				{
					$file_data = $this->upload->data();													
					
					//config image
					$config['image_library'] = 'gd2';
					$config['source_image'] = $config['upload_path'].$file_data['file_name'];
					$config['maintain_ratio'] = FALSE;
					$config['width'] = 795;
					$config['height'] = 255; // 255	
										
					
					//load image manupulation library
					$this->load->library('image_lib');
					$this->image_lib->initialize($config); 
					$this->image_lib->resize();

					//database update
					$values = array('galleries_category_id' => $id, 'banner' => $file_data['file_name']);
					$this->db->where('galleries_category_id', $id);
					$this->db->update('galleries_categories',$values);	
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
				}
				else
				{
					//error message
					$data['error_message']=$this->upload->display_errors('','');						
				}																
			}//end banner

			// upload image
			if($type  == 'image') 
			{																					
				// config upload file
				$config['upload_path'] 	= $this->config->item('base_path').'uploads/galleries_categories/images/';
				$config['allowed_types']= 'gif|jpg|png';
				$config['max_size']		= '3072';
				$config['max_width'] 	= '5000';
				$config['max_height'] 	= '5000';
				$config['file_name'] 	= 'image_'.$id;
				$config['overwrite'] 	= TRUE;
				
				//load upload library
				$this->load->library('upload', $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload('file'))
				{
					$file_data = $this->upload->data();																								
					
					/*****************************upload image*******************************/					
					//config image
					$config['image_library'] = 'gd2';
					$config['source_image'] = $config['upload_path'].$file_data['file_name'];
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 640;
					$config['height'] = 6400;
					if($file_data['image_width'] <= $config['width'])
					$config['width'] = $file_data['image_width']; 											
					//load image manupulation library
					$this->load->library('image_lib');
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();
					chmod($config['source_image'],0777);
					
					/*****************************create thumb*******************************/
					//config image
					$config['image_library'] = 'gd2';
					$config['source_image'] = $config['upload_path'].$file_data['file_name'];
					$config['create_thumb'] = TRUE;
					$config['thumb_marker'] = "_th";
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 150;
					$config['height'] = 1500;																														
					//load image manupulation library
					$this->load->library('image_lib');					
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					chmod($config['source_image'],0777);

					//database update
					$values = array('galleries_category_id' => $id, 'image' => $file_data['file_name']);
					$this->db->where('galleries_category_id', $id);
					$this->db->update('galleries_categories',$values);	
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
				}
				else
				{
					//error message
					$data['error_message']=$this->upload->display_errors('','');						
				}																
			}//end image
			
		}//end post
						
		//send data to view
		//=========================================================
		$data["type"]	= $type;
		$data["body"]  	= "admin/galleries_categories/upload_file";
		$this->load->view("admin/template_iframe",$data);	
	}
	function delete_file($type,$galleries_category_id)
	{		
		$where = " AND t1.galleries_category_id = ".$galleries_category_id;			
		$fields = "t1.*";
		$galleries_category = $this->galleries_categories_model->get_galleries_categories($where,false,false,false,$fields);
		$galleries_category = $galleries_category[0];
					
		$image = $galleries_category['image'];
		$image_path = $this->config->item('base_path').'uploads/galleries_categories/images/'.$image;
		$thumb = get_thumb_name($image);
		$thumb_path = $this->config->item('base_path').'uploads/galleries_categories/images/'.$thumb;
		
		$banner = $galleries_category['banner'];
		$banner_path = $this->config->item('base_path').'uploads/galleries_categories/banners/'.$banner;
			
		// delete image
		if($type == 'image')
		{
			if($image && file_exists($image_path))	unlink($image_path);
			if($thumb && file_exists($thumb_path))	unlink($thumb_path);	// delete thumb
			$this->db->where(array('galleries_category_id' => $galleries_category_id));
			$this->db->update('galleries_categories',array('image' => ''));
		}
		// delete banner
		if($type == 'banner')
		{
			if($banner && file_exists($banner_path))	unlink($banner_path);
			$this->db->where(array('galleries_category_id' => $galleries_category_id));
			$this->db->update('galleries_categories',array('banner' => ''));
		}
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php	
	}				
	function set_search_session($section, $search_field, $search_value)
	{			
		if(isset($_SESSION[$section]['search_by']))
			unset($_SESSION[$section]['search_by']);
		
		//set search session
		$_SESSION[$section]['search_by'][$search_field] = urldecode($search_value);	
		
		header('Location: '.$this->config->item('admin_url').$section);			
	}
	function set_session($section, $type, $parameters = false)
	{
		// $section este numele sectiunii (a controllerului)
		// $type poate lua valorile 'sort', 'per_galleries_category' 
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
			
		// setez sesiunea pt items_per_galleries_category
		if($type == 'per_page')
		{			
			$per_galleries_category = $parameters;						
			$_SESSION[$section]['per_page'] = $per_galleries_category;	
			header('Location: '.$this->config->item('admin_url').$section);
		}		
	}
}
