<?php
function generate_password($length = 8)
{
	$password = "";
	$possible = "0123456789abcdefghijklmnopqrstuvwxyz";
	
	$i = 0;
	while ($i < $length) 
	{
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) 
		{
			$password .= $char;
			$i++;
		}
	}
	return $password;
}
function get_thumb_name($file_name)
{
	if($file_name)
	{
		$arr = explode('.',$file_name);
		$thumb_name = $arr[0].'_th';
		return $thumb_name.'.'.$arr[1];
	} 	
}
function add_column_if_not_exist($db, $column, $column_attr = "VARCHAR( 255 ) NOTNULL" )
{
	$CI =& get_instance();
	
	$exists = false;
	$columns = mysqli_query($CI->db->conn_id,"show columns from $db");
	while($c = mysqli_fetch_assoc($columns)){
		if($c['Field'] == $column){
			$exists = true;
			break;
		}
	}
	if(!$exists){
		mysqli_query($CI->db->conn_id,"ALTER TABLE `$db` ADD `$column`  $column_attr");
	}
}
function buildTree($flat, $pidKey, $idKey = null)
{
	/*
	// Example:
	$flat = array(
		array('id'=>100, 'parentID'=>0, 'name'=>'a'),
		array('id'=>101, 'parentID'=>100, 'name'=>'a'),
		array('id'=>102, 'parentID'=>101, 'name'=>'a'),
		array('id'=>103, 'parentID'=>101, 'name'=>'a'),
	);
	
	$tree = buildTree($flat, 'parentID', 'id');
	*/
	
	$grouped = array();
	foreach ($flat as $sub){
		$grouped[$sub[$pidKey]][] = $sub;
	}
 
	$fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
		foreach ($siblings as $k => $sibling) {
			$id = $sibling[$idKey];
			if(isset($grouped[$id])) {
				$sibling['childrens'] = $fnBuilder($grouped[$id]);
			}
			$siblings[$k] = $sibling;
		}

		return $siblings;
	};

	if(isset($grouped[0]))
		$tree = $fnBuilder($grouped[0]);

	if(isset($tree))	
		return $tree;
}

function replace_size($string,$w=false,$h=false)
{		
	if($w)
	{
		$replacement[0]		= 'width="'.$w.'"';
		$pattern[0]			= '|width=\"([a-zA-Z0-9]+)\"|';
	}
	if($h)
	{
		$pattern[1]			= '|height=\"([a-zA-Z0-9]+)\"|';
		$replacement[1]		= 'height="'.$h.'"';
	}					

	if(isset($pattern))
		$new_string	=  preg_replace($pattern, $replacement, $string);
	else
		$new_string = $string;		
	return $new_string;
}

function show_file_name($filename)
{
    $parts  = explode('.', $filename);
    $last   = array_pop($parts);
    $parts  = array(implode('.', $parts), $last);
    
    $filename = $parts[0];
    
    $filename = str_replace( array("_"), array(" "), $filename);
    
    return $filename;
}

function hide_email($email)
{
    return str_replace("@","&#64;", $email);
}
?>
