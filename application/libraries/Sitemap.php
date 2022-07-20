<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Sitemap
{		
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();		
	}
	
	/**
	 * Google XML sitemap
	 */
	function sitemap_xml_generator()
	{																										
		//get languages active in site
		//=========================================================
		$this->ci->load->model("languages_model");
		$languages = $this->ci->languages_model->get_languages("AND active_site = '1' ", " ORDER BY default_site DESC, `order` ASC" ); 
		
		$base_url = base_url();	
		
		$content_xml = '<?xml version="1.0" encoding="UTF-8"?>
						<?xml-stylesheet type="text/xsl" href="'.$base_url.'uploads/sitemap/sitemap.xsl"?>    
					    <urlset 
						xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
						xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 
						http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" 
						xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
		$content_xml .= '
		<url>
		  <loc>'.$base_url.'</loc>
		  <priority>1.00</priority>
		</url>';
		
		//foreach language
		//=========================================================
		$this->ci->load->model('pages_model');
		$this->ci->load->model('proiecte_model');
		foreach($languages as $language)					
		{									
			//get pages
			//=========================================================
			$where 		= " AND lang_id = ".$language['lang_id']." 
							AND active = '1' AND section != 'home'";
			$orderby	= " ORDER BY `order` ASC ";			
			$pages 		= $this->ci->pages_model->get_pages($where,$orderby);		
			foreach($pages as $page)
			{				
				if($page["section"] == 'home')
					$link = $base_url.$language['code']."/";
				else
					$link = $base_url./*$language['code']."/".*/($page["section"]?$page["section"]:$page["url_key"]);	
						
				$content_xml .= '
						<url>
						  <loc>'.$link.'</loc>
						  <priority>0.90</priority>
						</url>';
			}

			//get proiecte
			//=========================================================
			$where 		= " AND lang_id = ".$language['lang_id']." 
							AND active = '1' ";
			$orderby	= " ORDER BY `order` ASC ";			
			$proiecte 		= $this->ci->proiecte_model->get_proiecte($where,$orderby);		
			foreach($proiecte as $proiect)
			{				
				$link = $base_url./*$language['code']."/".*/$proiect["url_key"];	
						
				$content_xml .= '
						<url>
						  <loc>'.$link.'</loc>
						  <priority>0.80</priority>
						</url>';
			}
			
			//blog_categories
			$this->ci->load->model('blog_categories_model');
			$where 		= " AND lang_id = ".$language["lang_id"]." AND active = '1' ";
			$orderby	= " ORDER BY `order` ASC ";
			$fields 	= "blog_category_name, url_key, `order`";		
			$blog_categories = $this->ci->blog_categories_model->get_blog_categories($where, $orderby, false, false, false, $fields);
			foreach($blog_categories as $blog_category)
			{				
				$link = base_url()."blog/".$blog_category['url_key'];
					
				$content_xml .= '
						<url>
						  <loc>'.$link.'</loc>
						  <priority>0.80</priority>
						</url>';
			}
			
			//blog_articles
			$this->ci->load->model('blog_articles_model');
			$where 		= " AND lang_id = ".$language["lang_id"]." AND active = '1' ";
			$orderby	= " ORDER BY `order` ASC ";
			$fields 	= "blog_article_name, url_key, `order`";		
			$blog_articles = $this->ci->blog_articles_model->get_blog_articles($where, $orderby, false, false, false, $fields);
			foreach($blog_articles as $blog_article)
			{				
				$link = base_url()."blog/articol/".$blog_article['url_key'];
					
				$content_xml .= '
						<url>
						  <loc>'.$link.'</loc>
						  <priority>0.80</priority>
						</url>';
			}
		}			
		$content_xml .= '</urlset>';

		file_put_contents(base_path().'uploads/sitemap/sitemap.xml',$content_xml);
	}
}	