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
			<?php echo $this->lang->line('admin_username')?>
		</th>
   		<td>
			<?php echo form_error('admin_username'); ?>
            <input type="text" name="admin_username" id="admin_username" value="<?php echo set_value('admin_username'); ?>"/>*
		</td>
    </tr>
    <tr>
    	<th>
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
    	<th>
			<?php echo $this->lang->line('admin_role')?>
		</th>
        <td>
			<?php echo form_error('admin_role_id'); ?>
            <select name="admin_role_id">
            <?php
            foreach($roles as $role)
            {
                ?><option value="<?php echo $role['admin_role_id']?>" <?php echo set_select('admin_role_id',$role['admin_role_id']); ?>><?php echo $role['admin_role']?></option><?php
            }
            ?>
            </select>*
        </td>
    </tr>
    <tr>
    	<th>
			<?php echo $this->lang->line('admin_email')?>
		</th>
    	<td>
			<?php echo form_error('admin_email'); ?>
            <input type="text" name="admin_email" id="admin_email" value="<?php echo set_value('admin_email'); ?>"/>*
		</td>
    </tr>
    <tr>
    	<th>
			<?php echo $this->lang->line('admin_name')?>
		</th>
    	<td>
			<?php echo form_error('admin_name'); ?>
            <input type="text" name="admin_name" id="admin_name" value="<?php echo set_value('admin_name'); ?>"/>*
		</td>
    </tr>
    <tr>
    	<th>
			<?php echo $this->lang->line('admin_phone')?>
		</th>
    	<td>
			<?php echo form_error('admin_phone'); ?>
            <input type="text" name="admin_phone" id="admin_phone" value="<?php echo set_value('admin_phone'); ?>"/>
		</td>
    </tr> 
    <tr>
    	<th><?php echo $this->lang->line('status')?></th>
        <td>
			<?php
			echo form_error('active');
			$active = array(	"0" => $this->lang->line("inactive"),
								"1" => $this->lang->line("active")	);
			?>
			<select name="active">
			<?php
			foreach($active as $value=>$label)
			{
				if($value == "1") $default = TRUE; else $default = FALSE;
				?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$default); ?>><?php echo $label?></option><?php
			}
			?>
			</select>*
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
