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

<!--SUBMENU-->
<ul class="submenu">	
	<?php	
    if($this->access['add_setting'])		
	{
		?>
        <li>
        	<a href="<?php echo admin_url()?>settings/add_setting" class="fancybox_iframe" rel="500,350">
				<?php echo $this->lang->line('add_setting'); ?>
			</a>
		</li>
		<?php
    }
	?>
</ul>

<?php 
//LOAD FORM HELPER
$this->load->helper('form');
$th_width 		= "400";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($_SESSION['done_message']))  { ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) { ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } ?>

<!--LISTING-->
<form action="" method="post">
<?php

//settings
if(isset($settings) && $settings)
{
	?>
	<table class="form_table" width="<?php echo $table_width?>">  
	<?php
	foreach($settings as $setting)
	{
		?>
		<tr>
			<th width="<?php echo $th_width?>" style="text-align:left">
				<?php if($setting['type'] != "checkbox") echo $setting['description']?>
			</th>
			<td>
				<?php 
				echo form_error('value['.$setting['setting_id'].']'); 
				
				if($setting['type'] == "textarea")
				{
					?><textarea name="value[<?php echo $setting['setting_id']?>]" <?php if($setting["html_textarea"]=="1") { ?>class="html_textarea" <?php } ?> style="width:300px;" rows="4"><?php echo $setting['value']?></textarea><?php
				}
				elseif($setting['type'] == "checkbox")
				{
					?>
                    <input type="checkbox" name="value[<?php echo $setting['setting_id']?>]" value="<?php echo $setting['value']?>" <?php if($setting['value']=="1") echo 'checked="checked"';?> onchange="if($(this).is(':checked')) $(this).val(1); else $(this).val(0); "/>
					<?php echo $setting['description']?>
					<?php
				}				
				elseif($setting['type'] == "input")
				{
					?><input type="text" name="value[<?php echo $setting['setting_id']?>]" value="<?php echo $setting['value']?>" style="width:300px;" /><?php
				}
				
				if($setting['name'] == "sort_resellers_by")
				{
					$options = array(	"custom_order" 	=> "Custom order",
										"total_credits" => "Total credits number",
										"spent_credits" => "Spent credits number",		
									);
					foreach($options as $option => $label)				
					{
						?>
						<input type="radio" name="value[<?php echo $setting['setting_id']?>]" value="<?php echo $option?>" <?php if($setting['value']==$option) echo 'checked="checked"';?>/>
						<?php echo $label?>
						<?php
					}
				}
				
				if($this->access['add_setting'])	
				{
					?>
					<input type="text" size="2" value="<?php echo $setting['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>settings/change_setting/<?php echo $setting['setting_id'] ?>/order/'+this.value" />					
                    
					<?php	
					//active			
					$aux = array(	"field" 	=> "active",
									"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
									"values" 	=> array(1, 0),
									"classes" 	=> array("access", "noaccess"),
									"url" 		=> $this->config->item("admin_url")."settings/change_setting/".$setting["setting_id"]	
									);				
					?>              
					<span class="hide"><?php echo json_encode($aux)?></span>					
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($setting[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
						<?php echo ($setting[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                	</a>  
                    
                    <a href="<?php echo admin_url()?>settings/edit_setting/<?php echo $setting['setting_id'] ?>" class="fancybox_iframe edit" rel="500,350">
						<?php echo $this->lang->line('edit'); ?>
                    </a>
                    
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>settings/delete_setting/<?php echo $setting['setting_id'] ?>'" class="delete">
						<?php echo $this->lang->line('delete'); ?>
                    </a>
                    
                    <span class="small">
						<?php echo $setting['name']?>
					</span>
					<?php
				}
				?>
			</td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}	

//settings_details
if(isset($settings_details) && $settings_details)
{
	//shows form validation errors for details field and for each language
	foreach($languages as $key=>$language)
	{
		foreach($settings_details as $setting_detail)		
		{
			echo form_error('value_detail['.$setting_detail['setting_id'].']['.$language['lang_id'].']');
		}
	}
		
	//show details fields for each language	
	foreach($languages as $key=>$language)
	{					
		//if we have more than one language, then display lang code label
		if($show_label_language) 
			$label_language = ' ('.$language['code'].')';
		else
			$label_language = '';	
			
		?><div id="array_div_<?php echo $key?>" style="clear:left; display:<?php if($key == 0) echo 'block'; else echo 'none';?>" ><?php
		
			//if we have more than one language, then display lang"s tab
			if($show_label_language) 
			{
				foreach($languages as $key_=>$lang)
				{				
					?><div class="<?php if($lang['lang_id'] == $language['lang_id']) echo 'tab_on'; else echo 'tab_off';?>" onclick="show_div(array_divs,'array_div_<?php echo $key_?>')"><?php echo $lang['code']?></div><?php
				}
			}	
			?>		
			<div style="clear:left;">
			<table class="form_table tab" width="<?php echo $table_width?>">
			<?php
			foreach($settings_details as $setting_detail)
			{
				if(!isset($setting_detail['description'][$language['lang_id']]))
					$setting_detail['description'][$language['lang_id']] = "";
				if(!isset($setting_detail['value'][$language['lang_id']]))
					$setting_detail['value'][$language['lang_id']] = "";																	
				?>
				<tr>
                    <th width="<?php echo $th_width?>">
						<?php echo $setting_detail['description'][$language['lang_id']]; ?>
					</th>
                    <td>		
                        <textarea name="value_detail[<?php echo $setting_detail['setting_id']?>][<?php echo $language['lang_id']; ?>]" <?php if($setting_detail["html_textarea"]=="1") { ?>class="html_textarea" <?php } ?> style="width:300px;" rows="4" ><?php echo set_value('value_detail['.$setting_detail['setting_id'].']['.$language['lang_id'].']',$setting_detail['value'][$language['lang_id']]); ?></textarea>
						<?php
                        if($this->access['add_setting'])	
                        {                           
							?> 
                            <input type="text" size="2" value="<?php echo $setting_detail['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>settings/change_setting/<?php echo $setting_detail['setting_id'] ?>/order/'+this.value" />                            
                            
                            <?php	
							//active									
							$aux = array(	"field" 	=> "active",
											"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
											"values" 	=> array(1, 0),
											"classes" 	=> array("access", "noaccess"),
											"url" 		=> $this->config->item("admin_url")."settings/change_setting/".$setting_detail["setting_id"]	
											);				
							?>              
							<span class="hide"><?php echo json_encode($aux)?></span>					
							<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($setting_detail[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
								<?php echo ($setting_detail[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
							</a>  
                            
                            <a href="<?php echo admin_url()?>settings/change_setting/<?php echo $setting_detail['setting_id'] ?>/html_textarea/<?php echo ($setting_detail["html_textarea"]==1?0:1)?>" class="<?php echo ($setting_detail["html_textarea"]==1?"access":"noaccess")?>">
								<?php echo ($setting_detail["html_textarea"]==1?"html yes":"html no")?>
                            </a>
                            
                            <a href="<?php echo admin_url()?>settings/edit_setting/<?php echo $setting_detail['setting_id'] ?>" class="fancybox_iframe edit" rel="500,350">
								<?php echo $this->lang->line('edit'); ?>
                            </a>
                            
                            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>settings/delete_setting/<?php echo $setting_detail['setting_id'] ?>'" class="delete">
								<?php echo $this->lang->line('delete'); ?>
                            </a>
                            
                            <span class="small">
								<?php echo $setting_detail['name']?>
							</span>
							<?php
                        }
                        ?>
                    </td>
				</tr> 
				<?php
			}
			?>  		  
			</table>
			</div> 
		</div> 
		<?php
	}
}	
?>
<table class="form_table" width="<?php echo $table_width?>">  
    <tr>
        <th width="<?php echo $th_width?>"></th>
        <td>
			<input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line('save');?>"/>
        </td>
    </tr>   		  
</table>
</form>
