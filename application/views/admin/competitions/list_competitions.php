<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_competition'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>competitions/add_competition">
				<?php echo $this->lang->line('add_competition'); ?>
			</a>
		</li>
		<?php
    }    
    ?> 
    <li>
        <a href="<?php echo admin_url()?>competitions/upload_diploma_image" class="fancybox_iframe" rel="900,600">
            Imagini diplome
        </a>
    </li> 
    <li>
        <a href="<?php echo admin_url()?>competitions/testing_diploma" class="fancybox_iframe" rel="800,600">
            <?php echo $this->lang->line('competition_testing_diploma'); ?>
        </a>
    </li>    
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
        <th><?php echo $sort_label['t1.competition_id'];?>Id</th>	
        <th><?php echo $this->lang->line('competition_name')?></th>                              
        <th></th>
        <th></th>
        <th></th>        
        <th><?php echo $this->lang->line('competition_image')?></th>
        <th><?php echo $this->lang->line('competition_banner')?></th>
        <th><?php echo $this->lang->line('competition_code_language_image')?></th>
        <?php /*        
        <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>
        <th><?php echo $this->lang->line('competition_on_home')?></th>
        */?>
        <th><?php echo $sort_label['t1.active'];?><?php echo $this->lang->line('status')?></th>               
        <th><?php echo $this->lang->line('actions')?></th>        
    </tr>
	<?php
    if(!$competitions)
    {	
        ?><tr><td colspan="8"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
	$this->load->helper('date');
    foreach($competitions as $competition)
    {
        $competition_url = base_url()./*$this->admin_default_lang."/".*/$competition['url_key'];
        ?>
        <tr>
            <td>
                <?php echo $competition['competition_id']?>
            </td>
            <td>            
                <div><strong><?php echo $competition['name'];?></strong></div>
                <div><?php echo $this->lang->line("competition_type_".$competition['type']);?></div>    
                <div><?php echo $competition['theme_name'];?></div>  
                <div>
                    <?php
                    foreach($competition["categories"] as $category)
                    {
                        ?><span><?php echo $category["category_name"]?>; </span><?php
                    }
                    ?>
                </div>                
            </td>                       
            <td>    
                <div>
                    <?php
                    foreach($competition["age_categories"] as $age_category)
                    {
                        ?><div><?php echo $age_category["age_category_name"]?></div><?php
                    }
                    ?>
                </div>
            </td> 
            <td>
                <div>
                    <small><?php echo $this->lang->line("competition_start_registration_date");?>:</small><br/> 
                    <?php echo custom_date($competition['start_registration_date'], $this->admin_default_lang);?>
                </div>
                <div>
                    <small><?php echo $this->lang->line("competition_end_registration_date");?>:</small><br/> 
                    <?php echo custom_date($competition['end_registration_date'], $this->admin_default_lang);?>
                </div>
            </td>
            <td>
                <div>
                    <small><?php echo $this->lang->line("competition_end_submit_project_date");?>:</small><br/>
                    <?php echo custom_date($competition['end_submit_project_date'], $this->admin_default_lang);?>
                </div>
                <div>
                    <small><?php echo $this->lang->line("competition_show_results_date");?>:</small><br/> 
                    <?php echo custom_date($competition['show_results_date'], $this->admin_default_lang);?>
                </div>
            </td>         
            <td>        
                <?php					
                //image	
                $file_name		= $competition["image"];
                $file_path 		= base_path()."uploads/competitions/".$file_name;
                $file_url 		= file_url()."uploads/competitions/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>
                    <div>
                        <a href="<?php echo $file_url;?>" class = "fancybox_image">
                            <?php //echo $this->lang->line("view")?>                        
                            <img src="<?php echo $file_url;?>" width="50"/>
                        </a>
                    </div>
					<?php
                    
                    if($this->admin_access["edit_competition"])
                    {                        
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file');?>')) window.location='<?php echo admin_url()?>competitions/delete_file/image/<?php echo $competition['competition_id']?>'; else return false;" class="delete">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_competition"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>competitions/upload_file/image/<?php echo $competition["competition_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>            
            <td>        
                <?php	
                //banner	
                $file_name		= $competition["banner"];
                $file_path 		= base_path()."uploads/competitions/banners/".$file_name;
                $file_url 		= file_url()."uploads/competitions/banners/".$file_name;                
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image">
                        <?php //echo $this->lang->line("view")?>                       
                        <img src="<?php echo $file_url;?>" width="50"/>
                    </a>
                    <?php
					
                    if($this->admin_access["edit_competition"])
                    {                       
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file');?>')) window.location='<?php echo admin_url()?>competitions/delete_file/banner/<?php echo $competition['competition_id']?>'; else return false;" class="delete">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_competition"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>competitions/upload_file/banner/<?php echo $competition["competition_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <td>        
                <?php					
                //code_language_image	
                $file_name		= $competition["code_language_image"];
                $file_path 		= base_path()."uploads/competitions/code_language_images/".$file_name;
                $file_url 		= file_url()."uploads/competitions/code_language_images/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>
                    <div>
                        <a href="<?php echo $file_url;?>" class = "fancybox_code_language_image">
                            <?php //echo $this->lang->line("view")?>                        
                            <img src="<?php echo $file_url;?>" width="50"/>
                        </a>
                    </div>
					<?php
                    
                    if($this->admin_access["edit_competition"])
                    {                        
                        ?>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file');?>')) window.location='<?php echo admin_url()?>competitions/delete_file/code_language_image/<?php echo $competition['competition_id']?>'; else return false;" class="delete">
                            <?php echo $this->lang->line("delete")?>
                        </a>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_competition"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>competitions/upload_file/code_language_image/<?php echo $competition["competition_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <?php
            /*
            <td>
                <input type="text" value="<?php echo $competition["order"]?>" onblur="window.location='<?php echo admin_url()?>competitions/change_competition/<?php echo $competition["competition_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>                                
            */?>            
            <td style="white-space:nowrap">
                <div style="background-color:<?php echo ($competition["on_home"]=="1"?"#E4FFDF":"#ffffff")?>; padding:5px; margin-bottom:5px">
                    <?php echo $this->lang->line("competition_on_home")?>:
                    <?php
                    //on_home				
                    $aux = array(	"field" 	=> "on_home",
                                    "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("", ""),
                                    "url" 		=> admin_url()."competitions/change_competition/".$competition["competition_id"]."/on_home/".($competition["on_home"]=="1"?"0":"1")	
                                    );
                    if($this->admin_access['edit_competition'])	
                    {                    								
                        ?>
                        <a href="<?php echo $aux["url"]?>" class="<?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                            <b style="text-transform:uppercase"><?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?></b>                    
                        </a> 					
                        <?php
                    }
                    else
                    {
                        echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                    }
                    ?>
                </div>

                <div style="background-color:<?php echo ($competition["status"]=="open"?"#E4FFDF":"#999999")?>; padding:5px">    
                    <span><?php echo $this->status_options[$competition["status"]]?></span>
                    <?php
                    //close button
                    if($competition["status"] == "open")
                    {
                        $message_loading = "Se proceseaza...";

                        ?>
                        <button type="button" onclick="if(confirm('Sigur doriti sa testati inchiderea competitiei?\n Veti primi mail-urile de informare pe adresa <?php echo $this->setting->item["testing_email"]?> \nConfirmati?')) { $('#loading<?php echo $competition["competition_id"]?>').html('<img src=<?php echo file_url()?>images/loading.gif width=20> <?php echo $message_loading; ?>'); window.location='<?php echo admin_url().'competitions/close_competition/'.$competition["competition_id"]?>/testing'; } else return false;">
                            Testeaza inchiderea
                        </button>
                        <button type="button" onclick="if(confirm('Sigur doriti sa inchideti competitia?\nOdata inchisa nu va mai putea fi deschisa. Confirmati?')) { $('#loading<?php echo $competition["competition_id"]?>').html('<img src=<?php echo file_url()?>images/loading.gif width=20> <?php echo $message_loading; ?>'); window.location='<?php echo admin_url().'competitions/close_competition/'.$competition["competition_id"]?>'; } else return false;">
                            Inchide
                        </button>
                        <div id="loading<?php echo $competition["competition_id"]?>"></div>
                        <?php 
                    }                
                    ?>
                </div>

                <div style="padding:5px; margin-bottom:5px">    
                    <?php echo $this->lang->line("competition_on_comming_soon")?>:
                    <?php
                    //on_comming_soon				
                    $aux = array(	"field" 	=> "on_comming_soon",
                                    "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("", ""),
                                    "url" 		=> admin_url()."competitions/change_competition/".$competition["competition_id"]."/on_comming_soon/".($competition["on_comming_soon"]=="1"?"0":"1")	
                                    );
                    if($this->admin_access['edit_competition'])	
                    {                    								
                        ?>
                        <a href="<?php echo $aux["url"]?>" class="<?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                            <b style="text-transform:uppercase"><?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?></b>                    
                        </a> 					
                        <?php
                    }
                    else
                    {
                        echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                    }
                    ?>
                </div>
            </td>               
            <?php
            /*                  
            <td>
                <?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."competitions/change_competition/".$competition["competition_id"]	
                                );
                if($this->admin_access['edit_competition'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($competition[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td> 
            */?>                          
            <td style="white-space:nowrap">
                <?php               
                if($this->admin_access['participants'])	
				{	
					?>
					<div>
						<a href="<?php echo admin_url()?>competitions/participants/<?php echo $competition["competition_id"] ?>" class="go">
							<?php echo $this->lang->line("competition_participants_registered"); ?> (<?php echo $competition["participants_number"]?>)
						</a>
					</div>
					<?php
				}
                if($this->admin_access['prizes'])	
				{	
					?>
					<div>
						<a href="<?php echo admin_url()?>competitions/prizes/<?php echo $competition["competition_id"] ?>" class="go">
							<?php echo $this->lang->line("competition_prizes"); ?> (<?php echo $competition["prizes_number"]?>)
						</a>
					</div>
					<?php
				} 	  
                if($this->admin_access["edit_competition"])	
                {
                    ?>
                    <div>
                        <a href="<?php echo admin_url()?>competitions/edit_competition/<?php echo $competition["competition_id"] ?>" class="edit">
                            <?php echo $this->lang->line("edit"); ?>
                        </a>
                    </div>
                    <?php
                }  
                if($this->admin_access["copy_competition"])	
                {
                    ?>
                    <div>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm")?>')) window.location='<?php echo admin_url()?>competitions/copy_competition/<?php echo $competition["competition_id"] ?>'; else return false;" class="delete">
                            Dubleaza
                        </a>                        
                    </div>
                    <?php
                }                  
                if($this->admin_access["delete_competition"])	
                {
                    ?>
                    <div>
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>competitions/delete_competition/<?php echo $competition["competition_id"] ?>'; else return false;" class="delete">
                            <?php echo $this->lang->line("delete"); ?>
                        </a>
                    </div>
                    
                    <div>
                    	<input type="checkbox" name="item[]" value="<?php echo $competition["competition_id"]?>" />
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
if($this->admin_access["delete_competition"])	
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