<?php
//LOAD FORM HELPER
$this->load->helper("form"); 

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<p>IMAGES: JPG, GIF, PNG, max 3 Mb, recomandat 400x480 px
<br/>FILES: PDF, DOC, DOCX, XLS, XLSX, JPG, GIF, PNG, max 3 Mb</p>

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