<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_qwerty'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>qwertys/add_qwerty">
				<?php echo $this->lang->line('add_qwerty'); ?>
			</a>
		</li>
		<?php
    }    
    ?>       
</ul>

<!--TOTAL RESULTS-->
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($qwertys);?></p>

<!--LISTING-->
<table class="list_table">
    <tr>
        <th><?php echo $sort_label['t1.qwerty_id'];?>Id</th>	
        <th><?php echo $sort_label['t2.name'];?><?php echo $this->lang->line('qwerty_name')?></th>
        <th><?php echo $this->lang->line('view_qwerty')?></th>
        <th><?php echo $this->lang->line('qwerty_image')?></th>
        <th><?php echo $this->lang->line('qwerty_banner')?></th>
        <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
        <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>               
        <th><?php echo $this->lang->line('actions')?></th>
    </tr>
	<?php
    if(!$qwertys)
    {	
        ?><tr><td colspan="8"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($qwertys as $qwerty)
    {
        $qwerty_url = base_url().$this->admin_default_lang."/".$qwerty['url_key'];
        ?>
        <tr>
            <td>
                <?php echo $qwerty['qwerty_id']?>
            </td>
            <td>            
                <?php 
                for($i = 0; $i < ($qwerty['level']*6) ; $i++)	
                    echo "&nbsp;";	
                echo $qwerty['name'];	
                ?>
            </td>	
            <td>
                <a href="<?php echo $qwerty_url?>" target="_blank"><?php echo $this->lang->line('view_qwerty');?></a>
            </td>	
            <td>        
                <?php	
                //image	
                $file_name		= $qwerty["image"];
                $file_path 		= base_path()."uploads/qwertys/".$file_name;
                $file_url 		= file_url()."uploads/qwertys/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image go">
                        <?php echo $this->lang->line("view")?>                        
                    </a>
					<?php
                    
                    if($this->admin_access["edit_qwerty"])
                    {                        
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>qwertys/delete_file/image/<?php echo $qwerty["qwerty_id"]?>'">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_qwerty"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>qwertys/upload_file/image/<?php echo $qwerty["qwerty_id"]?>" class = "fancybox_iframe go" rel="400,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <td>        
                <?php	
                //banner	
                $file_name		= $qwerty["banner"];
                $file_path 		= base_path()."uploads/qwertys/banners/".$file_name;
                $file_url 		= file_url()."uploads/qwertys/banners/".$file_name;                
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image go">
                        <?php echo $this->lang->line("view")?>                       
                    </a>
                    <?php
					
                    if($this->admin_access["edit_qwerty"])
                    {                       
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>qwertys/delete_file/banner/<?php echo $qwerty["qwerty_id"]?>'">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_qwerty"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>qwertys/upload_file/banner/<?php echo $qwerty["qwerty_id"]?>" class = "fancybox_iframe go" rel="400,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <td>
                <input type="text" value="<?php echo $qwerty["order"]?>" onblur="window.location='<?php echo admin_url()?>qwertys/change_qwerty/<?php echo $qwerty["qwerty_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>        
            <td>
                <?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."qwertys/change_qwerty/".$qwerty["qwerty_id"]	
                                );
                if($this->admin_access['edit_qwerty'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($qwerty[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($qwerty[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($qwerty[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>                  
            <td>
                <?php
                //actions
                if($this->admin_access["edit_qwerty"])	
                {
                    ?>
                    <a href="<?php echo admin_url()?>qwertys/edit_qwerty/<?php echo $qwerty["qwerty_id"] ?>" class="edit">
                        <?php echo $this->lang->line("edit"); ?>
                    </a>
                    <?php
                }     
				if($this->admin_access['images'])	
				{	
					?>
					<a href="<?php echo admin_url()?>qwertys/images/<?php echo $qwerty["qwerty_id"] ?>" class="go">
						<?php echo $this->lang->line("qwerty_images"); ?> (<?php echo $qwerty["images_number"]?>)
                    </a>
					<?php
				}  
				if($this->admin_access['files'])	
				{	
					?>
					<a href="<?php echo admin_url()?>qwertys/files/<?php echo $qwerty["qwerty_id"] ?>" class="go">
						<?php echo $this->lang->line("qwerty_files"); ?> (<?php echo $qwerty["files_number"]?>)
                    </a>
					<?php
				}             
                if($this->admin_access["delete_qwerty"] /*&& $qwerty["childs_number"] == 0*/)	
                {
                    ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_subitems")?>')) window.location='<?php echo admin_url()?>qwertys/delete_qwerty/<?php echo $qwerty["qwerty_id"] ?>'" class="delete">
                        <?php echo $this->lang->line("delete"); ?>
                    </a>
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
<p align="left" class="small"><?php echo $this->lang->line("results")?> <?php echo count($qwertys);?></p>