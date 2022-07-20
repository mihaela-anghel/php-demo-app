<p>
<!--Link add member-->
<?php 
if($admin_acces['add_member'])	
{	
	?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>members/add_member"><?php echo $this->lang->line('member_add_member'); ?></a><?php
}
?>
<!--Link search-->
 &nbsp; &raquo; <a href="#" onclick="jQuery('#div_search').slideToggle('slow');"><?php echo $this->lang->line('search'); ?></a></p>


<!--search form-->
<div id="div_search" style="display:<?php if(isset($_SESSION[$section_name]['search_by'])) echo 'block'; else echo 'none';?>">
<form action="" method="post" name="search" class="form">
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="search_table">	
<?php
$i = 3;
foreach($search_by as $key=>$search)
{
	if($key%$i == 0) { ?><tr><?php } 	
	?>	
	<td align="left"><?php echo $search['field_label']; ?>:</td>
	<td><?php	
	
	//input*****************************
	if($search['field_type'] == 'input')
	{		
		?><input type="text" name="<?php echo $search['field_name']?>" value="<?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']])) echo $_SESSION[$section_name]['search_by'][$search['field_name']]?>" class="input" /><?php
	}
	
	//select****************************
	if($search['field_type'] == 'select')
	{				
		?><select name="<?php echo $search['field_name']?>" class="select">
		<option value="">-</option>
		<?php
		foreach($search['field_values'] as $option_value => $option_label)
		{
			?><option value="<?php echo $option_value?>" <?php  if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && $option_value == $_SESSION[$section_name]['search_by'][$search['field_name']]) echo 'selected';?>><?php echo $option_label?></option><?php
		}
		?></select><?php
	}
	
	//checkbox****************************	
	if($search['field_type'] == 'checkbox')
	{										
		foreach($search['field_values'] as $option_value => $option_label)
		{
			?><input type="checkbox" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php  if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked';?> /><?php echo $option_label?><?php			
		}		
	}	
	
	//radio****************************
	if($search['field_type'] == 'radio')
	{				
		foreach($search['field_values'] as $option_value => $option_label)
		{
			?><input type="radio" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php  if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked';?> /><?php echo $option_label?><?php
		}		
	}
	
	?></td><?php
	if($key == (count($search_by)-1) && $key%$i != $i-1) 
	{
		for($j=0;$j<=$i-2;$j++) {	echo '<td></td>';	}
		echo '</tr>';
	}						
	if($key%$i == ($i-1)) 
	{ 
		echo '</tr>';
	}
}
?>
<tr><td colspan="<?php echo ($i*2)?>" align="right">
<input type="submit" name="Search"  value="<?php echo $this->lang->line('search')?>" class="button"/>
<input type="submit" name="Reset"   value="<?php echo $this->lang->line('cancel')?>" class="button" onclick="jQuery('#div_search').slideToggle('slow');"/>
</td></tr>
</table>
</form>
</div>

<!--Pagination-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed; ?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>

<!--Listing-->
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="bgtable">
<tr>
<th><?php echo $sort_label['member_id'];	?>Id</th>
<!--<th><?php echo $this->lang->line('member_image')?></th>
<th><?php echo $sort_label['username'];		?><?php echo $this->lang->line('member_username')?></th>-->
<th><?php echo $sort_label['first_name'];	?><?php echo $this->lang->line('member_name')?></th>
<th><?php echo $sort_label['company_name'];	?><?php echo $this->lang->line('member_company_name')?></th>
<th><?php echo $this->lang->line('member_address')?></th>
<th width="300"><?php echo $this->lang->line('member_contact')?></th>
<th><?php echo $sort_label['active'];?><?php echo $this->lang->line('status')?></th>		
<th width="150"><?php echo $this->lang->line('actions')?></th>
</tr>
<?php  
if(!$members)
{
	?><tr><td colspan="9" align="center"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}
