<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['edit_qwerty'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>qwertys/edit_qwerty/<?php echo $qwerty["qwerty_id"] ?>">
				<?php echo $this->lang->line("edit_qwerty"); ?>
            </a>
		</li>
		<?php
    } 
	if($this->admin_access['images'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>qwertys/images/<?php echo $qwerty["qwerty_id"] ?>">
				<?php echo $this->lang->line("qwerty_images"); ?>
            </a>
		</li>
		<?php
    }  
	if($this->admin_access['files'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>qwertys/files/<?php echo $qwerty["qwerty_id"] ?>">
				<?php echo $this->lang->line("qwerty_files"); ?>
            </a>
		</li>
		<?php
    }    
    ?>       
</ul>

