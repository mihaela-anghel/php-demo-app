<?php
//SUBMENU
require_once("menu.php");
?>

<!--TINYMCE-->
<script type="text/javascript" src="<?php echo base_url();?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>tinymce/init.js"></script>

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

<form action="" method="post" enctype="multipart/form-data">
<table class="form_table" width="<?php echo $table_width?>">  		
	<tr>
        <th width="<?php echo $th_width?>">
            <?php echo $this->lang->line("arbiter_name"); ?>
        </th>
        <td>
            <input type="text" name="name" id="name" value="<?php echo set_value("name", $arbiter["name"]); ?>" style="width:600px;"/>*
        </td>
    </tr> 
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_type"); ?>
        </th>
        <td>
            <?php echo form_error('type'); ?>
            <input type="text" name="type" id="type" value="<?php echo set_value("type", $arbiter["type"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_function"); ?>
        </th>
        <td>
            <?php echo form_error('function'); ?>
            <input type="text" name="function" id="function" value="<?php echo set_value("function", $arbiter["function"]); ?>"/>
        </td>
    </tr> 
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_company"); ?>
        </th>
        <td>
            <?php echo form_error('company'); ?>
            <input type="text" name="company" id="company" value="<?php echo set_value("company", $arbiter["company"]); ?>"/>
        </td>
    </tr>   
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_country"); ?>
        </th>
        <td>
            <?php echo form_error('country'); ?>
            <input type="text" name="country" id="country" value="<?php echo set_value("country", $arbiter["country"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_city"); ?>
        </th>
        <td>
            <?php echo form_error('city'); ?>
            <input type="text" name="city" id="city" value="<?php echo set_value("city", $arbiter["city"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_school"); ?>
        </th>
        <td>
            <?php echo form_error('school'); ?>
            <input type="text" name="school" id="school" value="<?php echo set_value("school", $arbiter["school"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_email"); ?>
        </th>
        <td>
            <?php echo form_error('email'); ?>
            <input type="text" name="email" id="email" value="<?php echo set_value("email", $arbiter["email"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_phone"); ?>
        </th>
        <td>
            <?php echo form_error('phone'); ?>
            <input type="text" name="phone" id="phone" value="<?php echo set_value("phone"), $arbiter["phone"]; ?>"/>
        </td>
    </tr>
    <tr>    
        <th>
        	<?php echo $this->lang->line('arbiter_image')?>
        </th>
        <td>    
        	<?php echo form_error('file');  ?>
	        <input type="file" name="file" id="file"/>
            <p>JPG, GIF, PNG, max 3 Mb</p>
        </td>
    </tr>             
    <?php
    /*
    <tr>
        <th>
            <?php echo $this->lang->line("arbiter_description"); ?>
        </th>
        <td>
            <textarea name="description" id="description" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("description", $arbiter["description"]); ?></textarea>                        						
        </td>
    </tr>     
	*/?>
	<tr>
		<th>
			<?php echo $this->lang->line("status")?>
		</th>
		<td>
			<?php
			echo form_error("active");
			$active = array(	"0" => $this->lang->line("inactive"),
								"1" => $this->lang->line("active")	);
			?>
			<select name="active">
			<?php
			foreach($active as $value=>$label)
			{
				if($arbiter["active"] == $value) $selected = true; else $selected = false;
				?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$selected); ?>><?php echo $label?></option><?php
			}
			?>
			</select>
		</td>
	</tr>	
    <tr>
        <th>
        <td>
            <input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line("save");?>"/>
        </td>
    </tr>
</table>
</form>