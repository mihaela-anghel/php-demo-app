<!--TINYMCE-->
<script type="text/javascript" src="<?php echo base_url();?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>tinymce/init.js"></script>

<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "200";
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
		<th></th>
		<td>
			<?php echo form_error("send_to");?>
			<div><input type="radio" name="send_to" value="all" <?php echo set_radio("send_to","all",true); ?>">Trimite la toti care s-au inscris indiferent daca au trimis sau nu proiectul</div>
            <div><input type="radio" name="send_to" value="without_project" <?php echo set_radio("send_to","without_project"); ?>">Trimite doar la cei care s-au inscris si nu au trimis proiectul</div>
            <div><input type="radio" name="send_to" value="with_project" <?php echo set_radio("send_to","with_project"); ?>">Trimite doar la cei care s-au inscris si au trimis si proiectul</div>            						            
		</td>
	</tr>
    <tr>
		<th></th>
		<td>
			<?php echo form_error("attach_file");?>
			<input type="checkbox" name="attach_file" value="1" <?php echo set_checkbox("attach_file","1"); ?>">
            Ataseaza diploma/certificatul daca acestea exista            					            
		</td>
	</tr> 
    <tr>
		<th>
			<?php echo $this->lang->line("participant_email_subject")?>
		</th>
		<td>
			<?php echo form_error("subject");?>
			<input type="text" name="subject" value="<?php echo set_value("subject", $competition["name"]); ?>">						            
		</td>
	</tr>    
    <tr>
		<th>
			<?php echo $this->lang->line("participant_email_content")?>
		</th>
        <td height="350">	
            <?php
            $email_content 	= "";
            ?>
			<?php echo form_error("content");?>
			<textarea type="text" name="content" class="html_textarea" style="height:200px"><?php echo set_value("content", $email_content); ?></textarea>
		</td>
	</tr>    
	<tr>
        <th width="<?php echo $th_width?>">
        <td>
			<input type="submit" name="Send" value="<?php echo $this->lang->line("participant_send_email");?>" onclick="$('#loading').html('<img src=<?php echo file_url()?>images/loading.gif width=20> sending...')"/>
			<div id="loading"></div>
        </td>
    </tr>
</table>
</form>