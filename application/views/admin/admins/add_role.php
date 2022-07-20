<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "150";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<form action="" method="post">
<table class="form_table" width="<?php echo $table_width?>">
    <tr>
    	<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line('admin_role')?>
        </th>
		<td>
			<?php echo form_error('admin_role'); ?>
            <input type="text" name="admin_role" id="admin_role" value="<?php echo set_value('admin_role'); ?>"/>*
		</td>
    </tr>  
    <tr>
    	<th></th>
    	<td>
        	<input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line('add');?>"/>
		</td>
    </tr>
</table>
</form>
