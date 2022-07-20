<?php $this->load->helper('form');?>
<form action="" method="post">
<table class="list_table">
<?php
foreach($sections as $section)
{
	$arr = array('admin_role_id' => $role_id, 'admin_section_id' => $section['admin_section_id'], 'admin_right_id' => 0);		
	if(in_array($arr,$permissions)) 
		$default = TRUE;
	else	
		$default = FALSE;
	
	$i=0;
	foreach($section['rights'] as $right)
	{
		if($this->global_admin->has_access('right',$right['admin_right_url']))
		{
			$i++;
		}	
	}
	if(count($section['rights']) > $i)
		$dibabled = 'disabled = "disabled"';
	else
		$dibabled = '';	
	
	?>
	<tr>
        <td>	
            <?php echo $section['admin_section_name']?>		
        </td> 
        <td>	
            <input type="checkbox" name="section_<?php echo $section['admin_section_id']?>" id="section_<?php echo $section['admin_section_id']?>" value="1" onchange="if(this.checked == true) document.getElementById('div_rights_<?php echo $section['admin_section_id']?>').style.display='none'; else document.getElementById('div_rights_<?php echo $section['admin_section_id']?>').style.display='block'; " <?php  echo set_checkbox('section_'.$section['admin_section_id'], '1', $default);  echo $dibabled; ?>/>
			<?php echo $this->lang->line('all_rights')?>		
        </td>  		  
        <td>
            <div id="div_rights_<?php echo $section['admin_section_id']?>" style="display:<?php if( (!isset($_POST['Edit']) && in_array($arr,$permissions)) || (isset($_POST['section_'.$section['admin_section_id']]) && $_POST['section_'.$section['admin_section_id']] == '1') ) echo 'none'; else echo 'block'; ?>">				
            <?php	            
            foreach($section['rights'] as $right)
            {
                if($this->global_admin->has_access('right',$right['admin_right_url']))
                {
                    $arr = array('admin_role_id' => $role_id, 'admin_section_id' => $section['admin_section_id'], 'admin_right_id' => $right['admin_right_id']);
                            
                    if(in_array($arr,$permissions)) 
                        $default = TRUE;
                    else	
                        $default = FALSE;
                    ?>
                    <input type="checkbox" name="right_<?php echo $right['admin_right_id']?>" id="right_<?php echo $right['admin_right_id']?>" value="1" <?php  echo set_checkbox('right_'.$right['admin_right_id'], '1', $default); ?>/>
					<?php echo $right['admin_right_name']?><br/>
                    <?php
                }	
            }
            ?>
            </div>
        </td>
	</tr>
	<?php
}
?>
</table>
<p align="left"><input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line('save')?>"/></p>
</form>
