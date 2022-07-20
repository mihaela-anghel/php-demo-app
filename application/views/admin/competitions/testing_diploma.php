<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "150";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php }
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } ?>

<form action="" method="post">
<!--FORM FIELDS THAT NOT DEPENDS ON LANGUGES-->
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
		<th width="<?php echo $th_width?>"></th>
		<td>
			<?php echo form_error("type");?>
			<input type="radio" name="type" value="prize" <?php echo set_radio("type","prize",true); ?> onchange="set_default_values($(this).val())">Diploma
            <input type="radio" name="type" value="certificate" <?php echo set_radio("type","certificate",false); ?> onchange="set_default_values($(this).val())">Certificat de participare
		</td>
	</tr>
	<tr>
		<th>
			<?php echo $this->lang->line("prize")?>
		</th>
		<td>
			<?php echo form_error("prize");?>
			<input type="text" name="prize" value="<?php echo set_value("prize", $this->setting->item["draft_diploma_prize"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_prize"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_prize"]?>">
		</td>
	</tr>
    <tr>
		<th>
			<?php echo $this->lang->line("competition_name")?>
		</th>
		<td>
			<?php echo form_error("competition_name");?>
			<input type="text" name="competition_name" value="<?php echo set_value("competition_name", $this->setting->item["draft_diploma_competition_name"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_competition_name"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_competition_name"]?>">
		</td>
	</tr>
    <tr>
		<th>
			<?php echo $this->lang->line("user_name")?>
		</th>
		<td>
			<?php echo form_error("name");?>
			<input type="text" name="name" value="<?php echo set_value("name", $this->setting->item["draft_diploma_name"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_name"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_name"]?>">
		</td>
	</tr>
    <tr>
		<th>
			<?php echo $this->lang->line("user_school")?>
		</th>
		<td>
			<?php echo form_error("school");?>
			<input type="text" name="school" value="<?php echo set_value("school", $this->setting->item["draft_diploma_school"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_school"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_school"]?>">
		</td>
    </tr>
    <tr>
		<th>
			<?php echo $this->lang->line("user_city")?>
		</th>
		<td>
			<?php echo form_error("city");?>
			<input type="text" name="city" value="<?php echo set_value("city", $this->setting->item["draft_diploma_city"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_city"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_city"]?>">
		</td>
	</tr>
    <tr>
		<th>
			<?php echo $this->lang->line("competition_age_category")?>
		</th>
		<td>
			<?php echo form_error("age_category_name");?>
			<input type="text" name="age_category_name" value="<?php echo set_value("age_category_name", $this->setting->item["draft_diploma_age_category_name"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_age_category_name"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_age_category_name"]?>">
		</td>
	</tr>
    <tr>
		<th>
			<?php echo $this->lang->line("competition_category")?>
		</th>
		<td>
			<?php echo form_error("category_name");?>
			<input type="text" name="category_name" value="<?php echo set_value("category_name", $this->setting->item["draft_diploma_category_name"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_category_name"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_category_name"]?>">
		</td>
	</tr>
    <tr>
		<th>
			Nmar
		</th>
		<td>
			<?php echo form_error("project_number");?>
			<input type="text" name="project_number" value="<?php echo set_value("project_number", $this->setting->item["draft_diploma_project_number"]); ?>" data-diploma = "<?php echo $this->setting->item["draft_diploma_project_number"]?>" data-certificate = "<?php echo $this->setting->item["draft_certificate_project_number"]?>">
		</td>
	</tr>
    <tr>
        <th>
        <td>
            <input type="submit" name="Download" value="Download draft"/>
			<input type="button" name="Reset" value="Reset form" onclick = "reset_from()"/>
        </td>
    </tr>
</table>
</form>

<script>
function set_default_values(type)
{
	if(type == "prize")
		type = "diploma";

	$('table.form_table input[type=text]').each(function(){
		value = $(this).attr('data-'+type);		
		$(this).val(value);
	});
}
function reset_from()
{
	$('table.form_table input[type=text]').each(function(){
		$(this).val('');
	});
}
</script>