<?php
//banner on homepage
if(isset($global_banners) && $global_banners)
{
	$banners 		= $global_banners;		
	?> 
    <!--Slider-->
    <section class="rev_slider_wrapper text-center">			
    <!-- START REVOLUTION SLIDER 5.0 auto mode -->
      <div id="rev_slider" class="rev_slider"  data-version="5.0">
        <ul>	
        <!-- SLIDE  -->
        <?php
		foreach($banners as $key=>$banner)
		{		
			$banner_src = base_url()."uploads/banners/".$banner['filename'];	
			
			$banner['description_'.$this->default_lang] = nl2br($banner['description_'.$this->default_lang]);
			
			if($banner["url"])
			{
				$banner["url"] = str_replace("en/",$this->default_lang."/",$banner["url"]);
				$banner["url"] = str_replace("ro/",$this->default_lang."/",$banner["url"]);
			}											
			?>
			<li data-transition="fade">
				<!-- MAIN IMAGE -->
				<img src="<?php echo base_url()."uploads/banners/".$banner['filename'];?>" alt="<?php echo $banner['name_'.$this->default_lang]?>" data-bgposition="center center" data-bgfit="cover">
				<!-- LAYER NR. 1 -->
				<h1 class="tp-caption  tp-resizeme" 							
				data-x="100"
				data-y="180"							
				data-width="auto"
				data-transform_idle="o:1;"
				data-transform_in="y:[-200%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;" 
				data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;" 
				data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
				data-mask_out="x:0;y:0;s:inherit;e:inherit;" 							 
				data-start="800"><?php echo $banner['name_'.$this->default_lang]?>
				</h1>
				
				<div class="tp-caption  tp-resizeme" 							
				data-x="160"
				data-y="280"							
				data-width="auto"
				data-transform_idle="o:1;"
				data-transform_in="y:[-200%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;" 
				data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;" 
				data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
				data-mask_out="x:0;y:0;s:inherit;e:inherit;" 							 
				data-start="800"><p><?php echo $banner['description_'.$this->default_lang]?></p>
				</div>
				
				<div class="tp-caption  tp-resizeme" 							
				data-x="210"
				data-y="390"							
				data-width="auto"
				data-transform_idle="o:1;"
				data-transform_in="y:[-200%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;" 
				data-transform_out="auto:auto;s:1000;e:Power3.easeInOut;" 
				data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
				data-mask_out="x:0;y:0;s:inherit;e:inherit;" 							 
				data-start="800">
				<?php
				if($banner["url"] != "") 
				{  
					?><a href="<?php echo $banner['url'] ?>" class="btn-light button-hover border-radius"><?php echo $this->lang->line("read_more")?></a><?php
				} 								
				?> 	                
				</div>
			</li>              			
			<?php				
		}
		?>              
        </ul>				
      </div><!-- END REVOLUTION SLIDER -->
    </section>	          
	<?php
}
?>