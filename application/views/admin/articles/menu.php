<h2>
    <?php 
    if(isset($article_details["name"][$this->admin_default_lang_id])) 
        echo $article_details["name"][$this->admin_default_lang_id]; 
    elseif(isset($article["name"])) 
        echo $article["name"];
    ?>
</h2>

<!--SUBMENU-->
<ul class="submenu">	
    <li>
        <a href="<?php echo admin_url()?>articles" style="background:#C13131">
            <i class="fa fa-caret-left"></i> Inapoi
        </a>
    </li>
	<?php 
    if($this->admin_access['edit_article'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>articles/edit_article/<?php echo $article["article_id"] ?>">
				<?php echo $this->lang->line("edit"); ?>
            </a>
		</li>
		<?php
    }     
	if($this->admin_access['images'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>articles/images/<?php echo $article["article_id"] ?>">
				<?php echo $this->lang->line("article_images"); ?>
            </a>
		</li>
		<?php
    }  	
    if($this->admin_access['videos'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>articles/videos/<?php echo $article["article_id"] ?>">
				<?php echo $this->lang->line("article_videos"); ?>
            </a>
		</li>
		<?php
    }  
    if($this->admin_access['files'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>articles/files/<?php echo $article["article_id"] ?>">
				<?php echo $this->lang->line("article_files"); ?>
            </a>
		</li>
		<?php
    }       
    ?>       
</ul>

