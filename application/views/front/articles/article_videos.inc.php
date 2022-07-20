<?php
//video gallery
if(isset($article["videos"]) && $article["videos"])
{			  			  
	?>
	<div class="row mb-4">	
		<div class="col-12">
			<h3><i class="fa fa-film"></i> <?php echo $this->lang->line("article_videos")?></h3>
						
			<div class="video-gallery">		
			<?php						
			foreach($article["videos"] as $video)
			{
				//video
				$file_url 		= "";
				$file_name 		= $video['filename'];						
				$file_path 		= $this->config->item('base_path').'uploads/articles/videos/'.$file_name;						
				if($file_name && file_exists($file_path))							
					$file_url		= $this->config->item('base_url').'uploads/articles/videos/'.$file_name;															
				
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
			
            //videos
            //============================================	
            foreach($article["videos"] as $video)
            {
                ?>
                <div class="item">          
                                             
                    <?php //echo $video["video"]?>
                    <?php
                    if($video['filename'])
                    {
                        ?>                        
                        <video controls autoplay muted width="100%">
                            <source src="<?php echo base_url()?>uploads/articles/videos/<?php echo $video['filename']?>" type="video/mp4">                            
                            Your browser does not support the video tag.
                        </video>                                                                         
                        <?php
                    }
                    if($video['video'])
                    {
                        ?>
                        <div class="video">
                            <?php echo $video["video"]?>
                        </div>
                        <?php
                    }
                    ?> 
                                
                </div>                            
                <?php	
            }
            ?> 	
			</div>							
		</div>																							
	</div>	
	<?php
}
?>