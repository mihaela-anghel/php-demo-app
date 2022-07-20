<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_age_category'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>age_categories/add_age_category">
				<?php echo $this->lang->line('add_age_category'); ?>
			</a>
		</li>
		<?php
    }    
    ?>         
</ul>

<!--Listing-->
<table class="list_table">
<tr>	
    <th><?php echo $sort_label['t1.age_category_id'];?>Id</th>	
    <th><?php echo $sort_label['t2.age_category_name'];?><?php echo $this->lang->line('age_category_name')?></th>
    <th><?php echo $this->lang->line('age_category_age')?></th>
    <!--<th><?php echo $this->lang->line('image')?></th>
    <th><?php echo $this->lang->line('banner')?></th>-->
    <th><?php echo $this->lang->line('items')?></th>
    <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
    <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>
    <th><?php echo $this->lang->line('actions')?></th>
</tr>
<?php  
if(!$age_categories)
{
	?><tr><td colspan="8" align="center"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}
//$nr=0;
foreach($age_categories as $age_category)
{
	?>
	<tr>	
        <td><?php echo $age_category['age_category_id']?></td>
        <td>
            <?php 
            /* for($i = 0; $i < ($age_category['level']*6) ; $i++)	echo "&nbsp;"; */	
            echo $age_category['age_category_name'];	
            ?>
        </td>	
        <td>
            <?php echo $age_category['min_age']?> - <?php echo $age_category['max_age']?> <?php echo $this->lang->line('age_category_years')?>
        </td>
        <?php
		/*
        <td>
            <?php
            //image	
            $image = $age_category['image'];
            $image_path = $this->config->item('base_path').'uploads/age_categories/images/'.$image;
            $image_url = $this->config->item('base_url').'uploads/age_categories/images/'.$image;
            
            if($image != '' && file_exists($image_path))
            {
                ?>
                 &raquo; <a href="<?php echo $image_url?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>age_categories/delete_file/image/<?php echo $age_category['age_category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>age_categories/upload_file/image/<?php echo $age_category['age_category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>
        </td>
        <td>
            <?php
            //banner
            $banner = $age_category['banner'];
            $banner_path = $this->config->item('base_path').'uploads/age_categories/banners/'.$banner;
            $banner_url = $this->config->item('base_url').'uploads/age_categories/banners/'.$banner;
            
            if($banner != '' && file_exists($banner_path))
            {
                ?>
                 &raquo; <a href="<?php echo $banner_url?>" class="fancybox_image"><?php echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>age_categories/delete_file/banner/<?php echo $age_category['age_category_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>age_categories/upload_file/banner/<?php echo $age_category['age_category_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>     	
        </td>
		*/?>
        <td>	
            &raquo; 
            <a href="<?php echo $this->config->item('admin_url')?>age_categories/set_search_session/competitions/age_category_id/<?php echo $age_category['age_category_id']; ?>">
                <?php echo $age_category['competitions_number']; ?> <?php echo $this->lang->line('age_category_competitions')?>
            </a>

            &raquo; 
            <?php echo $age_category['participants_number']; ?> <?php echo $this->lang->line('age_category_participants')?>            
        </td>
        <td><input type="text" size="2" class="input" value="<?php echo $age_category['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>age_categories/change_age_category/<?php echo $age_category['age_category_id'] ?>/order/'+this.value" style="width:30px;"/></td>			
        <td>
        	<?php
			//active				
			$aux = array(	"field" 	=> "active",
							"labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
							"values" 	=> array(1, 0),
							"classes" 	=> array("access", "noaccess"),
							"url" 		=> admin_url()."age_categories/change_age_category/".$age_category["age_category_id"]	
							);
			if($this->admin_access['edit_age_category'])	
			{                    								
				?>
				<span class="hide"><?php echo json_encode($aux)?></span>
				<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($age_category[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
					<?php echo ($age_category[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
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
            if($this->admin_access['edit_age_category'])
            {
                ?><a href="<?php echo $this->config->item('admin_url')?>age_categories/edit_age_category/<?php echo $age_category['age_category_id'] ?>" class="edit"><?php echo $this->lang->line('edit'); ?></a><?php
            }	
			if($this->admin_access['delete_age_category'])
            {
                if($age_category['competitions_number'] > 0 || $age_category['participants_number'] > 0)
                {
                    ?><div>Categroia nu poate fi stearsa. Are asociate competitii/participanti.</div><?php    
                }
                else
                {                    
                    ?><a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>age_categories/delete_age_category/<?php echo $age_category['age_category_id'] ?>'" class="delete"><?php echo $this->lang->line('delete'); ?></a><?php
                }
            }
            ?>		
        </td>    
	</tr>
	<?php
}
?>
</table>