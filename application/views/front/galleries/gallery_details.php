<div class="page-content mb-4 clearfix">
    <?php   
    //title
    if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }

    //content
    echo $gallery["description"];
    ?>
</div>

<?php
//image gallery
if(isset($gallery["images"]) && $gallery["images"])
{			  			  
	?>
	<div class="row mb-4">	
		<div class="col-12">
			<h3><i class="far fa-images"></i> <?php echo $this->lang->line("page_images")?></h3>
						
			<div class="gallery">							
			<?php
			foreach($gallery["images"] as $image)
			{
				//image
				$file_url 		= "";
				$file_name 		= $image['filename'];						
				$file_path 		= $this->config->item('base_path').'uploads/galleries/images/'.$file_name;						
				if($file_name && file_exists($file_path))
				{
					$file_url		= $this->config->item('base_url').'uploads/galleries/images/'.$file_name;
					$file_url_th	= $this->config->item('base_url').'image/r_300x300_crop/uploads/galleries/images/'.get_thumb_name($file_name);
				}	
				
				if($file_url)
				{	
					?><div class="item">
						<figure data-fancybox="images" data-caption="<?php echo $this->lang->line("page_images")?>" data-src="<?php echo $file_url?>">
							<a href="<?php echo $file_url?>">
								<img class="img-fluid" src="<?php echo $file_url_th?>" alt="<?php echo $gallery["name"]?>">								
							</a>	
							<figcaption>								
								<i class="fa fa-search"></i>																										
							</figcaption>						
						</figure>						
					</div><?php
				}
			}
			?>
			</div>
		</div>																							
	</div>	
	<?php
}

//video gallery
if(isset($gallery["videos"]) && $gallery["videos"])
{			  			  
	?>
	<div class="row mb-4">	
		<div class="col-12">
			<h3><i class="fa fa-film"></i> <?php echo $this->lang->line("page_videos")?></h3>
						
			<div class="video-gallery">		
			<?php						
			foreach($gallery["videos"] as $video)
			{
				//video
				$file_url 		= "";
				$file_name 		= $video['filename'];						
				$file_path 		= $this->config->item('base_path').'uploads/galleries/videos/'.$file_name;						
				if($file_name && file_exists($file_path))							
					$file_url		= $this->config->item('base_url').'uploads/galleries/videos/'.$file_name;															
				
				if($file_url)
				{	
					?><div class="item">
						<video class="w-100" controls autoplay muted>
							<source src="<?php echo $file_url?>?<?php echo uniqid('')?>" type="video/mp4">
							Your browser does not support the video tag.
						</video>
					</div><?php
				}
			}
			?>	
			</div>							
		</div>																							
	</div>	
	<?php
}
?>  