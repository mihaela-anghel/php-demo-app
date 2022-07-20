<?php
if(!defined('BASEPATH')) exit('No direct script acces allowed');
require_once('Base_controller.php');

class Test extends Base_controller  
{			
	function __construct()
	{
		//..						
	}
	
	function index()
	{						
		
	    echo $date = date("Y-m-d 00:00:00");
	    echo "<br>";
	    
 	    // create a $dt object with the America/Denver timezone
        $dt = new DateTime($date, new DateTimeZone('America/Denver'));
        
        // change the timezone of the object without changing it's time
        $dt->setTimezone(new DateTimeZone('UTC'));
        
        // format the datetime
        echo $dt->format('Y-m-d H:i:s');

	}		
}