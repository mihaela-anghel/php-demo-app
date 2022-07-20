<?php
//SUBMENU
require_once("menu.php");
?>

<!--TINYMCE-->
<script type="text/javascript" src="<?php echo file_url();?>tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo file_url();?>tiny_mce/init_tiny.js"></script>

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
<table class="form_table" width="<?php echo $table_width?>">  		
	<tr>
        <th width="<?php echo $th_width?>">
            <?php echo $this->lang->line("qwerty_name"); ?>
        </th>
        <td>
            <input type="text" name="name" id="name" value="<?php echo set_value("name", $qwerty["name"]); ?>" style="width:600px;"/>*
        </td>
    </tr>     
    <tr>
        <th>
            <?php echo $this->lang->line("qwerty_description"); ?>
        </th>
        <td>
            <textarea name="description" id="description" rows="4" class="html_textarea" style="width:600px; height:300px"><?php echo set_value("description", $qwerty["description"]); ?></textarea>                        						
        </td>
    </tr> 
    <tr>
        <th>
            <?php echo $this->lang->line("meta_title"); ?>
        </th>
        <td>
            <?php echo form_error('meta_title'); ?>
            <input type="text" name="meta_title" id="meta_title" value="<?php echo set_value("meta_title", $qwerty["meta_title"]); ?>" style="width:600px;"/>
        </td>
    </tr>  
    <tr>
        <th>
            <?php echo $this->lang->line("meta_description"); ?>
        </th>
        <td>
            <?php echo form_error('meta_description'); ?>
            <textarea name="meta_description" id="meta_description" rows="4" style="width:600px; height:50px"><?php echo set_value("meta_description", $qwerty["meta_description"]); ?></textarea>                        						
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("meta_keywords"); ?>
        </th>
        <td>
            <?php echo form_error('meta_keywords'); ?>
            <textarea name="meta_keywords" id="meta_keywords" rows="4" style="width:600px; height:50px"><?php echo set_value("meta_keywords", $qwerty["meta_keywords"]); ?></textarea>                        						
        </td>
    </tr> 
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
				if($qwerty["active"] == $value) $selected = true; else $selected = false;
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