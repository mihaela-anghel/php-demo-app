<script type="text/javascript" language="javascript">
// construiec un array cu toate div-urile care trebuie ascunse
var array_divs = new Array('div_company');
</script>  

<?php
$this->load->helper('form');
?>
<div style="width:700px;">
<form action="" method="post">
<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_account_info');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">  
  <tr>
    <td  width="150" align="right"><?php echo $this->lang->line('member_email')?></td>
    <td align="left">
	<?php echo form_error('email'); ?>
	<input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" class="input"/>
	* </td>
  </tr>
  <!--<tr>
    <td  width="150" align="right"><?php echo $this->lang->line('member_username');?></td>
    <td align="left">
	<?php echo form_error('username'); ?>
	<input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" class="input"/>
	* </td>
  </tr>-->
  <tr>
    <td  width="150" align="right"><?php echo $this->lang->line('member_password')?></td>
    <td align="left">
	<script src="<?php echo base_url() ?>js/password_strenght.js" type="text/javascript"></script>
	<?php echo form_error('password'); ?>
	<input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" class="input" onkeyup="runPassword(this.value, 'password');"/>
	* 
	<div style="width: 100px;">
    	<div id="password_text" style="font-size: 10px;"></div>
    	<div id="password_bar"  style="font-size: 1px; height: 2px; width: 0px; border: 0px solid white;"></div>
    </div>
	</td>
  </tr>
  <tr>
    <td  width="150" align="right"><?php echo $this->lang->line('member_confirm_password')?></td>
    <td align="left">	
	<?php echo form_error('confirm_password'); ?>
	<input type="password" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" class="input"/>
	* </td>
  </tr>  
  <tr>
    <td align="right"><?php echo $this->lang->line('member_type')?></td>
    <td align="left">
		<input type="radio" name="type" value="individual" <?php echo set_radio('type','individual',TRUE); ?>   onclick="if(this.checked) show_div(array_divs,'')" /><?php echo $this->lang->line('member_individual')?>
        <input type="radio" name="type" value="juridical"  <?php echo set_radio('type','juridical'); ?> 		onclick="if(this.checked) show_div(array_divs,'div_company')" /><?php echo $this->lang->line('member_juridical')?>
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
	<input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" class="input"/>
	*</td>
  </tr>
   <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_last_name')?></td>
    <td align="left">
	<?php echo form_error('last_name'); ?>
	<input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" class="input"/>
    <input type="hidden" name="cnp" id="cnp" value=""/>
	*</td>
  </tr>
  <?php  /*?>
   <tr>
    <td align="right"><?php echo $this->lang->line('member_cnp')?></td>
    <td align="left">
	<?php echo form_error('cnp'); ?>
	<input type="text" name="cnp" id="cnp" value="<?php echo set_value('cnp'); ?>" class="input"/></td>
  </tr>
  <?php */ ?>
   <tr>
    <td align="right"><?php echo $this->lang->line('member_mobile')?></td>
    <td align="left">
	<?php echo form_error('mobile'); ?>
	<input type="text" name="mobile" id="mobile" value="<?php echo set_value('mobile'); ?>" class="input"/>*</td>
  </tr>
   <tr>
    <td align="right"><?php echo $this->lang->line('member_phone')?></td>
    <td align="left">
	<?php echo form_error('phone'); ?>
	<input type="text" name="phone" id="phone" value="<?php echo set_value('phone'); ?>" class="input"/>
	</td>
  </tr> 
</table>
</fieldset>

