<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['edit_user'])	
    {	
        $class = ($this->uri->rsegment(2) == "edit_user" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>users/edit_user/<?php echo $user["user_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("edit_user"); ?>
            </a>
		</li>
		<?php
    }
    if($this->admin_access['change_password'])	
    {	
        $class = ($this->uri->rsegment(2) == "change_password" ? "selected" : "");
        ?>
        <li>        	
            <a href="<?php echo admin_url()?>users/change_password/<?php echo $user["user_id"] ?>" class="<?php echo $class?>">
                <?php echo $this->lang->line("user_change_password"); ?>
            </a>
		</li>
		<?php
    } 	   
    ?>       
</ul>