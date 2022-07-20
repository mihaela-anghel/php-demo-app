<?php
	function display_search_fragment_from_text($str, $searchTerms, $maxChar)
	{								
		//display the first fragment of text from $str that contain words or expresions from $searchTerms
		$len = count($searchTerms);
		$chunk = '';
		for ($i = 0; $i < $len; $i++)
		{
			if (preg_match("/$searchTerms[$i]/",$str))
			{
				$pos = strpos ($str,$searchTerms[$i]);
				if (($pos - ($maxChar/2)) < 0)
				{
				 	$startPos = 0;
				}
				else
				{
					$startPos = ($pos - ($maxChar/2));
					$chunk .= '... ';
				}
				
				$chunk .= substr($str,$startPos,$maxChar);
				
				if (($pos + ($maxChar/2)) < strlen($str))
				{
					$chunk .= ' ...';
				}
				break;
			}
		}
		
		if($chunk == '')
		{
			$chunk = substr($str,0,$maxChar).' ...';
		}			
		return $chunk;
	}		
	function natural_combi_words($phrase)
	{
		//return an array with all combinations of words from a given text or expresion
		$exclude = array('de','la','de le', 'pana la', 'si', 'si la', 'peste', 'de le', 'in');
		
		$arw = array();
		$words = explode(' ',trim($phrase));
		$nw = count ($words);
		for ($i=0; $i<$nw; $i++)
		{
			$k = '';
			$cnt = 0;
			for ($j=$i; $j<($nw); $j++)
			{
				$k .= $words[$j].' ';
				$arw[$cnt][] = trim($k);
				$cnt++;
			}
		}
		$combi_source = array_reverse($arw);
		
		$combi_words = $combi_source[0];
		$nw = count ($combi_source);
		for ($i=1; $i<$nw; $i++)						
		$combi_words = array_merge($combi_words, $combi_source[$i]);		
		
		//print_r($combi_words);
		$output = array();		
		foreach($combi_words as $combi_word)
		{
			if(!in_array($combi_word,$exclude))
				array_push($output,$combi_word);
		}		
		return $output;
	}	
	
	function permute($str) 
	{
		/* If we only have a single character, return it */
		if (strlen($str) < 2) 
		{
			return array($str);
		}
	 
		/* Initialize the return value */
		$permutations = array();
	 
		/* Copy the string except for the first character */
		$tail = substr($str, 1);
	 
		/* Loop through the permutations of the substring created above */
		foreach (permute($tail) as $permutation) 
		{
			/* Get the length of the current permutation */
			$length = strlen($permutation);
	 
			/* Loop through the permutation and insert the first character of the original
			string between the two parts and store it in the result array */
			for ($i = 0; $i <= $length; $i++) 
			{
				$permutations[] = substr($permutation, 0, $i) . $str[0] . substr($permutation, $i);
			}
		}
	 
		/* Return the result */
		return $permutations;
	}	
	function wordperms($phrase,$top='') 
	{
        $output = array();
       
        if (is_array($phrase)) 
        {
             $phrase_pieces = $phrase;
        }
        else 
        {
             $phrase_pieces = explode(' ',$phrase);
        }
        $top_pieces = explode(' ',$top);
        foreach ($phrase_pieces as $piece) 
        {
                if (!in_array($top.$piece,$output) && !in_array($piece,$top_pieces) ) 
                {
                        $output[] = $top.$piece;                                                                        
                        $output = array_merge($output,wordperms($phrase_pieces,$top.$piece.' '));
                }
        }
       return $output;                     
	}				
?>
