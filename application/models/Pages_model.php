<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Pages_model extends CI_Model 
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		  parent::__construct();	 
	}
	
	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_pages($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "t1.*, t2.*";

		if($groupby == false)	
			$groupby = "";												
		
		$query = "  SELECT ".$fields." 
					FROM pages as t1
					LEFT JOIN pages_details as t2 ON t1.page_id = t2.page_id
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}	
	
	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_just_pages($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";	
						
		$query 	= "  	SELECT ".$fields." 
						FROM pages 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";										
		$results = $this->db->query($query);
		$results =  $results->result_array();
		
		return $results;		
	}
	
	/**
	 * Select data from db
	 * 
	 * @param 	string 	$where
	 * @param 	string 	$orderby
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @param 	string 	$fields
	 * @param 	string 	$groupby
	 * @return 	array
	 */	
	function get_just_pages_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";	
						
		$query   = "	SELECT ".$fields." 
						FROM pages_details 
						WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;	
	}
	
	/**
	 * Insert data
	 * 
	 * @param array $values
	 * @param array $details
	 */
	function add_page($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("pages",$values);
			$page_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("page_id" => $page_id));
			$this->db->update("pages",array("order"=>$page_id));

			//insert details
			if($details !== false)
				$this->add_edit_page_details($details,$page_id);
		}
		if(isset($page_id))
			return $page_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$page_id
	 */
	function edit_page($values = false, $details = false, $page_id = false)
	{
		if($values !== false && $page_id !== false)
		{
			$this->db->where(array("page_id" => $page_id));
			$this->db->update("pages",$values);
		}
		if($details !== false && $page_id !== false)
			$this->add_edit_page_details($details, $page_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$page_id
	 */
	private function add_edit_page_details($details, $page_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM pages_details 
											WHERE page_id = ".$page_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['page_id']	= $page_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("page",0,3).$page_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("pages_details",$values);						
			}
			else 
			{
				$where = array("page_id" => $page_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("pages_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $page_id
	 */
	function delete_page($page_id)
	{
		//delete page
		$this->db->where("page_id",$page_id);
		$this->db->delete("pages");
		
		//delete page_details
		$this->db->where("page_id",$page_id);
		$this->db->delete("pages_details");				
	}

	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_images($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";												
		
		$query = "  SELECT ".$fields." 
					FROM pages_images as t1
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}	
	
	/**
	 * Insert image
	 * 
	 * @param array $values	 
	 */
	function add_image($values = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("pages_images",$values);
			$image_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("image_id" => $image_id));
			$this->db->update("pages_images",array("order"=>$image_id));			
		}
		if(isset($image_id))
			return $image_id;									
	}
	
	/**
	 * Update image
	 * 
	 * @param array $values
	 * @param int 	$image_id
	 */
	function edit_image($values = false, $image_id = false)
	{
		if($values !== false && $image_id !== false)
		{
			$this->db->where(array("image_id" => $image_id));
			$this->db->update("pages_images",$values);
		}		
	}	
		
	/**
	 * Delete image
	 * 
	 * @param int $image_id
	 */
	function delete_image($image_id)
	{		
		//delete image
		$this->db->where("image_id",$image_id);
		$this->db->delete("pages_images");				
	}
	
	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_videos($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";												
		
		$query = "  SELECT ".$fields." 
					FROM pages_videos as t1
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}	
	
	/**
	 * Insert video
	 * 
	 * @param array $values	 
	 */
	function add_video($values = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("pages_videos",$values);
			$video_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("video_id" => $video_id));
			$this->db->update("pages_videos",array("order"=>$video_id));			
		}
		if(isset($video_id))
			return $video_id;									
	}
	
	/**
	 * Update video
	 * 
	 * @param array $values
	 * @param int 	$video_id
	 */
	function edit_video($values = false, $video_id = false)
	{
		if($values !== false && $video_id !== false)
		{
			$this->db->where(array("video_id" => $video_id));
			$this->db->update("pages_videos",$values);
		}		
	}	
		
	/**
	 * Delete video
	 * 
	 * @param int $video_id
	 */
	function delete_video($video_id)
	{		
		//delete video
		$this->db->where("video_id",$video_id);
		$this->db->delete("pages_videos");				
	}
	
	/**
	 * Select data from db
	 * 
	 * @param string 	$where
	 * @param string 	$orderby
	 * @param int 		$limit
	 * @param int 		$offset
	 * @param string 	$fields
	 * @param string 	$groupby
	 * @return array
	 */
	function get_files($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
	{						
		if($where == false)		
			$where = "";
			
		if($orderby == false)	
			$orderby = "";
			
		if($limit!==false && $offset!==false)	
			$limit = " LIMIT ".$offset.",".$limit;	
		else 
			$limit = "";
			
		if($fields == false)	
			$fields = "*";

		if($groupby == false)	
			$groupby = "";												
		
		$query = "  SELECT ".$fields." 
					FROM pages_files as t1
					WHERE 1 ".$where."  ".$groupby."  ".$orderby." ".$limit." ";
										
		$results = $this->db->query($query);
		$results = $results->result_array();
		
		return $results;				
	}	
	
	/**
	 * Insert file
	 * 
	 * @param array $values	 
	 */
	function add_file($values = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("pages_files",$values);
			$file_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("file_id" => $file_id));
			$this->db->update("pages_files",array("order"=>$file_id));			
		}
		if(isset($file_id))
			return $file_id;									
	}
	
	/**
	 * Update file
	 * 
	 * @param array $values
	 * @param int 	$file_id
	 */
	function edit_file($values = false, $file_id = false)
	{
		if($values !== false && $file_id !== false)
		{
			$this->db->where(array("file_id" => $file_id));
			$this->db->update("pages_files",$values);
		}		
	}	
		
	/**
	 * Delete file
	 * 
	 * @param int $file_id
	 */
	function delete_file($file_id)
	{		
		//delete file
		$this->db->where("file_id",$file_id);
		$this->db->delete("pages_files");				
	}		
}	