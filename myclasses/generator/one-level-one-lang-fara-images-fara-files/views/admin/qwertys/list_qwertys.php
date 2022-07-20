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
        <th><?php echo $sort_label['qwerty_id'];?>Id</th>	
        <th><?php echo $this->lang->line('qwerty_name')?></th>
        <th><?php echo $this->lang->line('view_qwerty')?></th>
        <th><?php echo $this->lang->line('qwerty_image')?></th>
        <th><?php echo $this->lang->line('qwerty_banner')?></th>
        <th><?php echo $sort_label['order'];?><?php echo $this->lang->line('order')?></th>
        <th><?php echo $sort_label['active'];?><?php echo $this->lang->line('status')?></th>               
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
                <?php echo $qwerty['name'];	?>
            </td>	
            <td>
                <a href="<?php echo $qwerty_url?>" target="_blank"><?php echo $this->lang->line('view_qwerty');?></a>
            </td>	
            <?php
			/*
            <td>
            	<?php
                //image	
                $file_name		= $qwerty["image"];
                $file_path 		= base_path()."uploads/qwertys/images/".$file_name;
                $file_url 		= file_url()."uploads/qwertys/images/".$file_name;
				$file_url_th	= file_url()."uploads/qwertys/images/".get_thumb_name($file_name);
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image">
                        <img src="<?php echo $file_url_th?>" width="70"/>                        
                    </a>
					<?php                                        
                }				
				?>
            </td>
			*/
			?>
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
                    <div>
                        <a href="<?php echo admin_url()?>qwertys/edit_qwerty/<?php echo $qwerty["qwerty_id"] ?>" class="edit">
                            <?php echo $this->lang->line("edit"); ?>
                        </a>
                    </div>
                    <?php
                }     				             
                if($this->admin_access["delete_qwerty"])	
                {
                    ?>
                    <div>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>qwertys/delete_qwerty/<?php echo $qwerty["qwerty_id"] ?>'" class="delete">
                            <?php echo $this->lang->line("delete"); ?>
                        </a>
                    </div>
                    
                    <div>
                    	<input type="checkbox" name="item[]" value="<?php echo $qwerty["qwerty_id"]?>" />
						<?php echo $this->lang->line("delete"); ?>
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
<?php
//delete all
if($this->admin_access["delete_qwerty"])	
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