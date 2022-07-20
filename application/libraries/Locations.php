<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Locations
{		
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();
	}
	
	/**
	 * Get judete
	 * @return array
	 */	
	/*function get_judete()
	{
		$query = $this->ci->db->query(" SELECT * 
										FROM judete
										ORDER BY judet													 												 													 	
									 ");
		return  $query->result_array();			
	}*/
	
	/**
	 * Get judet by id
	 * @param int $judet_id
	 * @return string
	 */
	/*function get_judet_name($judet_id)
	{
		$query = $this->ci->db->query(" SELECT judet
										FROM judete
										WHERE id = ".$judet_id."													 												 													 	
									 ");				
		$judete	 		= $query->result_array();
		$judet		 	= $judete[0];
		return $judet['judet'];
	}*/
	
	/**
	 * Get countries
	 * @return array
	 */	
	function get_countries()
	{
		$query = $this->ci->db->query(" SELECT * 
										FROM countries
										ORDER BY country_name													 												 													 	
									 ");				
		return  $query->result_array();
	}
	
	/**
	 * Get country by id
	 * @param int $country_id
	 * @return string
	 */
	function get_country_name($country_id)
	{
		$query = $this->ci->db->query(" SELECT country_name
										FROM countries
										WHERE country_id = ".$country_id."													 												 													 	
									 ");				
		$countries 		= $query->result_array();
		if($countries)
		{
			$country	 	= $countries[0];
			return $country['country_name'];
		}
		return "";
	}
	
	/**
	 * Get country ISO CODE by id
	 * @param int $country_id
	 * @return string
	 */
	function get_country_isocode($country_id)
	{
		$query = $this->ci->db->query(" SELECT country_iso_code_2
										FROM countries
										WHERE country_id = ".$country_id."													 												 													 	
									 ");				
		$countries 		= $query->result_array();
		$country	 	= $countries[0];
		return $country['country_iso_code_2'];
	}		
}	
