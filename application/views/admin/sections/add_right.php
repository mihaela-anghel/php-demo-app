<!--MAKE AN JAVASCRIPT ARRAY WITH ALL DIVS THAT WE MUST HIDE (LANGUAGE TABS) -->
<script type="text/javascript" language="javascript"><?php
if($languages)
{
	?>var array_divs = new Array();<?php	
	foreach ($languages as $key=>$language)
	{
		?>array_divs[<?php echo $key; ?>]="array_div_"+"<?php echo $key; ?>";<?php 
	}
}
?></script><?php 

//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "150";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<form action="" method="post">
<!--FORM FIELDS THAT DEPENDS ON LANGUGES-->
<?php
//shows form validation errors for details field and for each language
foreach($languages as $key=>$language)
{
	echo form_error("right_name[".$language["lang_id"]."]"); 	
}
//show details fields for each language
foreach($languages as $key=>$language)
{			
	//if we have more than one language, then display lang code label
	if($show_label_language) 
		$label_language = " (".$language["code"].")";
	else
		$label_language = "";			
	?>
    <div id="array_div_<?php echo $key?>" style="clear:left; display:<?php if($key == 0) echo "block"; else echo "none";?>" >	
		<?php			
		//if we have more than one language, then display lang"s tab
		if($show_label_language) 
		{
			foreach($languages as $key_=>$lang)
			{				
				?><div class="<?php if($lang["lang_id"] == $language["lang_id"]) echo "tab_on"; else echo "tab_off";?>" onClick="show_div(array_divs,'array_div_<?php echo $key_?>')"><?php echo $lang["code"]?></div><?php
			}
		}	
		?>		
		<div style="clear:left;">
		<table class="form_table tab" width="<?php echo $table_width?>">
			<tr>
				<th width="<?php echo $th_width?>">
					<?php echo $this->lang->line("right_name").$label_language?>
				</th>
				<td>
                	<input type="text" name="right_name[<?php echo $language["lang_id"]; ?>]" id="right_name_<?php echo $language["lang_id"]; ?>" value="<?php echo set_value("right_name[".$language["lang_id"]."]"); ?>"/>*
				</td>
			</tr> 						
		</table>
		</div> 
	</div> 
	<?php
}
?>
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>">  
	<tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("right_url")?>
		</th>
		<td>
			<?php echo form_error("right_url"); ?>
            <?php 
			$class_methods 		= array();
			$admin_section_url 	= base_path()."application/controllers/admin/".$section["admin_section_url"].".php";
			if(file_exists($admin_section_url))
			{
            	require_once($admin_section_url);
            	$class_methods = get_class_methods($section["admin_section_url"]);		
			}
            ?>
            <select name="right_url">
            <?php
            $min_key = array_search('__construct', $class_methods);
            $max_key = array_search('get_instance', $class_methods);
            foreach($class_methods as $key=>$value)
            {
                if($key > $min_key && $key < $max_key)
                {
                    if($key == "0") $selected = true; else $selected = false;
                    ?><option value="<?php echo $value?>" <?php echo set_select("right_url",$value,$selected); ?>><?php echo $value?></option><?php
                }
            }
            ?>
            </select>*
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("order")?>
		</th>
		<td>
        	<input type="text" name="order" id="order" value="<?php echo set_value("order"); ?>" style="width:30px;"/>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("status")?>
		</th>
		<td>
			<?php
			$active = array(	"0" => $this->lang->line("inactive"),
								"1" => $this->lang->line("active")	);
			?>
			<select name="active">
			<?php
			foreach($active as $value=>$label)
			{
				if($value == "1") $selected = true; else $selected = false;
				?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$selected); ?>><?php echo $label?></option><?php
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<th></th>
		<td>
        	<input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line("add");?>"/>
		</td>
	</tr>
</table>
</form>