<div id="div_company" style="display:<?php if(isset($_POST['type']) && $_POST['type'] == 'juridical') echo 'block'; else echo 'none'; ?>">
<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_company_details');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">  
  <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_company_name')?></td>
    <td align="left">
	<?php echo form_error('company_name'); ?>
	<input type="text" name="company_name" id="company_name" value="<?php echo set_value('company_name'); ?>" class="input"/>
	*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_vat_number')?></td>
    <td align="left">
	<?php echo form_error('company_vat_number'); ?>
	<input type="text" name="company_vat_number" id="company_vat_number" value="<?php echo set_value('company_vat_number'); ?>" class="input"/>*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_reg_com')?></td>
    <td align="left">
	<?php echo form_error('company_reg_com'); ?>
	<input type="text" name="company_reg_com" id="company_reg_com" value="<?php echo set_value('company_reg_com'); ?>" class="input"/>*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_bank')?></td>
    <td align="left">
	<?php echo form_error('company_bank'); ?>
	<input type="text" name="company_bank" id="company_bank" value="<?php echo set_value('company_bank'); ?>" class="input"/>*</td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_bank_account')?></td>
    <td align="left">
	<?php echo form_error('company_bank_account'); ?>
	<input type="text" name="company_bank_account" id="company_bank_account" value="<?php echo set_value('company_bank_account'); ?>" class="input"/>*</td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_phone')?></td>
    <td align="left">
	<?php echo form_error('company_phone'); ?>
	<input type="text" name="company_phone" id="company_phone" value="<?php echo set_value('company_phone'); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_fax')?></td>
    <td align="left">
	<?php echo form_error('company_fax'); ?>
	<input type="text" name="company_fax" id="company_fax" value="<?php echo set_value('company_fax'); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_web')?></td>
    <td align="left">
	<?php echo form_error('company_web'); ?>
	<input type="text" name="company_web" id="company_web" value="<?php echo set_value('company_web'); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_position')?></td>
    <td align="left">
	<?php echo form_error('company_position'); ?>
	<input type="text" name="company_position" id="company_position" value="<?php echo set_value('company_position'); ?>" class="input"/></td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_company_description')?></td>
    <td align="left">
	<?php echo form_error('username'); ?>
	<input type="text" name="company_description" id="company_description" value="<?php echo set_value('company_description'); ?>" class="input"/></td>
  </tr> 
</table>
</fieldset>
</div>

<fieldset class="fieldset"><legend><?php echo $this->lang->line('member_address_info');?>:</legend>
<table width="500" border="0" cellpadding="3" cellspacing="1">   
  <tr>
    <td width="150" align="right"><?php echo $this->lang->line('member_address')?></td>
    <td align="left">
	<?php echo form_error('address'); ?>
	<input type="text" name="address" id="address" value="<?php echo set_value('address'); ?>" class="input"/>
	</td>
  </tr> 
  <tr>
    <td align="right"><?php echo $this->lang->line('member_city')?></td>
    <td align="left">
	<?php echo form_error('city'); ?>
	<input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="input"/>
	*</td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_postal_code')?></td>
    <td align="left">
	<?php echo form_error('postal_code'); ?>
	<input type="text" name="postal_code" id="postal_code" value="<?php echo set_value('postal_code'); ?>" class="input"/></td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('member_region')?></td>
    <td align="left">
	<?php echo form_error('region'); ?>
	<!--<input type="text" name="region" id="region" value="<?php echo set_value('region'); ?>" class="input"/>   -->
    <select name="region" class="select" style="width:130px">
	<option value=""><?php echo $this->lang->line('select')?></option>
	<?php	
	foreach($judete as $judet)
	{		
		?><option value="<?php echo $judet['judet']?>" <?php echo set_select('region',$judet['judet'])?> ><?php echo $judet['judet']?></option><?php
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
		?><option value="<?php echo $country['country_id']?>" <?php echo set_select('country_id',$country['country_id'])?> ><?php echo $country['country_name']?></option><?php
	}
	?>
	</select>
	*	</td>
  </tr>   -->
</table>
</fieldset>

<table width="500" border="0" cellpadding="3" cellspacing="1"> 
<tr>
    <td width="155" align="right"></td>
    <td align="left">
	<?php echo form_error('terms'); ?>
	<input type="checkbox" name="terms" id="terms" value= "1" <?php echo set_checkbox('terms',1,TRUE); ?> />
	<?php echo $this->lang->line('member_terms')?>
	</td>
  </tr>
  <!--<tr>
    <td align="right"></td>
    <td align="left">
	<?php echo form_error('newsletter'); ?>
	<input type="checkbox" name="newsletter" id="newsletter" value= "1" <?php echo set_checkbox('newsletter',1); ?> />
	<?php echo $this->lang->line('member_newsletter')?>
	</td>
  </tr>-->
</table>
  
<table width="500" border="0" cellpadding="3" cellspacing="1">     
  <tr>
    <td  width="160" align="right">&nbsp;</td>
    <td align="left">
	<input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line('add');?>" class="button"/>	</td>
  </tr>  
</table> 
</form>
</div>