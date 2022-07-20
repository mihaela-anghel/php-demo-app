<?php
if(isset($subpages) && $subpages)
{
	?>
	<div class="row">
		<div class="col-md-12">	
		<?php
		$this->load->helper("text");
		foreach($subpages as $key=>$subpage)
		{
			$subpage["url"] = base_url().$this->default_lang_url.($subpage["section"]?$subpage["section"]:$subpage["url_key"]);
			
			//image	
			$image_url	= "http://placehold.it/200x260";
			$image_name = $subpage['image'];
			$image_path = $this->config->item('base_path').'uploads/pages/'.$image_name;	
			if($image_name && file_exists($image_path))
				$image_url	= $this->config->item('base_url').'image/r_200x260_crop/uploads/pages/'.$image_name;            						

			$subpage["abstract"] = strip_tags($subpage["description"]);
			?>
			<div class="col-md-4 col-sm-6 content_wrap">
				
				<div class="image">
					<a href="<?php echo $subpage["url"];?>">
						<img src="<?php echo $image_url?>" alt="<?php echo $subpage["name"]?>" class="img-responsive border-radius">
					</a>
				</div>
				
				<div class="text">
					<h4><a href="<?php echo $subpage["url"]?>"><?php echo $subpage["name"]?></a></h4>
					<p><?php echo character_limiter($subpage["abstract"],100)?></p> 
					<a class="btn-light button-hover" href="<?php echo $subpage["url"];?>">
						<?php echo $this->lang->line("read_more")?>
					</a>
				</div>
			</div>                                        						
			<?php                                                      
		}
		?>   
		</div> 
	</div>
	<?php
}
?>
		
		