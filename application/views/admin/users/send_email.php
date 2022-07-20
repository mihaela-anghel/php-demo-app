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
			<?php
			if(isset($user))
			{
				?>
				<div><input type="radio" name="send_to" value="user" <?php echo set_radio("send_to","user",true); ?>">Trimite la <?php echo $user["name"]?> ( <?php echo $user["email"]?>)</div>            
				<?php
			}
			else
			{
				?>
				<div><input type="radio" name="send_to" value="active" <?php echo set_radio("send_to","active",true); ?>">Trimite la toti userii activi</div> 
				<div><input type="radio" name="send_to" value="all" <?php echo set_radio("send_to","all"); ?>">Trimite la toti userii activi si inactivi</div> 
				<div><input type="radio" name="send_to" value="active_and_not_registered_for_current_competition" <?php echo set_radio("send_to","active_and_not_registered_for_current_competition"); ?>">Trimite la toti userii activi neinscrisi la competitia curenta </div> 				          	           			
				<?php
			}
			?>
		</td>
	</tr>  
    <tr>
		<th></th>
		<td>
			<?php echo form_error("type");?>
			<?php
			if(isset($user))
			{
				?>
				<input type="hidden" name="type" value="all">				
				<?php
			}
			else						
			{
				?>
                <div><input type="radio" name="type" value="all" <?php echo set_radio("type","all", true); ?>">Userii din toate tarile</div> 				
				<div><input type="radio" name="type" value="national" <?php echo set_radio("type","national",false); ?>">Userii din Romania</div> 				
                <div><input type="radio" name="type" value="international" <?php echo set_radio("type","international",false); ?>">Userii din afara Romania</div> 				
				<?php
			}
			?>
		</td>
	</tr>   
    <tr>
		<th>
			<?php echo $this->lang->line("user_email_subject")?>
		</th>
		<td>
			<?php echo form_error("subject");?>
			<input type="text" name="subject" value="<?php echo set_value("subject"); ?>">						            
		</td>
	</tr>    
    <tr>
		<th>
			<?php echo $this->lang->line("user_email_content")?>
		</th>
        <td height="350">	            
			<?php echo form_error("content");?>
			<textarea type="text" name="content" class="html_textarea" style="height:200px"><?php echo set_value("content"); ?></textarea>
		</td>
	</tr>
    <tr>
        <th width="<?php echo $th_width?>">
        <td>
			<input type="submit" name="Send" value="<?php echo $this->lang->line("user_send_email");?>" onclick="$('#loading').html('<img src=<?php echo file_url()?>images/loading.gif width=20> sending...')"/>
			<div id="loading"></div>
        </td>
    </tr>
</table>
</form>

