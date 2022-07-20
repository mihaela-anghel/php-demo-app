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
            <?php echo $this->lang->line("partner_name"); ?>
        </th>
        <td>
            <input type="text" name="name" id="name" value="<?php echo set_value("name", $partner["name"]); ?>" style="width:600px;"/>*
        </td>
    </tr> 
    <tr>    
        <th>
        	<?php echo $this->lang->line('partner_image')?>
        </th>
        <td>    
        	<?php echo form_error('file');  ?>
	        <input type="file" name="file" id="file"/>
            <p>JPG, GIF, PNG, max 3 Mb</p>
        </td>
    </tr>         
    <tr>
        <th>
            <?php echo $this->lang->line("partner_url"); ?>
        </th>
        <td>
            <?php echo form_error('url'); ?>
            <input type="text" name="url" id="url" value="<?php echo set_value("url", $partner["url"]); ?>" style="width:600px;"/>
        </td>
    </tr>
    <?php
    /*
    <tr>
        <th>
            <?php echo $this->lang->line("partner_description"); ?>
        </th>
        <td>
            <textarea name="description" id="description" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("description", $partner["description"]); ?></textarea>                        						
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
				if($partner["active"] == $value) $selected = true; else $selected = false;
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