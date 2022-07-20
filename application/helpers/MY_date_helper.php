<?php
function custom_date($date, $lang_code = "en") 
{				
	if($date != '0000-00-00' && $date != '0000-00-00 00:00:00'  && $date != '')
	{						
		$x		= 	explode(' ',$date);
		
		$date	= 	$x[0];		
		$date	= 	explode('-',$date);			
		$day 	=	$date[2]; 
		$month =	$date[1];
		$year 	= 	$date[0];
		
		if(count($x) == 2)
		{
			$time	= 	$x[1];
			$time	= 	explode(':',$time);
			$hour	=	$time[0];
			$minute = 	$time[1];
			$second =  	$time[2];
		}													
		
		if($lang_code == 'ro')
		{
			$months['ro'] = array(	'01'	=>	'Ianuarie',
									'02'	=>	'Februarie',
									'03'	=>	'Martie',
									'04'	=>	'Aprilie',
									'05'	=>	'Mai',
									'06'	=>	'Iunie',
									'07'	=>	'Iulie',
									'08'	=>	'August',
									'09'	=>	'Septembrie',
									'10'	=>	'Octombrie',
									'11'	=>	'Noiembrie',
									'12'	=>	'Decembrie',
								);	
							
			if(count($x) == 2)
				$new_date = intval($day).' '.$months['ro'][$month].' '.$year. ', '.$hour.':'.$minute;
			else
				$new_date = intval($day).' '.$months['ro'][$month].' '.$year;

			//$new_date = $day.'/'.$month.'/'.$year;					
		}
		else 
		{
			if(count($x) == 2)
				$new_date = date("F j, Y H:i", mktime($hour, $minute, $second, $month, $day, $year));
			else
				$new_date = date("F j, Y", mktime(0, 0, 0, $month, $day, $year));				
		}
			
		return $new_date;
	}
	else return $date;	
} 

function month_format($month, $lang_code = "en") 
{		
	if($month != '00' && $month != '')
	{										
		if($lang_code == 'ro')
		{
			$months['ro'] = array( '01'	=>	'Ianuarie',
									'02'	=>	'Februarie',
									'03'	=>	'Martie',
									'04'	=>	'Aprilie',
									'05'	=>	'Mai',
									'06'	=>	'Iunie',
									'07'	=>	'Iulie',
									'08'	=>	'August',
									'09'	=>	'Septembrie',
									'10'	=>	'Octombrie',
									'11'	=>	'Noiembrie',
									'12'	=>	'Decembrie',
								);														
		
			$new_month = $months['ro'][$month];
		}	
		else
			$new_month = date("F", mktime(0, 0, 0, $month, date('d'), date('Y')));	
			
		return $new_month;
	}
	else return $month;	
}
?>
