<?php
//image gallery
if(isset($article["images"]) && $article["images"])
{			  			  
	?>		
	<div class="row mb-4">	
		<div class="col-12">
			<h3><i class="far fa-images"></i> <?php echo $this->lang->line("article_images")?></h3>
						
			<div class="gallery">							
			<?php
			foreach($article["images"] as $image)
			{
				//image
				$file_url 		= "";
				$file_name 		= $image['filename'];						
				$file_path 		= $this->config->item('base_path').'uploads/articles/images/'.$file_name;						
				if($file_name && file_exists($file_path))
				{
					$file_url		= $this->config->item('base_url').'uploads/articles/images/'.$file_name;
					$file_url_th	= $this->config->item('base_url').'image/r_300x300_crop/uploads/articles/images/'.get_thumb_name($file_name);
				}	
				
				if($file_url)
				{	
					?><div class="item">
						<figure data-fancybox="images" data-caption="<?php echo $this->lang->line("article_images")?>" data-src="<?php echo $file_url?>">
							<a href="<?php echo $file_url?>">
								<img class="img-fluid" src="<?php echo $file_url_th?>" alt="<?php echo $article["name"]?>">								
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
?>