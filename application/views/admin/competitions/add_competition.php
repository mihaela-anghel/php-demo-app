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
?></script>

<!--TINYMCE-->
<script type="text/javascript" src="<?php echo base_url();?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>tinymce/init.js"></script>

<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "200";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php }
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } ?>

<form action="" method="post">
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>">
	<tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("competition_type")?>
		</th>
		<td>
			<?php echo form_error("type");?>
			<?php
			foreach($this->competition_types as $value => $label)
			{
				$selected = ($label==current($this->competition_types)?true:false);
				
				?>
				<input type="radio" name="type" value="<?php echo $value?>" <?php echo set_radio("type",$value,$selected); ?>/><?php echo $label?>		            
				<?php
			}
			?>
		</td>
	</tr>	
	<tr>
		<th>
			<?php echo $this->lang->line("competition_age_category")?>
		</th>
		<td>
			<?php echo form_error("age_category_ids[]");?>
			<?php
			foreach($age_categories as $age_category)
			{
				$checked = true;
				?>
				<span style="margin-right:20px">
					<input type="checkbox" name="age_category_ids[]" value="<?php echo $age_category["age_category_id"]?>" <?php echo set_checkbox("age_category_ids[]", $age_category["age_category_id"], $checked); ?>/>
					<?php echo $age_category["age_category_name"]?>
				</span>
				<?php
			}
			?>						            
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_category")?>
		</th>
		<td>
			<?php echo form_error("category_ids[]");?>
			<?php
			foreach($categories as $category)
			{
				$checked = false;
				?>
				<span style="margin-right:20px">
					<input type="checkbox" name="category_ids[]" value="<?php echo $category["category_id"]?>" <?php echo set_checkbox("category_ids[]", $category["category_id"], $checked); ?>/>
					<?php echo $category["category_name"]?>
				</span>
				<?php
			}
			?>						            
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_start_registration_date")?>
		</th>
		<td>
			<?php echo form_error("start_registration_date");?>
			<?php echo form_error("end_registration_date");?>

			<input type="text" name="start_registration_date" value="<?php echo set_value("start_registration_date"); ?>" readonly="readonly" style="width:100px"/>*
			
			&nbsp;&nbsp;&nbsp;
			<?php echo $this->lang->line("competition_end_registration_date")?>			
			<input type="text" name="end_registration_date" value="<?php echo set_value("end_registration_date"); ?>" readonly="readonly" style="width:100px"/>*						
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_show_results_date")?>			
		</th>
		<td>
			<?php //echo form_error("end_submit_project_date");?>
			<?php echo form_error("show_results_date");?>
			
			<input type="text" name="show_results_date" value="<?php echo set_value("show_results_date"); ?>" readonly="readonly" style="width:100px"/>*						

			<?php
			/*
			&nbsp;&nbsp;&nbsp;			
			<?php echo $this->lang->line("competition_end_submit_project_date")?>
			<input type="text" name="end_submit_project_date" value="<?php echo set_value("end_submit_project_date"); ?>" readonly="readonly" style="width:100px"/>*			
			*/?>
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_default_count_participants")?>
		</th>
		<td>
			<?php echo form_error("default_count_participants");?>
			<input type="text" name="default_count_participants" value="<?php echo set_value("default_count_participants"); ?>" style="width:100px"/>*												
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_default_count_schools")?>
		</th>
		<td>
			<?php echo form_error("default_count_schools");?>
			<input type="text" name="default_count_schools" value="<?php echo set_value("default_count_schools"); ?>" style="width:100px"/>*												
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("competition_default_count_countries")?>
		</th>
		<td>
			<?php echo form_error("default_count_countries");?>
			<input type="text" name="default_count_countries" value="<?php echo set_value("default_count_countries"); ?>" style="width:100px"/>*												
		</td>
	</tr>	
    <tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("competition_popup_info_active")?>
		</th>
		<td>
			<?php echo form_error("popup_info_active");?>
			<?php
            $options = array("1" => $this->lang->line("yes"), "0" => $this->lang->line("no"));
			foreach($options as $value => $label)
			{
				$selected = ($label==current($options)?true:false);
				
				?>
				<input type="radio" name="popup_info_active" value="<?php echo $value?>" <?php echo set_radio("popup_info_active",$value,$selected); ?>/><?php echo $label?>		            
				<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("status")?>
		</th>
		<td>
			<?php
			echo form_error("active");
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
</table>
<script type="text/javascript" language="javascript">
	$(document).ready(function(){
		$('input[name="start_registration_date"], input[name="end_registration_date"], input[name="end_submit_project_date"], input[name="show_results_date"]').attachDatepicker({ 
			rangeSelect: false, firstDay: 1, dateFormat: 'yy-mm-dd' 
		}); 
	});
</script> 

<!--FORM FIELDS THAT DEPENDS ON LANGUGES-->
<?php
//shows form validation errors for details field and for each language
foreach($languages as $key=>$language)
{
	echo form_error('name['.$language['lang_id'].']'); 
	echo form_error('description['.$language['lang_id'].']');
	echo form_error('theme_name['.$language['lang_id'].']'); 
	echo form_error('theme_description['.$language['lang_id'].']');
	echo form_error('rules['.$language['lang_id'].']');
	echo form_error('code_language['.$language['lang_id'].']');
	echo form_error('email_content['.$language['lang_id'].']');
    echo form_error('popup_info['.$language['lang_id'].']');
	echo form_error('meta_title['.$language['lang_id'].']');
	echo form_error('meta_description['.$language['lang_id'].']');
	echo form_error('meta_keywords['.$language['lang_id'].']'); 
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
				?><div class="<?php if($lang["lang_id"] == $language["lang_id"]) echo "tab_on"; else echo "tab_off";?>" onclick="show_div(array_divs,'array_div_<?php echo $key_?>')"><?php echo $lang["code"]?></div><?php
			}
		}	
		?>		
		<div style="clear:left;">
            <table class="form_table tab" width="<?php echo $table_width?>">
                <tr>
                    <th width="<?php echo $th_width?>">
                        <?php echo $this->lang->line("competition_name").$label_language?>
                    </th>
                    <td>
                        <input type="text" name="name[<?php echo $language["lang_id"]; ?>]" id="name_<?php echo $language["lang_id"]; ?>" value="<?php echo set_value("name[".$language["lang_id"]."]"); ?>" style="width:600px;"/>*
                    </td>
                </tr>                                 			
                <tr>
                    <th>
                        <?php echo $this->lang->line("competition_description").$label_language?>
                    </th>
                    <td>
                    	<textarea name="description[<?php echo $language["lang_id"]; ?>]" id="description_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("description[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr> 
				<tr>
                    <th>
                        <?php echo $this->lang->line("competition_theme_name").$label_language?>
                    </th>
                    <td>
                        <input type="text" name="theme_name[<?php echo $language["lang_id"]; ?>]" id="theme_name_<?php echo $language["lang_id"]; ?>" value="<?php echo set_value("theme_name[".$language["lang_id"]."]"); ?>" style="width:600px;"/>*
                    </td>
                </tr>
				<tr>
                    <th>
                        <?php echo $this->lang->line("competition_theme_description").$label_language?>
                    </th>
                    <td>
                    	<textarea name="theme_description[<?php echo $language["lang_id"]; ?>]" id="theme_description_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px;"><?php echo set_value("theme_description[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr> 
				<tr>
                    <th>
                        <?php echo $this->lang->line("competition_code_language").$label_language?>
                    </th>
                    <td>
                    	<textarea name="code_language[<?php echo $language["lang_id"]; ?>]" id="code_language_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:100px"><?php echo set_value("code_language[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr>	
				<tr>
                    <th>
                        <?php echo $this->lang->line("competition_rules").$label_language?>
                    </th>
                    <td>
                    	<textarea name="rules[<?php echo $language["lang_id"]; ?>]" id="rules_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("rules[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr>
				<tr>
                    <th>
                        <?php echo $this->lang->line("competition_email_content").$label_language?>
                    </th>
                    <td>
                    	<textarea name="email_content[<?php echo $language["lang_id"]; ?>]" id="email_content_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("email_content[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr>
                <tr>
                    <th>
                        <?php echo $this->lang->line("competition_popup_info").$label_language?>
                    </th>
                    <td>
                    	<textarea name="popup_info[<?php echo $language["lang_id"]; ?>]" id="popup_info_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("popup_info[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
				</tr>
                <tr>
                    <th>
                        <?php echo $this->lang->line("meta_title").$label_language?>
                    </th>
                    <td>
                        <input type="text" name="meta_title[<?php echo $language["lang_id"]; ?>]" id="meta_title_<?php echo $language["lang_id"]; ?>" value="<?php echo set_value("meta_title[".$language["lang_id"]."]"); ?>" style="width:600px;"/>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo $this->lang->line("meta_description").$label_language?>
                    </th>
                    <td>
                    	<textarea name="meta_description[<?php echo $language["lang_id"]; ?>]" id="meta_description_<?php echo $language["lang_id"]; ?>" rows="4" style="width:600px;"><?php echo set_value("meta_description[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo $this->lang->line("meta_keywords").$label_language?>
                    </th>
                    <td>
                    	<textarea name="meta_keywords[<?php echo $language["lang_id"]; ?>]" id="meta_keywords_<?php echo $language["lang_id"]; ?>" rows="4" style="width:600px;"><?php echo set_value("meta_keywords[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
                </tr>					
            </table>
		</div> 
	</div> 
	<?php
}
?>
  
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
        <th width="<?php echo $th_width?>">
        <td>
            <input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line("add");?>"/>
        </td>
    </tr>
</table>
</form>