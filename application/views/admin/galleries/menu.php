<?php
if(isset($gallery["name"]))
{
    ?><h2><?php echo $gallery["name"]?></h2><?php
}
if(isset($gallery_details["name"][$this->admin_default_lang_id]))
{
    ?><h2><?php echo $gallery_details["name"][$this->admin_default_lang_id]?></h2><?php
}
?>

<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['edit_gallery'])	
    {	
        $class = ($this->uri->rsegment(2) == "edit_gallery" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>galleries/edit_gallery/<?php echo $gallery["gallery_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("edit_gallery"); ?>
            </a>
		</li>
		<?php
    } 
	if($this->admin_access['images'])	
    {	
        $class = ($this->uri->rsegment(2) == "images" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>galleries/images/<?php echo $gallery["gallery_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("gallery_images"); ?>
            </a>
		</li>
		<?php
    }  	
    if($this->admin_access['videos'])	
    {	
        $class = ($this->uri->rsegment(2) == "videos" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>galleries/videos/<?php echo $gallery["gallery_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("gallery_videos"); ?>
            </a>
		</li>
		<?php
    }
	/*if($this->admin_access['files'])	
    {	
        $class = ($this->uri->rsegment(2) == "files" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>galleries/files/<?php echo $gallery["gallery_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("gallery_files"); ?>
            </a>
		</li>
		<?php
    }*/    
    ?>       
</ul>

