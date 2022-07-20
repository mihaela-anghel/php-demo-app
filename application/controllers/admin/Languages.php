<?php
if(!defined("BASEPATH")) exit("No direct script acces allowed");
require_once("Base_controller.php");

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Languages extends Base_controller 
{			
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		
		$this->load->model("languages_model");
		$this->lang->load("admin/languages",$this->admin_default_lang);

		//set access
		//=========================================================						
		$this->admin_access = parent::set_access(get_class());		
	}	
	
	/**
	 * List languages
	 */
	function index()
	{				
		//controller name
		//======================================================		
		$section_name 	= strtolower(get_class());
		$data			= array();
		$where 			= false; 				

		//sort
		//======================================================				 	
		$sort_fields 			= array("lang_id", "name", "order");
		$default_sort_field 	= "lang_id"; 
		$default_sort_dir 		= "asc";			
				
		$sort_label = $this->global_admin->set_sort_sesssion($sort_fields, $default_sort_field, $default_sort_dir, $section_name);		
		if(isset($_SESSION[$section_name]["sort_field"]))		
			$orderby = "ORDER BY ".$_SESSION[$section_name]["sort_field"]." ".$_SESSION[$section_name]["sort_order"];
		else 
			$orderby = "ORDER BY ".$default_sort_field." ".$default_sort_dir;

		//get list
		//======================================================
		$languages = $this->languages_model->get_languages($where, $orderby);
									
		//send data to view
		//======================================================
		$data["sort_label"]  = $sort_label;
		$data["languages"] 	 = $languages;		
		$data["body"]		 = "admin/languages/list_languages";
		$this->load->view("admin/template",$data);	
	}
	
	/**
	 * Add language
	 */
	function add_language()
	{
		$data = array();
		
		//add form
		//=========================================================
		if(isset($_POST["Add"]))
		{
			//form validation
			$this->load->library("form_validation");
			$this->form_validation->set_rules("name",			$this->lang->line("lang"),			"trim|required");
			$this->form_validation->set_rules("code",			$this->lang->line("code"),			"trim|required|alpha|exact_length[2]");
			$this->form_validation->set_rules("active_site",	$this->lang->line("active_site"),	"trim");			
			$this->form_validation->set_rules("active_admin",	$this->lang->line("active_admin"),	"trim");
			$this->form_validation->set_rules("default_site",	$this->lang->line("default_site"),	"trim");			
			$this->form_validation->set_rules("default_admin",	$this->lang->line("default_admin"),	"trim");					
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();						
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{
				$where_exist		= " AND LOWER(name) = '".strtolower($this->db->escape_str($_POST["name"]))."' ";
				$exist 				= $this->languages_model->get_languages($where_exist);
				
				$where_exist_code	= " AND LOWER(code) = '".strtolower($this->db->escape_str($_POST["code"]))."' ";
				$exist_code 		= $this->languages_model->get_languages($where_exist_code);
								
				if($exist)
					$data["error_message"] = $this->lang->line("lang_exist");
				else if($exist_code)
					$data["error_message"] = $this->lang->line("lang_code_exist");	
				else
				{
					//values
					if(!isset($_POST["active_site"]))	 $_POST["active_site"] 	= "0";
					if(!isset($_POST["active_admin"]))	 $_POST["active_admin"] = "0";
					if(!isset($_POST["default_site"]))	 $_POST["default_site"] = "0";
					if(!isset($_POST["default_admin"]))  $_POST["default_admin"]= "0";
										
					$values = array(	"name"				=> $_POST["name"],
										"code"				=> $_POST["code"],
										"active_site"		=> $_POST["active_site"],
										"active_admin"		=> $_POST["active_admin"],
										"default_site"		=> $_POST["default_site"],
										"default_admin"		=> $_POST["default_admin"],																			
									);
									
					//insert				
					$this->languages_model->add_language($values);
										
					//check translation files
					if(!in_array(strtolower($_POST["code"]), array("en","ro")))
						$this->check_language_dir($_POST["code"],"add");
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_add");					
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}	
			}
		}	

		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("add_lang");
		
		//send data to view
		//=========================================================			
		$data["body"]  = "admin/languages/add_language";
		$this->load->view("admin/template_iframe",$data);			
	}	
	
	/**
	 * Edit language
	 * 
	 * @param int $lang_id
	 */
	function edit_language($lang_id = false)
	{
		$data = array();
		if($lang_id == false) die();
		
		//get language
		//=========================================================
		$langs 	= $this->languages_model->get_languages(" AND lang_id = '".$lang_id."' ");
		if(!$langs) die();
		$lang 		= $langs[0];			
		
		//edit form
		//=========================================================
		if(isset($_POST["Edit"]))
		{
			//form validation
			$this->load->library("form_validation");
			$this->form_validation->set_rules("name",			$this->lang->line("lang"),			"trim|required");
			$this->form_validation->set_rules("code",			$this->lang->line("code"),			"trim|required|alpha|exact_length[2]");
			$this->form_validation->set_rules("active_site",	$this->lang->line("active_site"),	"trim");			
			$this->form_validation->set_rules("active_admin",	$this->lang->line("active_admin"),	"trim");
			$this->form_validation->set_rules("default_site",	$this->lang->line("default_site"),	"trim");			
			$this->form_validation->set_rules("default_admin",	$this->lang->line("default_admin"),	"trim");					
			$this->form_validation->set_error_delimiters('<div class="error">','</div>');
			$form_is_valid = $this->form_validation->run();	
			
			//if form valid
			//=========================================================
			if($form_is_valid)
			{
				$where_exist		= " AND LOWER(name) = '".strtolower($this->db->escape_str($_POST["name"]))."' AND lang_id != '".$lang_id."' ";
				$exist 				= $this->languages_model->get_languages($where_exist);
				
				$where_exist_code	= " AND LOWER(code) = '".strtolower($this->db->escape_str($_POST["code"]))."' AND lang_id != '".$lang_id."' ";
				$exist_code 		= $this->languages_model->get_languages($where_exist_code);
																
				if($exist)
					$data["error_message"] = $this->lang->line("lang_exist");
				else if($exist_code)
					$data["error_message"] = $this->lang->line("lang_code_exist");						
				else
				{
					//values
					if(!isset($_POST["active_site"]))	 $_POST["active_site"] 	= "0";
					if(!isset($_POST["active_admin"]))	 $_POST["active_admin"] = "0";
					if(!isset($_POST["default_site"]))	 $_POST["default_site"] = "0";
					if(!isset($_POST["default_admin"]))  $_POST["default_admin"]= "0";
										
					$values = array(	"name"				=> $_POST["name"],
										"code"				=> $_POST["code"],
										"active_site"		=> $_POST["active_site"],
										"active_admin"		=> $_POST["active_admin"],
										"default_site"		=> $_POST["default_site"],
										"default_admin"		=> $_POST["default_admin"],																			
									);
									
					//update			
					$this->languages_model->edit_language($values,$lang_id);
													
					//check translation files
					if(!in_array(strtolower($_POST["code"]), array("en","ro")))
						$this->check_language_dir($_POST["code"], "edit");
					
					//done message
					$data["done_message"] = $this->lang->line("done_message_edit");					
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}	
			}
		}	
		
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("edit_lang");
		
		//send data to view
		//=========================================================
		$data["lang"]  = $lang;			
		$data["body"]  = "admin/languages/edit_language";
		$this->load->view("admin/template_iframe",$data);				
	}
	
	/**
	 * Check directory language files
	 * 
	 * @param string $lang_code
	 * @param string $action, can be "add" or "edit"
	 */
	private function check_language_dir($lang_code, $action = "add")
	{
		$source_dir_path 	= $this->config->item("base_path")."application/language/".$this->admin_default_lang;		
		$dir_path 			= $this->config->item("base_path")."application/language/".$lang_code;

		//check if directory exists
		if(!file_exists($dir_path))
		{
			//create directory 
			mkdir($dir_path, 0777);
		}	
		else if($action == "add")
		{						
			//delete old directory
			$del = $dir_path; 		
			if ($handle = opendir($del)) 
			{				
				while (false !== ($file = readdir($handle))) 												
					unlink($del."/".$file);				
				closedir($handle);
			}			
			rmdir($del);

			//create directory 
			mkdir($dir_path, 0777);
		}	

		$this->load->helper("directory");
		if(is_empty_folder($dir_path))
		{
			//copy all files to new directory
			if($handle = opendir($source_dir_path)) 
			{				
				while(false !== ($file = readdir($handle))) 
				{					
					if ($file!="" && $file != "." && $file != "..") 
					{
						$source_file	= $source_dir_path."/".$file;
						$new_file 		= $dir_path."/".$file;
						if(is_file($source_file))
						{
							copy($source_file, $new_file);
							chmod($new_file, 0777);
						}						
					}	
				}
				closedir($handle);
			}
		}														
	}
	
	/**
	 * Delete language
	 * 
	 * @param int $lang_id
	 */
	function delete_language($lang_id = false)
	{
		if($lang_id == false) die();
	
		//delete files
		//=========================================================
		$this->delete_file("flag", $lang_id, $no_redirect = true);					
		
		//delete language
		//=========================================================
		$this->languages_model->delete_language($lang_id);

		//redirect	
		//=========================================================
		?><script type="text/javascript" language="javascript">		
		window.history.go(-1);												
		</script><?php
	}	
	
	/**
	 * Change field value
	 * 
	 * @param int 		$lang_id
	 * @param string 	$field
	 * @param mixed 	$new_value
	 */
	function change_language($lang_id, $field, $new_value)
	{																
		if($lang_id == false) die();
		
		$values = array($field => $new_value);									
		$this->languages_model->edit_language($values,$lang_id);
		
		?><script type="text/javascript" language="javascript">
		window.history.go(-1);											
		</script><?php		
	}	
		
	/**
	 * Upload file
	 * 
	 * @param string $type
	 * @param int $lang_id
	 */
	function upload_file($type, $lang_id)
	{		
		$data = array();
		if($lang_id == false) die();
								
		//get language
		//=========================================================
		$langs 	= $this->languages_model->get_languages(" AND lang_id = '".$lang_id."' ");
		if(!$langs) die();
		$lang 		= $langs[0];						
				
		//upload form
		//=========================================================
		$this->lang->load("upload",$this->admin_default_lang);
		if(isset($_POST["Upload"]))
		{						
			//flag file
			if($type == "flag")
			{																				
				//config upload file
				$config["upload_path"] 	= base_path()."uploads/languages/";
				$config["allowed_types"]= "gif|jpg|png";
				$config["max_size"]		= "512";
				$config["max_width"] 	= "2000";
				$config["max_height"] 	= "2000";
				$config["file_name"] 	= $lang["code"];
				$config["overwrite"] 	= TRUE;
				
				//load upload library
				$this->load->library("upload", $config);																														
				
				//if the file has succesfully uploded
				if ($this->upload->do_upload("file"))
				{
					$file_data = $this->upload->data();													
													
					//resize file
					$config["image_library"] 	= "gd2";
					$config["source_image"] 	= $config["upload_path"].$file_data["file_name"];
					$config["maintain_ratio"] 	= TRUE;
					$config["width"] 			= 17;
					$config["height"] 			= 17;
					if($file_data["image_width"] <= $config["width"])
						$config["width"] 		= $file_data["image_width"]; 											
					//load image manupulation library
					$this->load->library("image_lib");
					$this->image_lib->initialize($config);					
					$this->image_lib->resize();								
	
					//update
					$values = array($type => $file_data["file_name"]);
					$this->languages_model->edit_language($values,$lang_id);										
					
					//redirect
					?><script type="text/javascript" language="javascript">									
					parent.jQuery.fancybox.close();
					window.parent.location.reload();											
					</script><?php
					die();
				}
				else
				{
					//error message
					$data["error_message"] = $this->upload->display_errors("","");						
				}					
			}//end flag																								
		}//end post
				
		//page title
		//=========================================================		
		$this->page_title = $this->lang->line("upload_file");
		
		//send data to view
		//=========================================================
		$data["lang"]  = $lang;			
		$data["body"]  = "admin/languages/upload_file";
		$this->load->view("admin/template_iframe",$data);				
	}		
	
	/**
	 * Delete file
	 * 
	 * @param string $type
	 * @param int $lang_id
	 * @param bool $no_redirect
	 */
	function delete_file($type, $lang_id, $no_redirect = false)
	{
		if($lang_id == false) die();
		
		//get language
		//=========================================================
		$langs 	= $this->languages_model->get_languages(" AND lang_id = '".$lang_id."' ");
		if(!$langs) die();
		$lang 		= $langs[0];			
																
		//delete flag
		//=========================================================
		if($type == "flag")
		{
			//delete file
			$file_name	= $lang["flag"];
			$file_path 	= base_path()."uploads/languages/".$file_name;	
			if($file_name && file_exists($file_path))	
				unlink($file_path);

			//db update
			$this->db->where(array("lang_id" => $lang_id));
			$this->db->update("languages",array($type => ""));	
		}

		//redirect
		//=========================================================
		if(!$no_redirect)
		{
			?><script type="text/javascript" language="javascript">
			window.history.go(-1);											
			</script><?php	
		}
	}	
		
	/**
	 * Translation 
	 * 
	 * @param int $lang_id
	 * @param string $file_name
	 */
	function translate($lang_id = false, $file_name = false)
	{
		if($lang_id == false) die();
		
		//get language
		//=========================================================
		$langs 	= $this->languages_model->get_languages(" AND lang_id = '".$lang_id."' ");
		if(!$langs) die();
		$lang 		= $langs[0];		
		
		//get files for translation	
		//=========================================================	
		$files 		= array();
		$dir_path  	= base_path()."application/language/".$lang["code"];									
		if($handle 	= opendir($dir_path)) 
		{				
			while(false !== ($file = readdir($handle))) 
			{									
				if ($file != "." && $file != ".." && $file != "admin") 
				{
					$file1 				= array();
					$file1["file_name"] = $file;
					$file1["file_path"] = $dir_path."/".$file;									
					array_push($files, $file1);
				}				
			}
			closedir($handle);
		}	

		//set file_name for current tranlation
		//=========================================================
		if($file_name == false && count($files) > 0)
			$file_name = $files[0]["file_name"];
		else if($file_name)		
			$file_name = $file_name.".php";		
		else
			$file_name = "";

		//get file_content
		//========================================================		
		$lines 			= array();	
		$file_path 		= base_path()."application/language/".$lang["code"]."/".$file_name;		
		$file_handle 	= fopen($file_path, "rb");
		$i=0;
		while(!feof($file_handle)) 
		{			
			$line_of_text 	= fgets($file_handle);											
			$parts 			= explode("=",$line_of_text,2);					    														
			$line 			= array();			
			if(count($parts) == 2)
			{
				$line["i"] 		= $i;
				$line["nr"]	 	= 2;
				$line["left"] 	= $parts[0];
				$line["right"] 	= $parts[1];				
			}
			else
			{
				$line["i"] 		= $i;
				$line["nr"] 	= 0;
				$line["left"] 	=  $line_of_text;				
			}				
			array_push($lines, $line);
			$i++;								
		}				
		
		//save form
		//========================================================
		if(isset($_POST["Save"]))
		{
			$content = "";
			foreach($lines as $line)
			{
				if($line["nr"]==2)
                {
                    $text 			= trim($line["right"]);
                    $text_without_1 = substr($text,0,-1);
                    $text_without_1 = trim($text_without_1);
                    $first_quote 	= substr($text_without_1, 0,1);
                    $last_quote  	= substr($text_without_1, -1);
                    $text_without_2 = substr($text_without_1,0,-1);
                    $text_without_3 = substr($text_without_2,1);	
                    
                    $_POST["message"][$line["i"]] = strip_tags($_POST["message"][$line["i"]]);
                    $_POST["message"][$line["i"]] = str_replace("\\",'',$_POST["message"][$line["i"]]);
                    
                    if($first_quote == '"')
                    	$_POST["message"][$line["i"]] = str_replace('"','\"',$_POST["message"][$line["i"]]);
                    if($first_quote == "'")
                    	$_POST["message"][$line["i"]] = str_replace("'","\'",$_POST["message"][$line["i"]]);
                	$new_content_line = $line["left"]." = ".$first_quote.$_POST["message"][$line["i"]].$last_quote.";\n";                	
                }
                else                
                   $new_content_line = $line["left"]."";
                
                $content .= $new_content_line;					
			}
			
			//fwrite content
			$this->load->helper("file");
			if(write_file($file_path ,$content))			
				$_SESSION["done_message"] = $this->lang->line("translation_done");			
			else
				$_SESSION["error_message"] = $this->lang->line("translation_error");

			//redirect	
			header("Location: ".current_url());
			die();	
		}
			
		//page title
		//========================================================
		$this->page_title = $this->lang->line("translation");						 
		
		//send data to view
		//========================================================
		$data["lines"] 		= $lines;				
		$data["file_name"] 	= $file_name;
		$data["files"] 		= $files;
		$data["lang"] 		= $lang;
		$data["body"] 		= "admin/languages/translate";
		$this->load->view("admin/template",$data);		
	}
	
	/**
	 * Set session
	 * 
	 * @param string $section 		$section is name of controller
	 * @param string $type			$type can be "sort" or "per_page"
	 * @param string $parameters	$parameters contains multiples parameters separated by "-"	
	 */
	function set_session($section, $type, $parameters = false)
	{		
		// set sort session		
		if($type == "sort")
		{						
			$parameters = str_replace("___",".",$parameters);
			$parameters = explode("-",$parameters);			
			$field = $parameters[0];
			$order = $parameters[1];
			
			$_SESSION[$section]["sort_field"] = $field;						
			$_SESSION[$section]["sort_order"] = $order;
			
			?><script type="text/javascript" language="javascript">		
			window.history.go(-1);											
			</script><?php			
		}
			
		// set items_per_page session
		if($type == "per_page")
		{			
			$per_page = $parameters;						
			$_SESSION[$section]["per_page"] = $per_page;	
			header("Location: ".admin_url().$section);			
		}		
	}
}
