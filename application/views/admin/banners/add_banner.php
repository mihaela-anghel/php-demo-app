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

<form action="" method="post" enctype="multipart/form-data">
<table class="form_table" width="<?php echo $table_width?>">  		
	<tr>
        <th>
			<?php echo $this->lang->line("banner_position")?>
		</th>
		<td>
			<?php echo form_error("position");?>
			<select name="position">
			<?php
			foreach($this->position_options as $value=>$label)
			{
				if($value == "slider") $selected = true; else $selected = false;
				?><option value="<?php echo $value?>" <?php echo set_select("position",$value,$selected); ?>><?php echo $label?></option><?php
			}
			?>
			</select>
		</td>
	</tr>
    <tr>    
        <th width="<?php echo $th_width?>">
        	<?php echo $this->lang->line('banner_filename')?>
        </th>
        <td>    
        	<?php echo form_error('file');  ?>
	        <input type="file" name="file" id="file"/>*	
            <p>JPG, GIF, PNG, max 3 Mb</p>
        </td>
    </tr> 
    <?php
    foreach($this->admin_languages as $language)
	{
		?>
        <tr>
            <th>
                <?php echo $this->lang->line("banner_name"); ?> <?php //echo strtoupper($language["code"])?>
            </th>
            <td>
                <?php echo form_error('name_'.$language["code"]); ?>
                <input type="text" name="name_<?php echo $language["code"]?>" id="name_<?php echo $language["code"]?>" value="<?php echo set_value("name_".$language["code"]); ?>" style="width:600px;"/>
            </td>
        </tr>
        <?php
		/*
        <tr>
            <th>
                <?php echo $this->lang->line("banner_subtitle"); ?> <?php echo strtoupper($language["code"])?>
            </th>
            <td>
                <?php echo form_error('subtitle_'.$language["code"]); ?>
                <input type="text" name="subtitle_<?php echo $language["code"]?>" id="subtitle_<?php echo $language["code"]?>" value="<?php echo set_value("subtitle_".$language["code"]); ?>" style="width:600px;"/>
            </td>
        </tr>
		*/?>
        <tr>
            <th>
                <?php echo $this->lang->line("banner_description"); ?> <?php //echo strtoupper($language["code"])?>
            </th>
            <td>
                <?php echo form_error('description_'.$language["code"]); ?>
                <textarea name="description_<?php echo $language["code"]?>" id="description_<?php echo $language["code"]?>" rows="4" class="html_textarea" style="width:600px; height:250px"><?php echo set_value("description_".$language["code"]); ?></textarea>                        						
            </td>
        </tr>
		<?php
	}	
	?>    
    <tr>
        <th>
            <?php echo $this->lang->line("banner_url"); ?>
        </th>
        <td>
            <?php echo form_error('url'); ?>
            <input type="text" name="url" id="url" value="<?php echo set_value("url"); ?>" style="width:600px;"/>
        </td>
    </tr>        
    <tr>
        <th>
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
    <tr>
        <th></th>
        <td>
            <input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line("add");?>"/>
        </td>
    </tr>
</table>
</form>