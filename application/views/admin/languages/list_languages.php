<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access["add_language"])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>languages/add_language" class="fancybox_iframe" rel="450,220">
				<?php echo $this->lang->line("add_lang"); ?>
			</a>
		</li>
		<?php
    }  
    ?>       
</ul>

<!--TOTAL RESULTS-->
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($languages);?></p>

<!--LISTING-->
<table class="list_table">
    <tr>
        <th><?php echo $sort_label["lang_id"];?>Id</th>
        <th><?php echo $sort_label["name"];?><?php echo $this->lang->line("lang")?></th>
        <th><?php echo $this->lang->line("code")?></th>
        <th><?php echo $this->lang->line("image")?></th>
        <th><?php echo $sort_label["order"];?><?php echo $this->lang->line("order")?></th>
        <th>Google translate</th>
        <th><?php echo $this->lang->line("active_site")?></th>
        <th><?php echo $this->lang->line("active_admin")?></th>    
        <th><?php echo $this->lang->line("default_site")?></th>        
        <?php
		if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
		{
			?>
        	<th><?php echo $this->lang->line("default_admin")?></th>
	        <?php
		}
		if($this->admin_access["translate"])
		{
			?>
        	<th><?php echo $this->lang->line("translation")?></th>
	        <?php
        }
		if($this->admin_access["edit_language"] || $this->admin_access["delete_language"])
		{
			?>
        	<th><?php echo $this->lang->line("actions")?></th>    
	        <?php
        }
		?>        
    </tr>
	<?php 
    if(!$languages)
    {
    	?><tr><td colspan="8" align="center"><?php echo $this->lang->line("no_entries");?></td></tr><?php
    } 
    foreach($languages as $lang)
    {
		?>
		<tr>
            <td>
				<?php echo $lang["lang_id"]?>
			</td>
            <td>
				<?php echo $lang["name"]?>
			</td>
            <td>
				<?php echo $lang["code"]?>
			</td>    
            <td>    
				<?php	
                //image	
                $image 		= $lang["flag"];
                $image_path = base_path()."uploads/languages/".$image;
                $image_url 	= file_url()."uploads/languages/".$image;                
                if($image && file_exists($image_path))
                {
                    ?><img src="<?php echo $image_url;?>" alt="<?php echo $lang["code"]?>"/><?php
					
					if($this->admin_access["edit_language"])
					{
						//delete file
						?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>languages/delete_file/flag/<?php echo $lang["lang_id"]?>'">
							<?php echo $this->lang->line("delete")?>
                        </a>						
						<?php
					}
                }
                else if($this->admin_access["edit_language"])
                {
                    ?>                    
                    <a href="<?php echo admin_url()?>languages/upload_file/flag/<?php echo $lang["lang_id"]?>" class = "fancybox_iframe go" rel="400,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   
                ?>
            </td>
            <td>
            	<input type="text" value="<?php echo $lang["order"]?>" onblur="window.location='<?php echo admin_url()?>languages/change_language/<?php echo $lang["lang_id"] ?>/order/'+this.value" style="width:30px;"/>
			</td>	
            <td>                
                <?php	
				//active_site			
				$aux = array(	"field" 	=> "google_translate",
								"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> admin_url()."languages/change_language/".$lang["lang_id"]	
								);				
				?>              
                <span class="hide"><?php echo json_encode($aux)?></span>
                <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                    <?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                </a>                                
            </td>            
            <td>                
                <?php	
				//active_site			
				$aux = array(	"field" 	=> "active_site",
								"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> admin_url()."languages/change_language/".$lang["lang_id"]	
								);				
				?>              
                <span class="hide"><?php echo json_encode($aux)?></span>
                <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                    <?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                </a>                                
            </td>
            <td>
                <?php	
				//active_admin										
				$aux = array(	"field" 	=> "active_admin",
								"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> admin_url()."languages/change_language/".$lang["lang_id"]	
								);				
				?>              
                <span class="hide"><?php echo json_encode($aux)?></span>
                <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                    <?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                </a>                  
            </td>
            <td>
                <?php	
				//default_site			
				$aux = array(	"field" 	=> "default_site",
								"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> admin_url()."languages/change_language/".$lang["lang_id"]	
								);				
				?>              
                <span class="hide"><?php echo json_encode($aux)?></span>
                <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                    <?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                </a>               
            </td>                                 	
			<?php
			if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
			{
				?>
				<td>
                    <?php	
					//default_admin			
					$aux = array(	"field" 	=> "default_admin",
									"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
									"values" 	=> array(1, 0),
									"classes" 	=> array("access", "noaccess"),
									"url" 		=> admin_url()."languages/change_language/".$lang["lang_id"]	
									);				
					?>              
					<span class="hide"><?php echo json_encode($aux)?></span>
					<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
						<?php echo ($lang[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
					</a>                     
                </td>  
				<?php
			}
			
			//translate
            if($this->admin_access["translate"])
            {
                ?>
                <td>			
                    <a href="<?php echo admin_url()?>languages/translate/<?php echo $lang["lang_id"]?>" class="view">
                        <?php echo $this->lang->line("view_translation"); ?>
                    </a> 
                </td>
                <?php
            }
			           
            if($this->admin_access["edit_language"] || $this->admin_access["delete_language"])
			{
				?>
				<td>	
					<?php
					//actions
					if($this->admin_access["edit_language"])	
					{
						?>
						<a href="<?php echo admin_url()?>languages/edit_language/<?php echo $lang["lang_id"] ?>" class="fancybox_iframe edit" rel="450,220">
							<?php echo $this->lang->line("edit"); ?>
						</a>
						<?php
					}              
					if($this->admin_access["delete_language"])	
					{
						?>
						<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>languages/delete_language/<?php echo $lang["lang_id"] ?>'" class="delete">
							<?php echo $this->lang->line("delete"); ?>
						</a>
						<?php
					}
					?>						 					
				</td>
				<?php
            }
			?>                
		</tr>
		<?php
    }
    ?>
</table>

<!--TOTAL RESULTS-->
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($languages);?></p>