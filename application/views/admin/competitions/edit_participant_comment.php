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

<form action="" method="post">
<table class="form_table" width="<?php echo $table_width?>"> 
    <tr>
		<th>
			<?php echo $this->lang->line("participant_name")?>
		</th>
		<td>
            <strong><?php echo $participant['name'];?></strong>
		</td>
	</tr>  
	<tr>
		<th width="<?php echo $th_width?>">
			<?php echo $this->lang->line("participant_comment")?>
		</th>
		<td>
			<?php echo form_error("comment");?>
			<textarea name="comment"><?php echo set_value("comment",$participant["comment"]);?></textarea>
		</td>
	</tr> 
    <tr>
        <th width="<?php echo $th_width?>">
        <td>
            <input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line("save");?>"/>
        </td>
    </tr>
</table>
</form>