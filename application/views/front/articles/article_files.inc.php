<?php
//files
if(isset($article["files"]) && $article["files"])
{
	?>		
	<div class="row bg-light mb-4 py-4">
		<div class="col-12">
			<h3><i class="far fa-folder-open"></i> <?php echo $this->lang->line("article_files")?></h3>
			
			<div class="files row">				
			<?php	
			foreach($article["files"] as $file)
			{
				//image
				$file_name 		= $file['filename'];
				$file_url		= $this->config->item('base_url').'uploads/articles/files/'.$file_name;
				$file_path 		= $this->config->item('base_path').'uploads/articles/files/'.$file_name;
				
				$extension_array= explode(".",$file_name);
				$extension 		= end($extension_array);
				$anchor_name	= str_replace(array("_","-",".".$extension),array(" "," ",""),$file_name);
				if(isset($file["name"]) && $file["name"])
					$anchor_name = $file["name"]; 
				$class_name		= $extension;
				
				if($file_name && file_exists($file_path))
				{	
					?>
					<div class="col-lg-6">
						<div class="item">
							<a href="<?php echo $file_url?>" title="<?php echo htmlspecialchars($anchor_name)?>" class="download effect-shine <?php echo $class_name?>" target="_blank">
								<i class="fa fa-download"></i>	
								<strong><?php echo htmlspecialchars($anchor_name)?></strong>
								<br><?php echo $extension?>, <?php echo round(filesize($file_path)/1024,2)?> KB
							</a>
						</div>
					</div>	
					<?php
				}	
			}
			?>	
			</div>		
		</div>
	</div>
	<?php
}
?>