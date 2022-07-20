<!--MAKE AN JAVASCRIPT ARRAY WITH ALL DIVS THAT WE MUST HIDE (LANGUAGE TABS) -->
<script type="text/javascript" language="javascript">
var vector_page=new Array('div_setting','div_details');
<?php
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
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
        <th width="<?php echo $th_width?>">
			<?php echo $this->lang->line('setting')?>*
		</th>
        <td>
			<?php echo form_error('name'); ?>
            <input type="text" name="name" id="name" value="<?php echo set_value('name',$setting["name"]); ?>"/>
		</td>
    </tr>
    <tr>
        <th>
			<?php echo $this->lang->line('is_multilanguage')?>
		</th>
        <td>
        	<input type="radio" name="is_multilanguage" value="0" onchange="show_div(vector_page,'div_setting')" <?php  echo set_radio('is_multilanguage', '0', ($setting["is_multilanguage"]=="0"?true:false)); ?>/><?php echo $this->lang->line('no')?>
        	<input type="radio" name="is_multilanguage" value="1" onchange="show_div(vector_page,'div_details')" <?php  echo set_radio('is_multilanguage', '1', ($setting["is_multilanguage"]=="1"?true:false)); ?>/><?php echo $this->lang->line('yes')?>
        </td>
    </tr>
</table>
 
<div id = "div_setting" style="display:<?php if((isset($_POST['is_multilanguage']) && $_POST['is_multilanguage'] == '0') || (!isset($_POST['is_multilanguage']) && $setting["is_multilanguage"]=="0")) echo 'block'; else echo 'none';?>">  
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
        <th width="<?php echo $th_width?>">
			<?php echo $this->lang->line('description')?>
		</th>
        <td>
        	<input type="text" name="description" id="description" value="<?php echo set_value('description',$setting["description"]); ?>"/>
			<?php echo form_error('description'); ?>
		</td>
    </tr>
    <tr>
        <th>
			<?php echo $this->lang->line('value')?>
		</th>
        <td>
        	<input type="text" name="value" id="value" value="<?php echo set_value('value',$setting["value"]); ?>"/>
			<?php echo form_error('value'); ?>
		</td>
    </tr>     
</table>
</div>

<div id="div_details" style="display:<?php if((isset($_POST['is_multilanguage']) && $_POST['is_multilanguage'] == '1') || (!isset($_POST['is_multilanguage']) && $setting["is_multilanguage"]=="1")) echo 'block'; else echo 'none';?>"> 
<!--FORM FIELDS THAT DEPENDS ON LANGUGES-->
<?php
//shows form validation errors for details field and for each language
foreach($languages as $key=>$language)
{
	echo form_error("description_details[".$language["lang_id"]."]"); 	
	echo form_error("value_details[".$language["lang_id"]."]"); 	
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
            <tr>
                <th width="<?php echo $th_width?>">
					<?php echo $this->lang->line('description').$label_language?>
				</th>
                <td>
                	<?php
                    if(!isset($setting_details['description'][$language['lang_id']]))
						$setting_details['description'][$language['lang_id']] = "";					
					?>
                    <input type="text" name="description_details[<?php echo $language['lang_id']; ?>]" id="description_details_<?php echo $language['lang_id']; ?>" value="<?php echo set_value('description_details['.$language['lang_id'].']',$setting_details["description"][$language['lang_id']]); ?>"/>
                </td>
            </tr>   
            <tr>
                <th>
					<?php echo $this->lang->line('value').$label_language?>
				</th>
                <td>			
                	<?php
                    if(!isset($setting_details['value'][$language['lang_id']]))
						$setting_details['value'][$language['lang_id']] = "";	
					?>
                    <textarea name="value_details[<?php echo $language['lang_id']; ?>]" id="value_details_<?php echo $language['lang_id']; ?>" style="width:300px; height:70px;"><?php echo set_value('value_details['.$language['lang_id'].']',$setting_details["value"][$language['lang_id']]); ?></textarea>
                </td>
            </tr>  			 
		</table>
		</div> 
	</div> 
	<?php
}
?>
</div>

<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
        <th width="<?php echo $th_width?>">
			<?php echo $this->lang->line('order')?>
		</th>
        <td>
        	<input type="text" name="order" id="order" value="<?php echo set_value('order',$setting["order"]); ?>"/>
            <?php echo form_error('order'); ?>
		</td>
    </tr>
    <tr>
        <th></th>
        <td>
        	<input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line('save');?>"/>
		</td>
	</tr>
</table>  
</form>
