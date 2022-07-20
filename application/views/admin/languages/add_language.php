<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "50";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<form action="" method="post">
<table class="form_table" width="<?php echo $table_width?>">
    <tr>
    	<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("lang")?>
		</th>
   		<td>
			<?php echo form_error("name"); ?>
            <input type="text" name="name" id="name" value="<?php echo set_value("name"); ?>"/>*
		</td>
    </tr>  
    <tr>
    	<th>
			<?php echo $this->lang->line("code")?>
		</th>
   		<td>
			<?php echo form_error("code"); ?>
            <input type="text" name="code" id="code" value="<?php echo set_value("code"); ?>" maxlength="2"/>*
            <span class="small">exp: en <?php echo $this->lang->line("for")?> UK</span>
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