<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('Base_controller.php');

/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Project extends Base_controller 
{	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();									
	}
		
	/**
	 * Listing proiecte
	 * @param string $url_key
	 * @param int $offset
	 */
	public function index($project_number = false)
	{																																
		if(!$project_number)
			show_404();
			
		$where 					= " AND project_number = '".$project_number."' ";									
		$orderby 				= " ORDER BY competitions_participant_id DESC ";		
		$participations			= $this->competitions_model->get_participants($where);		
		if(!$participations)
			show_404();	
		$participation = $participations[0];
		
		//get extention
		$aux 			= explode(".",$participation["project_filename"]);
		$extention 		= end($aux);
		$new_file_name 	= $project_number.".".$extention;
		
		//download file		
		$project_file_url 	= base_url()."uploads/competitions/projects/".$participation["project_filename"];
		$project_file_path 	= base_path()."uploads/competitions/projects/".$participation["project_filename"];
        if($participation["project_filename"] && file_exists($project_file_path))
        {
	        header('Expires: 0');
    		header('Cache-Control: must-revalidate');
    		header('Pragma: public');
        	header('Content-Type: application/octet-stream');
	        header("Content-Transfer-Encoding: Binary"); 
	        header("Content-Disposition: attachment; filename=\"" . $new_file_name . "\"");
	        header('Content-Length: ' . filesize($project_file_path));	        	        	         
	        readfile($project_file_path); 			
        }
        else
        	echo "No file to download";			
	}	
}