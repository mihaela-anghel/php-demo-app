<?php
//LOAD FORM HELPER
$this->load->helper("form"); 

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<p>JPG, GIF, PNG, max 3 Mb, 700x700 pixeli</p>

<form action="" method="post" enctype="multipart/form-data">
<table class="form_table">
    <tr>    	
   		<td>
			<?php echo form_error("file"); ?>
            <input type="file" name="file" id="file"/>
		</td>
        <td>
        	<input type="submit" name="Upload" id="Upload" value="<?php echo $this->lang->line("upload");?>"/>
        </td>
    </tr> 
</table>      
</form>