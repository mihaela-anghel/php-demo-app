<script type="text/javascript" language="javascript">
// construiec un array cu toate div-urile care trebuie ascunse
var array_divs = new Array('div_company');
</script>  

<?php
$this->load->helper('form');

if(isset($message)) echo '<p class = "done">'.$message.'</p>';
?>
<div style="width:700px;">
<form action="" method="post">
<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_account_info');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">      
  <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_type')?></td>
    <td align="left">
		<input type="radio" name="type" value="individual" <?php if($member['type'] == 'individual') $default = TRUE; else $default = FALSE; echo set_radio('type','individual',$default); ?>   onclick="if(this.checked) show_div(array_divs,'')" /><?php echo $this->lang->line('member_individual')?>
        <input type="radio" name="type" value="juridical"  <?php if($member['type'] == 'juridical')  $default = TRUE; else $default = FALSE; echo set_radio('type','juridical', $default); ?> 		onclick="if(this.checked) show_div(array_divs,'div_company')" /><?php echo $this->lang->line('member_juridical')?>
	</td>
  </tr>
</table>
</fieldset>

<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_personal_details');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">
   <tr>
    <td align="right"><?php echo $this->lang->line('member_first_name')?></td>
    <td align="left">
	<?php echo form_error('first_name'); ?>
	<input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name', $member['first_name']); ?>" class="input"/>
	*</td>
  </tr>
   <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_last_name')?></td>
    <td align="left">
	<?php echo form_error('last_name'); ?>
	<input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name', $member['last_name']); ?>" class="input"/>
	<input type="hidden" name="cnp" id="cnp" value=""/>
    *</td>
  </tr>  
   <tr>
    <td align="right"><?php echo $this->lang->line('member_cnp')?></td>
    <td align="left">
	<?php echo form_error('cnp'); ?>
	<input type="text" name="cnp" id="cnp" value="<?php echo set_value('cnp', $member['cnp']); ?>" class="input"/></td>
  </tr>

   <tr>
    <td align="right"><?php echo $this->lang->line('member_mobile')?></td>
    <td align="left">
	<?php echo form_error('mobile'); ?>
	<input type="text" name="mobile" id="mobile" value="<?php echo set_value('mobile', $member['mobile']); ?>" class="input"/>*</td>
  </tr>
   <!-- <tr>
    <td align="right"><?php echo $this->lang->line('member_phone')?></td>
    <td align="left">
	<?php echo form_error('phone'); ?>
	<input type="text" name="phone" id="phone" value="<?php echo set_value('phone', $member['phone']); ?>" class="input"/>
	</td>
  </tr>  -->
</table>
</fieldset>

<div id="div_company" style="display:<?php  if(isset($_POST['type'])) $type = $_POST['type']; else $type = $member['type']; if($type == 'juridical') echo 'block'; else echo 'none'; ?>">
<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_company_details');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">  
  <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_company_name')?></td>
    <td align="left">
	<?php echo form_error('company_name'); ?>
	<input type="text" name="company_name" id="company_name" value="<?php echo set_value('company_name', $member['company_name']); ?>" class="input"/>
	*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_vat_number')?></td>
    <td align="left">
	<?php echo form_error('company_vat_number'); ?>
	<input type="text" name="company_vat_number" id="company_vat_number" value="<?php echo set_value('company_vat_number', $member['company_vat_number']); ?>" class="input"/></td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_reg_com')?></td>
    <td align="left">
	<?php echo form_error('company_reg_com'); ?>
	<input type="text" name="company_reg_com" id="company_reg_com" value="<?php echo set_value('company_reg_com', $member['company_reg_com']); ?>" class="input"/></td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_bank')?></td>
    <td align="left">
	<?php echo form_error('company_bank'); ?>
	<input type="text" name="company_bank" id="company_bank" value="<?php echo set_value('company_bank', $member['company_bank']); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_bank_account')?></td>
    <td align="left">
	<?php echo form_error('company_bank_account'); ?>
	<input type="text" name="company_bank_account" id="company_bank_account" value="<?php echo set_value('company_bank_account', $member['company_bank_account']); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_phone')?></td>
    <td align="left">
	<?php echo form_error('company_phone'); ?>
	<input type="text" name="company_phone" id="company_phone" value="<?php echo set_value('company_phone', $member['company_phone']); ?>" class="input"/></td>
  </tr> 
  <!--<tr>
    <td align="right"><?php echo $this->lang->line('member_company_fax')?></td>
    <td align="left">
	<?php echo form_error('company_fax'); ?>
	<input type="text" name="company_fax" id="company_fax" value="<?php echo set_value('company_fax', $member['company_fax']); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_web')?></td>
    <td align="left">
	<?php echo form_error('company_web'); ?>
	<input type="text" name="company_web" id="company_web" value="<?php echo set_value('company_web', $member['company_web']); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_position')?></td>
    <td align="left">
	<?php echo form_error('company_position'); ?>
	<input type="text" name="company_position" id="company_position" value="<?php echo set_value('company_position', $member['company_position']); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_description')?></td>
    <td align="left">
	<?php echo form_error('company_description'); ?>
	<input type="text" name="company_description" id="company_description" value="<?php echo set_value('company_description', $member['company_description']); ?>" class="input"/></td>
  </tr> --> 
