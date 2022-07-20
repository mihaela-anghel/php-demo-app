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
            <input type="text" name="admin_username" id="admin_username" value="<?php echo set_value('admin_username',$admin['admin_username']); ?>"/>*
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
            if($_SESSION['admin_auth']['admin_role'] != 'webmaster')
            {
                if($admin['admin_role_id'] == '1')
                {
                    foreach($roles as $role)
                    {
                        if($role['admin_role_id'] == '1')
                        {
                            if($role['admin_role_id'] == $admin['admin_role_id'] ||( isset($_POST['admin_role_id']) && $role['admin_role_id'] == $_POST['admin_role_id'])) 
                                $default = TRUE;
                            else 
                                $default = FALSE;
                            ?><option value="<?php echo $role['admin_role_id']?>" <?php echo set_select('admin_role_id',$role['admin_role_id'],$default); ?>><?php echo $role['admin_role']?></option><?php
                        }
                    }	
                }
                else
                {
                    foreach($roles as $role)
                    {
                        if($role['admin_role_id'] != '1')
                        {			
                            if($role['admin_role_id'] == $admin['admin_role_id'] || ( isset($_POST['admin_role_id']) && $role['admin_role_id'] == $_POST['admin_role_id'])) 
                                $default = TRUE;
                            else 
                                $default = FALSE;
                            ?><option value="<?php echo $role['admin_role_id']?>" <?php echo set_select('admin_role_id',$role['admin_role_id'],$default); ?>><?php echo $role['admin_role']?></option><?php
                        }	
                    }
                }
            }
            else
            {
                foreach($roles as $role)
                {					
                    if($role['admin_role_id'] == $admin['admin_role_id'] ||( isset($_POST['admin_role_id']) && $role['admin_role_id'] == $_POST['admin_role_id']) ) 
                        $default = TRUE;
                    else 
                        $default = FALSE;
                    ?><option value="<?php echo $role['admin_role_id']?>" <?php echo set_select('admin_role_id',$role['admin_role_id'],$default); ?>><?php echo $role['admin_role']?></option><?php
                        
                }
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
            <input type="text" name="admin_email" id="admin_email" value="<?php echo set_value('admin_email',$admin['admin_email']); ?>"/>*
		</td>
    </tr>
    <tr>
   		<th>
			<?php echo $this->lang->line('admin_name')?>
		</th>
    	<td>
			<?php echo form_error('admin_name'); ?>
            <input type="text" name="admin_name" id="admin_name" value="<?php echo set_value('admin_name',$admin['admin_name']); ?>"/>*
		</td>
    </tr>
    <tr>
    	<th>
			<?php echo $this->lang->line('admin_phone')?>
		</th>
    	<td>
			<?php echo form_error('admin_phone'); ?>
            <input type="text" name="admin_phone" id="admin_phone" value="<?php echo set_value('admin_phone',$admin['admin_phone']); ?>"/>
        </td>
    </tr> 
    <tr>
    	<th>
			<?php echo $this->lang->line('status')?>
		</th>
        <td>
			<?php
			echo form_error('active');
			$active = array(	"0" => $this->lang->line("inactive"),
								"1" => $this->lang->line("active")	);
			?>
			<select name="active" class="select">
			<?php
			foreach($active as $value=>$label)
			{
				if($admin["active"] == $value) $default = TRUE; else $default = FALSE;
				?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$default); ?>><?php echo $label?></option><?php
			}
			?>
			</select>*
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
