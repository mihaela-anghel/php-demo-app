<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author 		Mihaela Anghel <mihaela.anghel@webdesignsoft.ro>
 * @copyright 	Copyright (c) 2009, IXEDO DOTCOM SRL
 * @link 		http://www.webdesignsoft.com; http://www.webdesignsoft.ro; http://www.ixedo.ro;		
 */
class MY_Form_validation extends CI_Form_validation 
{				
	protected $_error_prefix		= '<div class="error">';
	protected $_error_suffix		= '</div>';		
	
	/**
	 * @param string $str
	 */
	function matches_captcha($str)
	{								
		return ($_SESSION['security_code'] !== $str) ? FALSE : TRUE;
	}

	function custom_message_1($str) {	return false;	}
	function custom_message_2($str) {	return false;	}
	function custom_message_3($str) {	return false;	}
	function custom_message_4($str) {	return false;	}
	function custom_message_5($str) {	return false;	}
	
	function recaptcha($str)
	{		
		$google_url="https://www.google.com/recaptcha/api/siteverify";
		$secret='6Lc-I3oUAAAAANJiy18YOXykWkZ7uCEvSM9L8DAa';
		$ip=$_SERVER['REMOTE_ADDR'];
		$url=$google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		curl_setopt($ch, CURLOPT_URL, $url);
		$page_content = curl_exec($ch);
		curl_close($ch);																				
		$res= json_decode($page_content, true);
		//echo "<pre>"; print_r($res); echo "</pre>";
		
		//reCaptcha success check
		if(isset($res['success']) && $res['success'] == 1)
			return TRUE;
		else
		{
			$this->set_message('recaptcha', "The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?");
			return FALSE;
		}						
	}
	
	/**
	 * @param string $str
	 */
	function min_words_count($str, $words_no)
	{								
		return (str_word_count($str) >= $words_no) ? TRUE : FALSE;
	}
	
	/**
	 * Prep URL
	 *
	 * @param	string
	 * @return	string
	 */
	public function prep_capitallise($str = '')
	{
		$str = ucwords(strtolower($str));
	    
		return $str;
	}		
	
	/**
	 * Alpha w/ spaces
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_spaces($str)
	{
		return (bool) preg_match('/^[A-Z ]+$/i', $str);
	}
	
	/**
	 * @param string $str
	 */
	function min_age($birthday, $min_age)
	{								
		$d1 	= new DateTime(date("Y-m-d"));
		$d2 	= new DateTime($birthday);			
		$diff 	= $d2->diff($d1);			
		$age 	= $diff->y;
		
		if($age < $min_age)
		{
		    return FALSE;
		}
		else 
		    return true;
	}
}	
?>
