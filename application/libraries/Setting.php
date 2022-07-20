<?php
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class Setting
{			
	var $item;
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->ci =& get_instance();
		
		$this->item = $this->get_settings();
	}
	
	/**
	 * Return setting value
	 * 
	 * @param string $setting_name
	 * @return string
	 */
	function item($setting_name)
	{
		$aux = false;
		foreach($this->item as $name => $value)				
			if( $name == $setting_name)	
			{							
				$aux = true;
				break;
			}								

		if($aux && isset($value))
			return $value;
		else
			echo "Setting ".$setting_name." not found";	
	}
	
	/**
	 * Get all settings
	 * 
	 * @return array;
	 */
	private function get_settings()
	{
		$lang_id = 0;
		if(isset($this->ci->admin_default_lang_id))
			$lang_id = $this->ci->admin_default_lang_id;
		else if(isset($this->ci->default_lang_id))
			$lang_id = $this->ci->default_lang_id;
							
		// get settings
		$this->ci->load->model("settings_model");
		$where 		= "AND (id IS NULL OR lang_id = '".$lang_id."' )";
		$fields 	= "name, IF(is_multilanguage = '0', t1.value, t2.value) as value";
		$settings 	= $this->ci->settings_model->get_settings($where, false, false, false, $fields);
		
		$output = array();
		foreach($settings as $setting)		
			$output[$setting["name"]] = $setting["value"];					
		
		return $output;			
	}
}	
