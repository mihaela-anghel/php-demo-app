<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['add_user'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>users/add_user">
				<?php echo $this->lang->line('add_user'); ?>
			</a>
		</li>
		<?php
    } 
    if($this->admin_access['send_email'])	
    {	
        ?>
        <li>
        	<a href="<?php echo admin_url()?>users/send_email" class="fancybox_iframe" rel="1000,900">
				<?php echo $this->lang->line('user_send_email_to_all_users'); ?>
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
        <th><?php echo $sort_label['user_id'];?>Id</th>	
        <th><?php echo $this->lang->line('user_name')?></th>
        <th><?php echo $this->lang->line('user_image')?></th>
        <th><?php echo $this->lang->line('user_birthday')?></th>
        <th><?php echo $this->lang->line('user_address')?></th>        
        <th><?php echo $this->lang->line('user_school')?></th>       
        <th><?php echo $this->lang->line('user_school_certificate')?></th>  
        <th><?php echo $this->lang->line('user_add_date')?></th>       
        <th>Mesaj afisat in cont</th>
        <th><?php echo $sort_label['active'];?><?php echo $this->lang->line('status')?></th>               
        <th><?php echo $this->lang->line('actions')?></th>
    </tr>
	<?php
    if(!$users)
    {	
        ?><tr><td colspan="8"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($users as $user)
    {
       ?>
        <tr>
            <td>
                <?php echo $user['user_id']?>                
            </td>
            <td>            
                <div><strong><?php echo $user['name'];?></strong></div>
                <div><?php echo $user['email'];?></div>
                <div><?php echo $this->lang->line('user_phone')?>: <?php echo $user['phone'];?></div>
            </td>                      
            <td>        
                <?php					
                //image	
                $file_name		= $user["image"];
                $file_path 		= base_path()."uploads/users/".$file_name;
                $file_url 		= file_url()."uploads/users/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>
					<a href="<?php echo $file_url;?>" class = "fancybox_image">
                        <?php //echo $this->lang->line("view")?>                        
                        <img src="<?php echo $file_url?>" width="50"/>
                    </a>
					<?php
                    
                    if($this->admin_access["edit_user"])
                    {                        
                        ?>
                        <div>
                            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>users/delete_file/image/<?php echo $user["user_id"]?>'" class="delete">
                                <?php echo $this->lang->line("delete")?>
                            </a>	
                        </div>					
                        <?php
                    }
                }
                else if($this->admin_access["edit_user"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>users/upload_file/image/<?php echo $user["user_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>
            <td>            
                <?php echo custom_date($user['birthday'], $this->admin_default_lang);?>                
            </td>  
            <td>            
                <div><?php echo $user['city'];?></div>
                <div><?php echo $user['country'];?></div>              
            </td>
            <td>            
                <div><?php echo $user['school'];?></div> 
                <div><?php echo $this->lang->line('user_guide')?>: <?php echo $user['guide'];?></div>                     
            </td> 
            <td>        
                <?php					
                //school_certificate	
                $file_name		= $user["school_certificate"];
                $file_path 		= base_path()."uploads/users/school_certificates/".$file_name;
                $file_url 		= file_url()."uploads/users/school_certificates/".$file_name;
                if($file_name && file_exists($file_path))
                {
                    ?>                
					<a href="<?php echo $file_url;?>" download style="word-wrap:true">
                        <?php echo $file_name?>                                                
                    </a>
					<?php
                    
                    if($this->admin_access["edit_user"])
                    {                        
                        ?>
                        <div>
                            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>users/delete_file/school_certificate/<?php echo $user["user_id"]?>'" class="delete">
                                <?php echo $this->lang->line("delete")?>
                            </a>
                        </div>						
                        <?php
                    }
                }
                else if($this->admin_access["edit_user"])
                {                   
                    ?>                    
                    <a href="<?php echo admin_url()?>users/upload_file/school_certificate/<?php echo $user["user_id"]?>" class = "fancybox_iframe go" rel="600,150">
                        <?php echo $this->lang->line("upload")?>
                    </a>
                    <?php
                }                                   			                			
                ?>
            </td>    
            <td>
                <small><?php echo $this->lang->line('user_add_date')?>:</small>
                <div><?php echo custom_date($user['add_date'], $this->admin_default_lang);?></div>
                <small><?php echo $this->lang->line('user_last_login_date')?>:</small>
                <div><?php echo custom_date($user['last_login_date'], $this->admin_default_lang);?></div>   
            </td>
            <td>
                <?php echo nl2br($user["admin_message"])?>
            </td>    
            <td>
                <div>
                    <?php
                    //active				
                    $aux = array(	"field" 	=> "active",
                                    "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("access", "noaccess"),
                                    "url" 		=> admin_url()."users/change_user/".$user["user_id"]	
                                    );
                    if($this->admin_access['edit_user'])	
                    {                    								
                        ?>
                        <span class="hide"><?php echo json_encode($aux)?></span>
                        <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($user[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                            <?php echo ($user[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                        </a> 					
                        <?php
                    }
                    else
                    {
                        echo ($user[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                    }

                    //reason
                    if($user["active"] == '0')
                    {
                        ?>Motiv: <p class="error"><?php echo $user["inactive_reason"]?></p><?php
                    }
                    ?>
                </div>

                <hr>

                <div>
                    Activeaza posibilitatea<br>de incarcare fisier proiect:<br>
                    <?php
                    //enable_submit_project_file				
                    $aux = array(	"field" 	=> "enable_submit_project_file",
                                    "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("access", "noaccess"),
                                    "url" 		=> admin_url()."users/change_user/".$user["user_id"]	
                                    );
                    if($this->admin_access['edit_user'])	
                    {                    								
                        ?>
                        <span class="hide"><?php echo json_encode($aux)?></span>
                        <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($user[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                            <?php echo ($user[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                        </a> 					
                        <?php
                    }
                    else
                    {
                        echo ($user[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                    }
                    ?>
                </div>
            </td>                  
            <td>
                <a name="<?php echo $user['user_id']?>" style="margin-top:50px"></a>    
                <?php
                //actions
                if($this->admin_access["edit_user"])	
                {
                    ?>
                    <div>
                        <a href="<?php echo admin_url()?>users/edit_user/<?php echo $user["user_id"] ?>" class="edit">
                            <?php echo $this->lang->line("edit"); ?>
                        </a>
                    </div>
                    <?php
                } 
                if($this->admin_access["change_password"])	
                {
                    ?>
                    <div>
                        <a href="<?php echo admin_url()?>users/change_password/<?php echo $user["user_id"] ?>" class="edit">
                            <?php echo $this->lang->line("user_change_password"); ?>
                        </a>
                    </div>
                    <?php
                } 
                if($this->admin_access["send_email"])	
                {
                    ?>
                    <div>
                        <a href="<?php echo admin_url()?>users/send_email/<?php echo $user["user_id"] ?>" class="go fancybox_iframe" rel="1000,900">
                            <?php echo $this->lang->line("user_send_email"); ?>
                        </a>                          
                    </div>
                    <?php
                }                				             
                if($this->admin_access["delete_user"])	
                {                                        
                    if($user["closed_number"])
                    {
                        ?>
                        <div class="small">
                            Nu poate fi sters.<br/>Exista competitii inchise la care s-a inscris.
                        </div>
                        <?php
                    }
                    else
                    {
                        ?>                       
                        <div>
                            <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>users/delete_user/<?php echo $user["user_id"] ?>'" class="delete">
                                <?php echo $this->lang->line("delete"); ?>
                            </a>
                        </div>
                        
                        <div>
                            <input type="checkbox" name="item[]" value="<?php echo $user["user_id"]?>" />
                            <?php echo $this->lang->line("delete"); ?>
                        </div>

                        <?php
                        if($user["opened_number"])
                        {
                            ?>
                            <div class="small">
                                Stergerea implica si stergerea din competitia deschisa la care s-a inscris.
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div class="small">
                                Nu s-a inscris la nici o competitie.
                            </div>
                            <?php
                        }
                    }
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
if($this->admin_access["delete_user"])	
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