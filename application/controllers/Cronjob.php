<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

class Cronjob extends Base_controller  
{			
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();								
	}
	function index()
	{
		//genereaza sitemap
		//==============================================
		$this->load->library('sitemap');
		$this->sitemap->sitemap_xml_generator();
	}	
}