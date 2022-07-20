<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_testimonial'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>testimonials/add_testimonial">
				<?php echo $this->lang->line('add_testimonial'); ?>
			</a>
		</li>
		<?php
    }    
    ?> 
    <li>
        <a href="javascript:void(0)" onclick="$('#search').slideToggle('fast');">
            <?php echo $this->lang->line('search'); ?>
        </a>
    </li>         
</ul>

<!--SEARCH FORM-->
<div id="search" style="display:<?php if(isset($_SESSION[$section_name]['search_by'])) echo 'block'; else echo 'none';?>">
<form action="" method="post">
<table>	
	<?php
    $i = 4;
    foreach($search_by as $key=>$search)
    {
        if($key%$i == 0) { ?><tr><?php } 	
        ?>	
        <th>
            <?php echo $search['field_label']; ?>:
        </th>
        <td>
            <?php		
            //input
            if($search['field_type'] == 'input')
            {		
                ?>
                <input type="text" name="<?php echo $search['field_name']?>" value="<?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']])) echo $_SESSION[$section_name]['search_by'][$search['field_name']]?>"/>
                <?php
				if(substr_count($search['field_name'], 'date'))
				{
					?>
					<script type="text/javascript" language="javascript">
						$(document).ready(function(){
							$('input[name="<?php echo $search['field_name'];?>"]').attachDatepicker({ rangeSelect: false, firstDay: 1, dateFormat: 'yy-mm-dd' }); 
						});
					</script> 
					<?php
				}	
            }
            
            //select
            if($search['field_type'] == 'select')
            {				
                ?>
                <select name="<?php echo $search['field_name']?>">
                    <option value="">&nbsp;</option>
                    <?php
                    foreach($search['field_values'] as $option_value => $option_label)
                    {
                        $aux 			= explode("-",$option_value);
						$option_value 	= $aux[0];
						$level			= (isset($aux[1])?$aux[1]:0);
						?><option value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && $option_value == $_SESSION[$section_name]['search_by'][$search['field_name']]) echo 'selected="selected"';?>><?php echo str_repeat("&nbsp;",($level*6))?><?php echo $option_label?></option><?php
                    }
                    ?>
                </select>
                <?php
            }
            
            //checkbox
            if($search['field_type'] == 'checkbox')
            {										
                foreach($search['field_values'] as $option_value => $option_label)
                {
                    ?><input type="checkbox" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked="checked"';?> /><?php echo $option_label?><?php			
                }		
            }	
            
            //radio
            if($search['field_type'] == 'radio')
            {				
                foreach($search['field_values'] as $option_value => $option_label)
                {
                    ?><input type="radio" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked="checked"';?> /><?php echo $option_label?><?php
                }		
            }	
            ?>
        </td>
        <?php
		if($key+1 == count($search_by)) 
		{
			for($j=1; $j<=($i-1-($key%$i)); $j++) 
			{	
				if($j < ($i-1-($key%$i)))
				{
					if(count($search_by) > $i)
					{
						?><th></th><td></td><?php 
					}
				}
				else
				{
					?>
                    <th></th>
                    <td>
                    	<input type="submit" name="Search" value="<?php echo $this->lang->line('search')?>"/>
						<input type="submit" name="Reset"  value="<?php echo $this->lang->line('cancel')?>"/>
                    </td>
					<?php
				}
			}			
			?></tr><?php
		}
		else if(($key+1)%$i == 0) 
		{ 
			echo "</tr>";			
		}	
		if(($key+1)%$i == 0 && !isset($search_by[$key+1]))
		{
			?>
			<tr>
				<td colspan="<?php echo ($i*2)?>" align="right">
					<input type="submit" name="Search" value="<?php echo $this->lang->line('search')?>"/>
					<input type="submit" name="Reset"  value="<?php echo $this->lang->line('cancel')?>"/>
				</td>
			</tr>
			<?php
		}	       
    }
    ?>  
</table>
</form>
</div>

<?php 
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } 
?>

<!--PAGINATION-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed;?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>

