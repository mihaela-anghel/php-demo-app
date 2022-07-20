<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_page'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>pages/add_page">
				<?php echo $this->lang->line('add_page'); ?>
			</a>
		</li>
		<?php
    }    
    ?>       
</ul>

<?php 
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } 
?>

<!--TOTAL RESULTS-->
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($pages);?></p>

<!--LISTING-->
<table class="list_table">
    <tr>
        <th><?php echo $sort_label['t1.page_id'];?>Id</th>	
        <th><?php echo $sort_label['t2.name'];?><?php echo $this->lang->line('page_name')?></th>
        <th><?php echo $this->lang->line('page_image')?></th>
        <!--
        <th><?php echo $this->lang->line('page_banner')?></th>
        <th>Icon</th>
        <th>Clasa icon <br/><a href="http://fontawesome.io/icons/" target="_blank">vezi exemple</a></th>
        -->
        <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>                
        <th><?php echo $this->lang->line('on_menu')?></th>             
        <th><?php echo $this->lang->line('on_footer')?></th>
        <th><?php echo $this->lang->line('on_home')?></th>
        <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>
        <th colspan="3"><?php echo $this->lang->line('actions')?></th>
    </tr>
	<?php
    if(!$pages)
    {	
        ?><tr><td colspan="9"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($pages as $page)
    {
        if($page["section"] == "home")
			$page_url = base_url()/*/$this->admin_default_lang*/;	
		else
			$page_url = base_url()./*$this->admin_default_lang."/".*/($page['section']?$page['section']:$page['url_key']);		
        ?>
        <tr>
            <td>
                <?php echo $page['page_id']?>
            </td>
            <td>            
                <?php 
                for($i = 0; $i < ($page['level']*6) ; $i++)	
                    echo "&nbsp;";	
                echo $page['name'];	
                ?>
            </td>	            	
            <td>        
                <?php	
				if($page["parent_id"] >= 0)
				{
					//image	
					$file_name		= $page["image"];
					$file_path 		= base_path()."uploads/pages/".$file_name;
					$file_url 		= file_url()."uploads/pages/".$file_name;
					if($file_name && file_exists($file_path))
					{
						?>
						<a href="<?php echo $file_url;?>" class = "fancybox_image go">
							<?php echo $this->lang->line("view")?>                        
						</a>
						<?php
						
						if($this->admin_access["edit_page"])
						{                        
							?>
							<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>pages/delete_file/image/<?php echo $page["page_id"]?>'">
								<?php echo $this->lang->line("delete")?>
							</a>						
							<?php
						}
					}
					else if($this->admin_access["edit_page"])
					{                   
						?>                    
						<a href="<?php echo admin_url()?>pages/upload_file/image/<?php echo $page["page_id"]?>" class = "fancybox_iframe go" rel="600,150">
							<?php echo $this->lang->line("upload")?>
						</a>
						<?php
					}      
				}	                             			                			
                ?>
            </td>     
            <!--      
            <td>        
                <?php	
				if($page["section"] != "home")
				{
					//banner	
					$file_name		= $page["banner"];
					$file_path 		= base_path()."uploads/pages/banners/".$file_name;
					$file_url 		= file_url()."uploads/pages/banners/".$file_name;                
					if($file_name && file_exists($file_path))
					{
						?>
						<a href="<?php echo $file_url;?>" class = "fancybox_image go">
							<?php echo $this->lang->line("view")?>                       
						</a>
						<?php
						
						if($this->admin_access["edit_page"])
						{                       
							?>
							<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>pages/delete_file/banner/<?php echo $page["page_id"]?>'">
								<?php echo $this->lang->line("delete")?>
							</a>						
							<?php
						}
					}
					else if($this->admin_access["edit_page"])
					{                   
						?>                    
						<a href="<?php echo admin_url()?>pages/upload_file/banner/<?php echo $page["page_id"]?>" class = "fancybox_iframe go" rel="600,150">
							<?php echo $this->lang->line("upload")?>
						</a>
						<?php
					} 
				}                                  			                			
                ?>
            </td>	
            <td>        
                <?php	
				if(in_array($page["parent_id"],array(1,10)))
				{
					//icon	
					$file_name		= $page["icon"];
					$file_path 		= base_path()."uploads/pages/icons/".$file_name;
					$file_url 		= file_url()."uploads/pages/icons/".$file_name;                
					if($file_name && file_exists($file_path))
					{
						?>
						<a href="<?php echo $file_url;?>" class = "fancybox_image go">
							<?php echo $this->lang->line("view")?>                       
						</a>
						<?php
						
						if($this->admin_access["edit_page"])
						{                       
							?>
							<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>pages/delete_file/icon/<?php echo $page["page_id"]?>'">
								<?php echo $this->lang->line("delete")?>
							</a>						
							<?php
						}
					}
					else if($this->admin_access["edit_page"])
					{                   
						?>                    
						<a href="<?php echo admin_url()?>pages/upload_file/icon/<?php echo $page["page_id"]?>" class = "fancybox_iframe go" rel="600,150">
							<?php echo $this->lang->line("upload")?>
						</a>
						<?php
					} 
				}                                  			                			
                ?>
            </td>		
			<td>
                <input type="text" value="<?php echo $page["class"]?>" onblur="window.location='<?php echo admin_url()?>pages/change_page/<?php echo $page["page_id"] ?>/class/'+this.value" style="width:100px;"/>
            </td>--> 			
            <td>
                <input type="text" value="<?php echo $page["order"]?>" onblur="window.location='<?php echo admin_url()?>pages/change_page/<?php echo $page["page_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>                                
            <td>
                <?php
                //on_menu				
                $aux = array(	"field" 	=> "on_menu",
                                "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."pages/change_page/".$page["page_id"]	
                                );
                if($this->admin_access['edit_page'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>            
            <td>
                <?php
                //on_footer				
                $aux = array(	"field" 	=> "on_footer",
                                "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."pages/change_page/".$page["page_id"]	
                                );
                if($this->admin_access['edit_page'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>
            <td>
                <?php
                //on_home				
                $aux = array(	"field" 	=> "on_home",
                                "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."pages/change_page/".$page["page_id"]	
                                );
                if($this->admin_access['edit_page'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td> 
            <td>
                <?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."pages/change_page/".$page["page_id"]	
                                );
                if($this->admin_access['edit_page'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($page[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>  
            <td>
                <a href="<?php echo $page_url?>" target="_blank"><?php echo $this->lang->line('view_page');?></a>
            </td>
            <td> 
            	<?php						
                //actions 								  
				if($this->admin_access['images'])	
				{	
					?>
                    <div>
                        <a href="<?php echo admin_url()?>pages/images/<?php echo $page["page_id"] ?>" class="go">
                            <?php echo $this->lang->line("page_images"); ?> (<?php echo $page["images_number"]?>)
                        </a>
                    </div>
					<?php
				} 	                   				                    																		
				if($this->admin_access['videos'])	
				{	
					?>
					<div>
						<a href="<?php echo admin_url()?>pages/videos/<?php echo $page["page_id"] ?>" class="go">
							<?php echo $this->lang->line("page_videos"); ?> (<?php echo $page["videos_number"]?>)
						</a>
					</div>
					<?php
				}  
				if($this->admin_access['files'])	
				{	
					?>
                    <div>
						<a href="<?php echo admin_url()?>pages/files/<?php echo $page["page_id"] ?>" class="go">
							<?php echo $this->lang->line("page_files"); ?> (<?php echo $page["files_number"]?>)
						</a>
					</div>
					<?php
				}								 				
                ?>	                                   
            </td>
            <td>				
				<?php						
                //actions 				
				if($this->admin_access["edit_page"])	
                {
                    ?>
                    <div>                   
                        <a href="<?php echo admin_url()?>pages/edit_page/<?php echo $page["page_id"] ?>" class="edit">
                            <?php echo $this->lang->line("edit"); ?>
                        </a>
                    </div>
                    <?php
                }  												 
				if($this->admin_access["delete_page"] && $page["section"] == "" && $page["childs_number"] == 0)	
                {
                    ?>
                    <div>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_subitems")?>')) window.location='<?php echo admin_url()?>pages/delete_page/<?php echo $page["page_id"] ?>'" class="delete">
                            <?php echo $this->lang->line("delete"); ?>
                        </a>
                    </div>
                    <?php
                }	
                ?>			        	
            </td>    
        </tr>
        <?php	
	}
    ?>
</table>

<!--TOTAL RESULTS-->
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($pages);?></p>