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
<table class="form_table" width="<?php echo $table_width?>">  
	<tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("prize_type")?>
		</th>
		<td>
			<?php echo form_error("type");?>
			<?php
			foreach($this->prize_types as $value => $label)
			{
				$selected = ($value==$prize["type"]?true:false);
				
				?>
				<input type="radio" name="type" value="<?php echo $value?>" <?php echo set_radio("type",$value,$selected); ?>/><?php echo $label?>		            
				<?php
			}
			?>
		</td>
	</tr> 
</table>

<!--FORM FIELDS THAT DEPENDS ON LANGUGES-->
<?php
// shows form validation errors for details field and for each language
foreach($languages as $key=>$language)
{
	echo form_error('certificate['.$language['lang_id'].']');
	echo form_error('name['.$language['lang_id'].']'); 
	echo form_error('description['.$language['lang_id'].']'); 
	echo form_error('email_content['.$language['lang_id'].']');
}
// show details fields for each language
foreach($languages as $key=>$language)
{					
	// daca sunt mai multe limbi active afisez limba in dreptul campurilor in mesajele de eroare
	if($show_label_language) 
		$label_language = ' ('.$language['code'].')';
	else
		$label_language = '';	
		
	?><div id="array_div_<?php echo $key?>" style="clear:left; display:<?php if($key == 0) echo 'block'; else echo 'none';?>" ><?php
	
		// daca sunt mai multe limbi active afisez taburile de limbi
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
                    <th width="<?php echo $th_width?>"><?php echo $this->lang->line('prize_certificate').$label_language?>*</th>
                    <td>
                    	<input type="text" name="certificate[<?php echo $language['lang_id']; ?>]" id="certificate_<?php echo $language['lang_id']; ?>" value="<?php echo set_value('certificate['.$language['lang_id'].']', @$prize_details['certificate'][$language['lang_id']]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th width="<?php echo $th_width?>"><?php echo $this->lang->line('prize_name').$label_language?></th>
                    <td>
                    	<input type="text" name="name[<?php echo $language['lang_id']; ?>]" id="name_<?php echo $language['lang_id']; ?>" value="<?php echo set_value('name['.$language['lang_id'].']',@$prize_details['prize_name'][$language['lang_id']]); ?>"/>
                    </td>
                </tr>
				<tr>
                    <th>
                        <?php echo $this->lang->line("prize_email_content").$label_language?>
                    </th>
                    <td height="200">
                    	<textarea name="email_content[<?php echo $language["lang_id"]; ?>]" id="email_content_<?php echo $language["lang_id"]; ?>" rows="4" class="html_textarea" style="width:600px; height:100px"><?php echo set_value("email_content[".$language["lang_id"]."]",(isset($prize_details["email_content"][$language["lang_id"]])?$prize_details["email_content"][$language["lang_id"]]:"")); ?></textarea>                        						
                    </td>
				</tr>   
                <tr style="display:none">
                    <th><?php echo $this->lang->line('prize_description').$label_language?></th>
                    <td>			
                    	<textarea name="description[<?php echo $language['lang_id']; ?>]" id="description_<?php echo $language['lang_id']; ?>" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value('description['.$language['lang_id'].']',@$prize_details['prize_description'][$language['lang_id']]); ?></textarea>
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
            <input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line("save");?>"/>
        </td>
    </tr>
</table>

</form>