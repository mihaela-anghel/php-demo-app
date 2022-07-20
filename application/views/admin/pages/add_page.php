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
$th_width 		= "150";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php }
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } ?>

<form action="" method="post">
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<?php /*<input type="hidden" name="parent_id" value="0"/>*/?>
<table class="form_table" width="<?php echo $table_width?>">  
	<tr style="display:none">
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("page")?>
		</th>
		<td>
			<?php echo form_error("parent_id"); ?>
            <select name="parent_id" class="select">
                <option value="0"><?php echo $this->lang->line('select')?></option>
                <?php	
                foreach($pages_tree as $option)
                {                  
                    ?><option value="<?php echo $option['page_id']?>" <?php echo set_select('parent_id',$option['page_id']); ?>><?php for($i = 0; $i < ($option['level']*5) ; $i++) echo "&nbsp;";  echo $option['name']?></option><?php
                }
                ?>
            </select>
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

<!--FORM FIELDS THAT DEPENDS ON LANGUGES-->
<?php
//shows form validation errors for details field and for each language
foreach($languages as $key=>$language)
{
	echo form_error('name['.$language['lang_id'].']'); 
	echo form_error('abstract['.$language['lang_id'].']'); 
	echo form_error('description['.$language['lang_id'].']');
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
                        <?php echo $this->lang->line("page_name").$label_language?>
                    </th>
                    <td>
                        <input type="text" name="name[<?php echo $language["lang_id"]; ?>]" id="name_<?php echo $language["lang_id"]; ?>" value="<?php echo set_value("name[".$language["lang_id"]."]"); ?>" style="width:600px;"/>*
                    </td>
                </tr>                                             
                <!-- <tr>
                    <th>
                        <?php echo $this->lang->line("page_abstract").$label_language?>
                    </th>
                    <td>
                    	<textarea name="abstract[<?php echo $language["lang_id"]; ?>]" id="abstract_<?php echo $language["lang_id"]; ?>" rows="4" style="width:600px;"><?php echo set_value("abstract[".$language["lang_id"]."]"); ?></textarea>                        						
                    </td>
                </tr>  -->
                <tr>
                    <th>
                        <?php echo $this->lang->line("page_description").$label_language?>
                    </th>
                    <td>
                    	<textarea name="description[<?php echo $language["lang_id"]; ?>]" id="description_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("description[".$language["lang_id"]."]"); ?></textarea>                        						
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