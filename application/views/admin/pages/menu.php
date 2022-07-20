<?php
if(isset($page["name"]))
{
    ?><h2><?php echo $page["name"]?></h2><?php
}
if(isset($page_details["name"][$this->admin_default_lang_id]))
{
    ?><h2><?php echo $page_details["name"][$this->admin_default_lang_id]?></h2><?php
}
?>

<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['edit_page'])	
    {	
        $class = ($this->uri->rsegment(2) == "edit_page" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>pages/edit_page/<?php echo $page["page_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("edit_page"); ?>
            </a>
		</li>
		<?php
    } 
	if($this->admin_access['images'])	
    {	
        $class = ($this->uri->rsegment(2) == "images" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>pages/images/<?php echo $page["page_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("page_images"); ?>
            </a>
		</li>
		<?php
    } 	  		
	if($this->admin_access['videos'])	
    {	
        $class = ($this->uri->rsegment(2) == "videos" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>pages/videos/<?php echo $page["page_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("page_videos"); ?>
            </a>
		</li>
		<?php
    }	
	if($this->admin_access['files'])	
    {	
        $class = ($this->uri->rsegment(2) == "files" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>pages/files/<?php echo $page["page_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("page_files"); ?>
            </a>
		</li>
		<?php
    }    
    ?>       
</ul>

