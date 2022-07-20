<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_role'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>admins/add_role" class="fancybox_iframe" rel="500,150">
				<?php echo $this->lang->line('add_role'); ?>
			</a>
		</li>
		<?php
    }
    ?>       
</ul>

<!--LISTING-->
<table class="list_table">
    <tr>
    	<th><?php echo $this->lang->line('admin_role')?></th>
    	<th><?php echo $this->lang->line('actions')?></th>
    </tr>
	<?php
    if(!$roles)
    {
   		?><tr><td colspan="2"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($roles as $role)
    {
		?>
		<tr>
            <td>
				<?php echo $role['admin_role']?>
			</td>	   
            <td>				
				<?php
                if($this->admin_access['set_role_permissions'])	
                {
                    ?>
                    <a href="<?php echo admin_url()?>admins/set_role_permissions/<?php echo $role['admin_role_id'] ?>" class="view">
						<?php echo $this->lang->line('permissions'); ?>
                    </a>
					<?php
                }
                if($this->admin_access['edit_role'])	
                {
                    ?>
                    <a href="<?php echo admin_url()?>admins/edit_role/<?php echo $role['admin_role_id'] ?>" class="fancybox_iframe edit" rel="500,100">
						<?php echo $this->lang->line('edit'); ?>
                    </a>
					<?php
                }
                if($this->admin_access['delete_role'])	
                {
                    ?>
                    <a href="javascript:;" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo admin_url()?>admins/delete_role/<?php echo $role['admin_role_id'] ?>'" class="delete">
						<?php echo $this->lang->line('delete'); ?>
                    </a>
					<?php
                }
                ?>			
            </td>
		</tr>
		<?php
    }
    ?>
</table>