</table>
</fieldset>
</div>

<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_address_info');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">   
  <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_address')?></td>
    <td align="left">
	<?php echo form_error('address'); ?>
	<input type="text" name="address" id="address" value="<?php echo set_value('address', $member['address']); ?>" class="input"/>*
	</td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_city')?></td>
    <td align="left">
	<?php echo form_error('city'); ?>
	<input type="text" name="city" id="city" value="<?php echo set_value('city', $member['city']); ?>" class="input"/>
	*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_postal_code')?></td>
    <td align="left">
	<?php echo form_error('postal_code'); ?>
	<input type="text" name="postal_code" id="postal_code" value="<?php echo set_value('postal_code', $member['postal_code']); ?>" class="input"/></td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_region')?></td>
    <td align="left">
	<?php echo form_error('region'); ?>
	<!--<input type="text" name="region" id="region" value="<?php echo set_value('region', $member['region']); ?>" class="input"/>-->   
    <select name="region" class="select" style="width:130px">
	<option value=""><?php echo $this->lang->line('select')?></option>
	<?php	
	foreach($judete as $judet)
	{		
		if($member['region'] == $judet['judet']) $default = TRUE; else $default = FALSE;
		?><option value="<?php echo $judet['judet']?>" <?php echo set_select('region',$judet['judet'],$default)?> ><?php echo $judet['judet']?></option><?php
	}
	?>
	</select>	
    <input type="hidden" name="country_id" id="country_id" value="175"/>
    </td>
  </tr>  
  <!--<tr>
    <td align="right"><?php echo $this->lang->line('member_country_id')?></td>
    <td align="left">
	<?php echo form_error('country_id'); ?>	
	<select name="country_id" class="select">
	<option value=""><?php echo $this->lang->line('select')?></option>
	<?php	
	foreach($countries as $country)
	{		
		if($member['country_id'] == $country['country_id']) $default = TRUE; else $default = FALSE;
		?><option value="<?php echo $country['country_id']?>" <?php echo set_select('country_id',$country['country_id'],$default)?> ><?php echo $country['country_name']?></option><?php
	}
	?>
	</select>
	*	</td>
  </tr>   -->
</table>
</fieldset>

<!--<table width="500" border="0" cellpadding="3" cellspacing="1"> 
   <tr>
    <td width="160" align="right"></td>
    <td align="left">
	<?php echo form_error('newsletter'); ?>
	<input type="checkbox" name="newsletter" id="newsletter" value= "1" <?php if($member['newsletter'] == '1') $default = TRUE; else $default = FALSE;  echo set_checkbox('newsletter',1,$default); ?> />
	<?php echo $this->lang->line('member_newsletter')?>
	</td>
  </tr>
</table>-->
  
<table width="500" border="0" cellpadding="3" cellspacing="1">     
  <tr>
    <td  width="160" align="right">&nbsp;</td>
    <td align="left">
	<input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line('save');?>" class="button"/>	</td>
  </tr>  
</table> 
</form>
</div>