<!--LISTING-->
<form action="" method="post" name="listForm" onsubmit="if(!confirm('<?php echo $this->lang->line('confirm')?>')) return false;">
<table class="list_table">
    <tr>
        <th><?php echo $sort_label['t1.testimonial_id'];?>Id</th>	
        <th><?php echo $this->lang->line('testimonial_person_name')?></th>        
        <th><?php echo $this->lang->line('testimonial_image')?></th>
        <!--<th><?php echo $this->lang->line('testimonial_banner')?></th>-->
        <th width="400"><?php echo $this->lang->line('testimonial_description')?></th>
        <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
        <!-- <th><?php echo $this->lang->line('testimonial_on_home')?></th> -->
        <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>                       
        <th><?php echo $this->lang->line('actions')?></th>
    </tr>
	<?php
    if(!$testimonials)
    {	
        ?><tr><td colspan="8"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($testimonials as $testimonial)
    {
        $testimonial_url = base_url().$this->admin_default_lang."/".$testimonial['url_key'];
        ?>
        <tr>
            <td>
                <?php echo $testimonial['testimonial_id']?>
            </td>
            <td>            
                <div><strong><?php echo $testimonial['person_name'];?></strong></div>
                <div><?php echo $testimonial['function'];?></div>
                <div><?php echo $testimonial['company'];?></div>                
            </td>                        
            <td>        
                <?php					
                //image	
                $file_name		= $testimonial["image"];
                $file_path 		= base_path()."uploads/testimonials/".$file_name;
                $file_url 		= file_url()."uploads/testimonials/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image">
                        <?php //echo $this->lang->line("view")?>                        
                        <img src="<?php echo $file_url?>" width="50"/>
                    </a>
					<?php
                    
                    if($this->admin_access["edit_testimonial"])
                    {                        
                        ?>
                        <div>
                            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>testimonials/delete_file/image/<?php echo $testimonial["testimonial_id"]?>'" class="delete">
                                <?php echo $this->lang->line("delete")?>
                            </a>
                        </div>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_testimonial"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>testimonials/upload_file/image/<?php echo $testimonial["testimonial_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <!--<td>        
                <?php	
                //banner	
                $file_name		= $testimonial["banner"];
                $file_path 		= base_path()."uploads/testimonials/banners/".$file_name;
                $file_url 		= file_url()."uploads/testimonials/banners/".$file_name;                
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image go">
                        <?php echo $this->lang->line("view")?>                       
                    </a>
                    <?php
					
                    if($this->admin_access["edit_testimonial"])
                    {                       
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>testimonials/delete_file/banner/<?php echo $testimonial["testimonial_id"]?>'">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_testimonial"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>testimonials/upload_file/banner/<?php echo $testimonial["testimonial_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>-->
            <td>            
                <?php echo $testimonial['description'];	?>
            </td>
            <td>
                <input type="text" value="<?php echo $testimonial["order"]?>" onblur="window.location='<?php echo admin_url()?>testimonials/change_testimonial/<?php echo $testimonial["testimonial_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>        
            <!-- <td>
                <?php
                //on_home				
                $aux = array(	"field" 	=> "on_home",
                                "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."testimonials/change_testimonial/".$testimonial["testimonial_id"]	
                                );
                if($this->admin_access['edit_testimonial'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td> -->
            <td>
                <?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."testimonials/change_testimonial/".$testimonial["testimonial_id"]	
                                );
                if($this->admin_access['edit_testimonial'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($testimonial[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>                  
            <td>
                <?php
                //actions
                if($this->admin_access["edit_testimonial"])	
                {
                    ?>
                    <span>
                        <a href="<?php echo admin_url()?>testimonials/edit_testimonial/<?php echo $testimonial["testimonial_id"] ?>" class="edit">
                            <?php echo $this->lang->line("edit"); ?>
                        </a>
                    </span>
                    <?php
                }     				         
                if($this->admin_access["delete_testimonial"])	
                {
                    ?>
                    <span>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>testimonials/delete_testimonial/<?php echo $testimonial["testimonial_id"] ?>'" class="delete">
                            <?php echo $this->lang->line("delete"); ?>
                        </a>
                    </span>
                    
                    <span>
                    	<input type="checkbox" name="item[]" value="<?php echo $testimonial["testimonial_id"]?>" />
						<?php echo $this->lang->line("delete"); ?>
                    </span>
                    <?php
                }
                ?>			        	
            </td>    
        </tr>
        <?php	
	}	
    ?>
</table>
<?php
//delete all
if($this->admin_access["delete_testimonial"])	
{
	?>
	<p align="right">
		<input type="button" onclick="checkAll(document.listForm.elements['item[]']);"   value="<?php echo $this->lang->line('select_all')?>" />
		<input type="button" onclick="uncheckAll(document.listForm.elements['item[]']);" value="<?php echo $this->lang->line('deselect_all')?>" />
		<input type="submit" name="DeleteSelected" value="<?php echo $this->lang->line('delete_selected')?>" class="button"/>                       
	</p>
	<?php
}	
?>
</form>

<!--PAGINATION-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed;?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>