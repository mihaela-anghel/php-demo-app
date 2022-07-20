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
			<?php echo $this->lang->line('admin_password')?>
		</th>
        <td>
			<script src="<?php echo base_url() ?>js/admin/password_strenght.js" type="text/javascript"></script>
            <?php echo form_error('admin_password'); ?>
            <input type="password" name="admin_password" id="admin_password" value="<?php echo set_value('admin_password'); ?>" onKeyUp="runPassword(this.value, 'password');"/>*        
            <div style="width: 100px;">
                <div id="password_text" style="font-size: 10px;"></div>
                <div id="password_bar"  style="font-size: 1px; height: 2px; width: 0px; border: 0px solid white;"></div>
            </div>
        </td>
    </tr>
    <tr>
        <th>
			<?php echo $this->lang->line('admin_password2')?>
        </th>
        <td>
			<?php echo form_error('admin_password2'); ?>
            <input type="password" name="admin_password2" id="admin_password2" value="<?php echo set_value('admin_password2'); ?>"/>*
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