foreach($members as $member)
{
	?>
	<tr>
	<td><?php echo $member['member_id']?></td>
    <?php
	/*
    <td align="center">
	<?php
	//image
	$has_image = 1;
	
	$image_url 	= $this->config->item('base_url').'uploads/members/'.$member['image'];
	$image_path = $this->config->item('base_path').'uploads/members/'.$member['image'];
	
	$thumb_url 	= $this->config->item('base_url').'uploads/members/'.$member['thumb'];
	$thumb_path = $this->config->item('base_path').'uploads/members/'.$member['thumb'];		
		
	if($member['image'] == "" || !file_exists($image_path))		
	{
		$image_url 	= $this->config->item('base_url').'uploads/nopictures/nopicture.gif';						
		$has_image = 0;
	}		
	if($member['thumb'] == "" || !file_exists($thumb_path))		
	{
		$thumb_url 	= $this->config->item('base_url').'uploads/nopictures/nopicture_90.gif';													
		$has_image = 0;
	}
	?>
    
    Display image        
    <a href="<?php echo $image_url?>" class="fancybox_image">
    <img id="picture" src="<?php echo $thumb_url;?>?<?php echo uniqid('')?>" alt="" border="0"/>
    </a>
    <div>
	<?php
    if($has_image)
	{
		?> &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>members/delete_file/image/<?php echo $member['member_id']?>'"><?php echo $this->lang->line('delete')?></a><?php
	}
	else
	{
		?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>members/upload_file/image/<?php echo $member['member_id']?>" class = "fancybox_iframe" rel="400,150"><?php echo $this->lang->line('upload')?></a><?php
	}
	?>
    </div>
    </td>
	*/
	?>
    <!--<td><?php echo $member['username']?></td>-->
	<td><?php echo $member['first_name'].' '.$member['last_name']?></td>	
	<td <?php if($member['company_name']) {	?> onmouseover="document.getElementById('div_company_<?php echo $member['member_id']?>').style.display='block';" onmouseout="document.getElementById('div_company_<?php echo $member['member_id']?>').style.display='none';" <?php } ?> >
	<?php 
	// company
	if($member['company_name'])			echo '<strong>'.$member['company_name'].'</strong>';	
	?>
	<div id="div_company_<?php echo $member['member_id']?>" style="display:none; position:absolute;" class="div_absolute"><?php
	if($member['company_name'])			echo '<em>'.$this->lang->line('member_company_name').' 		</em>: '.$member['company_name'].'  		<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_vat_number'])	echo '<em>'.$this->lang->line('member_company_vat_number').'	</em>: '.$member['company_vat_number'].' 	<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_reg_com'])		echo '<em>'.$this->lang->line('member_company_reg_com').' 		</em>: '.$member['company_reg_com'].'  		<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_bank'])			echo '<em>'.$this->lang->line('member_company_bank').' 		</em>: '.$member['company_bank'].'  		<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_bank_account'])	echo '<em>'.$this->lang->line('member_company_bank_account').' </em>: '.$member['company_bank_account'].'  <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_phone'])		echo '<em>'.$this->lang->line('member_company_phone').' 		</em>: '.$member['company_phone'].'  		<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_fax'])			echo '<em>'.$this->lang->line('member_company_fax').'			</em>: '.$member['company_fax'].'  			<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_web'])			echo '<em>'.$this->lang->line('member_company_web').' 			</em>: <a href = "'.prep_url($member['company_web']).'" target="_blank">'.$member['company_web'].'</a> 			<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_position'])		echo '<em>'.$this->lang->line('member_company_position').'		</em>: '.$member['company_position'].'  	<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	if($member['company_description'])	echo '<em>'.$this->lang->line('member_company_description').' 	</em>: '.$member['company_description'].' 	<hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;">';
	?>
	</div>
	</td>
	<td>
	<?php 
	// address
	echo $member['address'];
	if($member['postal_code'])	
	echo ', '.$this->lang->line('member_postal_code').' '.$member['postal_code'];
	echo ', '.$member['city'];
	echo ', '.$member['region'];
	//echo ', '.$member['country'];			
	?>
	</td>   
	<td>
	<?php 
	// contact
	if($member['email'])	echo '<em>'.$this->lang->line('member_email').' </em>: <a href="mailto:'.$member['email'].'">'.$member['email'].'</a>  <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;"/>';
	if($member['mobile'])	echo '<em>'.$this->lang->line('member_mobile').'</em>: '.$member['mobile'].' <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;"/>';
	if($member['phone'])	echo '<em>'.$this->lang->line('member_phone').' </em>: '.$member['phone'].'  <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;"/>';
	
	$this->load->helper('date');
	if($member['registration_date'] != "0000-00-00 00:00:00")	
		echo '<em>'.$this->lang->line('member_registration_date').' </em>: '.custom_date($member['registration_date'],$this->admin_default_lang).'  <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;"/>';
	if($member['last_login'] != "0000-00-00 00:00:00")	
		echo '<em>'.$this->lang->line('member_last_login').' </em>: '.custom_date($member['last_login'],$this->admin_default_lang).'  <hr size = "1" style="color:#CCCCCC; width:100%; margin:0px;"/>';
	?>
	</td>
	<td>
		<?php 
		// status
		//if($admin_acces['edit_member'])  
		//{	
			?><a href="<?php echo $this->config->item('admin_url')?>members/change/<?php echo $member['member_id'] ?>/active/<?php echo $member['active'] ?>/"> <?php 
			if($member['active'] == '1') echo $this->lang->line('active'); else echo $this->lang->line('inactive');
			?></a><?php
		//} 
		//else
		//{
			//if($member['active'] == '1') echo $this->lang->line('active'); else echo $this->lang->line('inactive');
		//}
		?>			
	</td>    
	<td>	
	<?php
	// actions	
	?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>members/set_search_session/orders/member_id/<?php echo $member['member_id'] ?>"><?php echo $this->lang->line('member_orders'); ?> (<?php echo $member['orders_number']?>)</a><br/><?php
	
	if($admin_acces['edit_member'])	
	{
		?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>members/edit_member/<?php echo $member['member_id'] ?>"><?php echo $this->lang->line('edit'); ?></a><?php
	}	
	if($admin_acces['delete_member'])	
	{
		if($member["removed"] == "1")
		{
			?><br/>&raquo; <a href="#" onclick="if(confirm('Sigur anulati stergerea acestui membru?')) window.location='<?php echo $this->config->item('admin_url') ?>members/change/<?php echo $member['member_id']?>/removed/<?php echo $member['removed']?>/'">Anuleaza stergerea</a><?php
		}
		else
		{
			if($member["orders_number"] > 0)
			{
				/*?><br/> &raquo; <a href="#" onclick="alert('<?php echo $this->lang->line('member_cant_delete_has_orders'); ?>\nAcest user va fi doar marcat ca fiind sters')"><?php echo $this->lang->line('delete'); ?></a><?php*/
				?><br/> &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('member_cant_delete_has_orders'); ?>\nAcest user va fi doar marcat ca fiind sters! Continuati?')) window.location='<?php echo $this->config->item('admin_url')?>members/delete_member/<?php echo $member['member_id'] ?>'"><?php echo $this->lang->line('delete'); ?></a><?php
			}
			else
			{		
				?><br/> &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>members/delete_member/<?php echo $member['member_id'] ?>'"><?php echo $this->lang->line('delete'); ?></a><?php
			}
		}	
	}
	?>		
	</td>    
	</tr>
	<?php
}//end foreach
?>
</table>

<!--Pagination-->
<p align="left" style="position:absolute" class="small"><?php echo $results_displayed; ?></p>
<p align="right"><?php echo $pagination;?> <?php echo $per_page_select;?></p>