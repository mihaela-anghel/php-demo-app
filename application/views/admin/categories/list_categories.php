<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_category'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>categories/add_category">
				<?php echo $this->lang->line('add_category'); ?>
			</a>
		</li>
		<?php
    }    
    ?>         
</ul>

<!--Listing-->
<table class="list_table">
<tr>	
    <th><?php echo $sort_label['t1.category_id'];?>Id</th>	
    <th><?php echo $sort_label['t2.category_name'];?><?php echo $this->lang->line('category_name')?></th>
    <!--<th><?php echo $this->lang->line('image')?></th>
    <th><?php echo $this->lang->line('banner')?></th>-->
    <th><?php echo $this->lang->line('items')?></th>
    <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
    <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>
    <th><?php echo $this->lang->line('actions')?></th>
</tr>
<?php  
if(!$categories)
{
	?><tr><td colspan="8" align="center"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}
//$nr=0;
foreach($categories as $category)
{
	?>
	<tr>	
        <td><?php echo $category['category_id']?></td>
        <td>
            <?php 
            /* for($i = 0; $i < ($category['level']*6) ; $i++)	echo "&nbsp;";	 */
            echo $category['category_name'];	
            ?>
        </td>	
        <?php
		/*
        <td>
            <?php
            //image	
            $image = $category['image'];
            $impath = $this->config->item('base_path').'uploads/categories/images/'.$image;
            $imurl = $this->config->item('base_url').'uploads/categories/images/'.$image;
            
            if($image != '' && file_exists($impath))
            {
                ?>
                 &raquo; <a href="<?php echo $imurl?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>categories/delete_file/image/<?php echo $category['category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>categories/upload_file/image/<?php echo $category['category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>
        </td>
        <td>
            <?php
            //banner
            $banner = $category['banner'];
            $banner_path = $this->config->item('base_path').'uploads/categories/banners/'.$banner;
            $banner_url = $this->config->item('base_url').'uploads/categories/banners/'.$banner;
            
            if($banner != '' && file_exists($banner_path))
            {
                ?>
                 &raquo; <a href="<?php echo $banner_url?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>categories/delete_file/banner/<?php echo $category['category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>categories/upload_file/banner/<?php echo $category['category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>     	
        </td>
		*/?>        
        <td>	
            &raquo; 
            <a href="<?php echo $this->config->item('admin_url')?>categories/set_search_session/competitions/category_id/<?php echo $category['category_id']; ?>">
                <?php echo $category['competitions_number']; ?> <?php echo $this->lang->line('category_competitions')?>
            </a>

            &raquo; 
            <?php echo $category['participants_number']; ?> <?php echo $this->lang->line('category_participants')?>            
        </td>
        <td><input type="text" size="2" class="input" value="<?php echo $category['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>categories/change_category/<?php echo $category['category_id'] ?>/order/'+this.value" style="width:30px;"/></td>			
        <td>
        	<?php
			//active				
			$aux = array(	"field" 	=> "active",
							"labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
							"values" 	=> array(1, 0),
							"classes" 	=> array("access", "noaccess"),
							"url" 		=> admin_url()."categories/change_category/".$category["category_id"]	
							);
			if($this->admin_access['edit_category'])	
			{                    								
				?>
				<span class="hide"><?php echo json_encode($aux)?></span>
				<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($category[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
					<?php echo ($category[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
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
            if($this->admin_access['edit_category'])
            {
                ?><a href="<?php echo $this->config->item('admin_url')?>categories/edit_category/<?php echo $category['category_id'] ?>" class="edit"><?php echo $this->lang->line('edit'); ?></a><?php
            }	
			if($this->admin_access['delete_category'])
            {
                if($category['competitions_number'] > 0 || $category['participants_number'] > 0)
                {
                    ?><div>Categroia nu poate fi stearsa. Are asociate competitii/participanti.</div><?php    
                }
                else
                {
                    ?><a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>categories/delete_category/<?php echo $category['category_id'] ?>'" class="delete"><?php echo $this->lang->line('delete'); ?></a><?php
                }
            }
            ?>		
        </td>    
	</tr>
	<?php
}
?>
</table>