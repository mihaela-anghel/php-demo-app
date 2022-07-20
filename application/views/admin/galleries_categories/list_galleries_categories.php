<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_galleries_category'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>galleries_categories/add_galleries_category">
				<?php echo $this->lang->line('add_galleries_category'); ?>
			</a>
		</li>
		<?php
    }    
    ?>         
</ul>

<!--Listing-->
<table class="list_table">
<tr>	
    <th><?php echo $sort_label['t1.galleries_category_id'];?>Id</th>	
    <th><?php echo $sort_label['t2.galleries_category_name'];?><?php echo $this->lang->line('galleries_category_name')?></th>
    <!--<th><?php echo $this->lang->line('image')?></th>
    <th><?php echo $this->lang->line('banner')?></th>-->
    <th><?php echo $this->lang->line('items')?></th>
    <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
    <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>
    <th><?php echo $this->lang->line('actions')?></th>
</tr>
<?php  
if(!$galleries_categories)
{
	?><tr><td colspan="8" align="center"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}
//$nr=0;
foreach($galleries_categories as $galleries_category)
{
	?>
	<tr>	
        <td><?php echo $galleries_category['galleries_category_id']?></td>
        <td>
            <?php 
            for($i = 0; $i < ($galleries_category['level']*6) ; $i++)	echo "&nbsp;";	
            echo $galleries_category['galleries_category_name'];	
            ?>
        </td>	
        <?php
		/*
        <td>
            <?php
            //image	
            $image = $galleries_category['image'];
            $image_path = $this->config->item('base_path').'uploads/galleries_categories/images/'.$image;
            $image_url = $this->config->item('base_url').'uploads/galleries_categories/images/'.$image;
            
            if($image != '' && file_exists($image_path))
            {
                ?>
                 &raquo; <a href="<?php echo $image_url?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>galleries_categories/delete_file/image/<?php echo $galleries_category['galleries_category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>galleries_categories/upload_file/image/<?php echo $galleries_category['galleries_category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>
        </td>
        <td>
            <?php
            //banner
            $banner = $galleries_category['banner'];
            $banner_path = $this->config->item('base_path').'uploads/galleries_categories/banners/'.$banner;
            $banner_url = $this->config->item('base_url').'uploads/galleries_categories/banners/'.$banner;
            
            if($banner != '' && file_exists($banner_path))
            {
                ?>
                 &raquo; <a href="<?php echo $banner_url?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>galleries_categories/delete_file/banner/<?php echo $galleries_category['galleries_category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>galleries_categories/upload_file/banner/<?php echo $galleries_category['galleries_category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>     	
        </td>
		*/?>
        <td>	
        &raquo; <a href="<?php echo $this->config->item('admin_url')?>galleries_categories/set_search_session/galleries/galleries_category_id/<?php echo $galleries_category['galleries_category_id']; ?>"><?php echo $galleries_category['galleries_articles_number']; ?> <?php echo $this->lang->line('items')?></a>
        </td>
        <td><input type="text" size="2" class="input" value="<?php echo $galleries_category['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>galleries_categories/change_galleries_category/<?php echo $galleries_category['galleries_category_id'] ?>/order/'+this.value" style="width:30px;"/></td>			
        <td>
        	<?php
			//active				
			$aux = array(	"field" 	=> "active",
							"labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
							"values" 	=> array(1, 0),
							"classes" 	=> array("access", "noaccess"),
							"url" 		=> admin_url()."galleries_categories/change_galleries_category/".$galleries_category["galleries_category_id"]	
							);
			if($this->admin_access['edit_galleries_category'])	
			{                    								
				?>
				<span class="hide"><?php echo json_encode($aux)?></span>
				<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($galleries_category[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
					<?php echo ($galleries_category[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
				</a> 					
				<?php
			}
			else
			{
				echo ($proiect[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
			}
			?>        	
        </td>     
        <td>	
            <?php
            // actiuni
            if($this->admin_access['edit_galleries_category'])
            {
                ?><a href="<?php echo $this->config->item('admin_url')?>galleries_categories/edit_galleries_category/<?php echo $galleries_category['galleries_category_id'] ?>" class="edit"><?php echo $this->lang->line('edit'); ?></a><?php
            }	
			if($this->admin_access['delete_galleries_category'])
            {
                ?><a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>galleries_categories/delete_galleries_category/<?php echo $galleries_category['galleries_category_id'] ?>'" class="delete"><?php echo $this->lang->line('delete'); ?></a><?php
            }
            ?>		
        </td>    
	</tr>
	<?php
}
?>
</table>