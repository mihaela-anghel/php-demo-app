<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Articles_model extends CI_Model 
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
	function get_articles($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
					FROM articles as t1
					LEFT JOIN articles_details as t2 ON t1.article_id = t2.article_id
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
	function get_just_articles($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM articles 
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
	function get_just_articles_details($where = false, $orderby = false, $limit = false, $offset = false, $fields = false, $groupby = false)
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
						FROM articles_details 
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
	function add_article($values = false, $details = false)
	{
		if($values !== false)		
		{
			//insert
			$this->db->insert("articles",$values);
			$article_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("article_id" => $article_id));
			$this->db->update("articles",array("order"=>$article_id));

			//insert details
			if($details !== false)
				$this->add_edit_article_details($details,$article_id);
		}
		if(isset($article_id))
			return $article_id;									
	}
	
	/**
	 * Updata data
	 * 
	 * @param array $values
	 * @param array $details
	 * @param int 	$article_id
	 */
	function edit_article($values = false, $details = false, $article_id = false)
	{
		if($values !== false && $article_id !== false)
		{
			$this->db->where(array("article_id" => $article_id));
			$this->db->update("articles",$values);
		}
		if($details !== false && $article_id !== false)
			$this->add_edit_article_details($details, $article_id);		
	}	
	
	/**
	 * Foreach language insert/update details
	 * 
	 * @param array $details
	 * @param int 	$article_id
	 */
	private function add_edit_article_details($details, $article_id)
	{
		//foreach lang
		foreach($details['lang_id'] as $lang_id)
		{
			$query	=	$this->db->query("	SELECT * FROM articles_details 
											WHERE article_id = ".$article_id." 
											AND lang_id = ".$lang_id." "); 
			$exista	=	$query->row_array();
			
			//foreach field
			$values 			= array();
			$values['article_id']	= $article_id;
			foreach($details as $field => $detail)			
				$values[$field] = $detail[$lang_id];						

			//concat id to url_key	
			$values['url_key'] = $values['url_key'].'-'.substr("article",0,20).$article_id;
			
			if(!$exista)
			{	
				if(!isset($values['add_date']))
					$values['add_date'] = date("Y-m-d H:i:s");
				$this->db->insert("articles_details",$values);						
			}
			else 
			{
				$where = array("article_id" => $article_id, "lang_id" => $lang_id);
				$this->db->where($where);
				$this->db->update("articles_details",$values);
			}							
		}
	}	
	
	/**
	 * Delete data
	 * 
	 * @param int $article_id
	 */
	function delete_article($article_id)
	{
		//delete article
		$this->db->where("article_id",$article_id);
		$this->db->delete("articles");
		
		//delete article_details
		$this->db->where("article_id",$article_id);
		$this->db->delete("articles_details");		
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
					FROM articles_images as t1
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
			$this->db->insert("articles_images",$values);
			$image_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("image_id" => $image_id));
			$this->db->update("articles_images",array("order"=>$image_id));			
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
			$this->db->update("articles_images",$values);
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
		$this->db->delete("articles_images");				
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
					FROM articles_videos as t1
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
			$this->db->insert("articles_videos",$values);
			$video_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("video_id" => $video_id));
			$this->db->update("articles_videos",array("order"=>$video_id));			
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
			$this->db->update("articles_videos",$values);
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
		$this->db->delete("articles_videos");				
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
					FROM articles_files as t1
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
			$this->db->insert("articles_files",$values);
			$file_id = $this->db->insert_id();
			
			//update order
			$this->db->where(array("file_id" => $file_id));
			$this->db->update("articles_files",array("order"=>$file_id));			
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
			$this->db->update("articles_files",$values);
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
		$this->db->delete("articles_files");				
	}
}	