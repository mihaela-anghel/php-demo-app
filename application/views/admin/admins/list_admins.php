<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_admin'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>admins/add_admin" class="fancybox_iframe" rel="500,300">
				<?php echo $this->lang->line('add_admin'); ?>
			</a>
		</li>
		<?php
    }
    if($this->admin_access['list_roles'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>admins/list_roles">
				<?php echo $this->lang->line('roles_and_permissions'); ?>
			</a>
		</li>
		<?php		
    } 
    ?>       
</ul>

<!--PAGINATION-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed;?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>

<!--LISTING-->
<table class="list_table">
    <tr>
        <th><?php echo $sort_label['admin_id'];?>Id</th>
        <th><?php echo $sort_label['admin_username'];?><?php echo $this->lang->line('admin_username')?></th>
        <th><?php echo $this->lang->line('admin_role')?></th>
        <th><?php echo $sort_label['admin_email'];?><?php echo $this->lang->line('admin_email')?></th>
        <th><?php echo $sort_label['admin_name'];?><?php echo $this->lang->line('admin_name')?></th>
        <th><?php echo $this->lang->line('admin_phone')?></th>
        <th><?php echo $this->lang->line('admin_status')?></th>
        <th><?php echo $this->lang->line('actions')?></th>
    </tr>
    <?php
    if(!$admins)
    {
   		?><tr><td colspan="8"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($admins as $admin)
    {
		?>
		<tr>
            <td>
				<?php echo $admin['admin_id']?>
			</td>
            <td>
				<?php echo $admin['admin_username']?>
			</td>
            <td>
				<?php echo $admin['role']?>
			</td>
            <td>
            	<a href="mailto:<?php echo $admin['admin_email']?>"><?php echo $admin['admin_email']?></a>
			</td>
            <td>
				<?php echo $admin['admin_name']?>
			</td>
            <td>
				<?php echo $admin['admin_phone']?>
			</td>
            <td>
				<?php
                //active				
				$aux = array(	"field" 	=> "active",
								"labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> admin_url()."admins/change_admin/".$admin["admin_id"]	
								);
                if($this->admin_access['edit_admin'])	
                {                    								
					?>
					<span class="hide"><?php echo json_encode($aux)?></span>
					<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($admin[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
						<?php echo ($admin[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
					</a> 					
					<?php
                }
                else
                {
                    echo ($admin[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>
            <td>				
				<?php
                //actions
                if($this->admin_access['edit_admin'])	
                {
                    ?>
                    <a href="<?php echo admin_url()?>admins/edit_admin/<?php echo $admin['admin_id'] ?>" class="fancybox_iframe edit" rel="500,300">
						<?php echo $this->lang->line('edit'); ?>
                    </a>
					<?php
                }
                if($this->admin_access['change_password'])	
                {
                    ?>
                    <a href="<?php echo admin_url()?>admins/change_password/<?php echo $admin['admin_id'] ?>" class="fancybox_iframe go" rel="500,150">
						<?php echo $this->lang->line('change_password'); ?>
                    </a>
					<?php
                }
                if($this->admin_access['delete_admin'])	
                {
                    ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo admin_url()?>admins/delete_admin/<?php echo $admin['admin_id'] ?>'" class="delete">
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

<!--PAGINATION-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed;?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>