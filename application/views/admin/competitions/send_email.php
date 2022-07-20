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
			<?php echo $this->lang->line("participant_email_to")?>
		</th>
		<td>
			<?php echo form_error("to_email");?>
            <?php echo form_error("to_name");?>

            <?php echo $this->lang->line("participant_email_to_email")?>
			<input type="text" name="to_email" value="<?php echo set_value("to_email", $participant["email"]); ?>">						            
            
            <?php echo $this->lang->line("participant_email_to_name")?>
            <input type="text" name="to_name" value="<?php echo set_value("to_name", $participant["name"]); ?>">						            
		</td>
	</tr>    
    <?php
    $email_content  = "";
    
    $file_name = $participant["diploma"];
    $file_url = file_url()."uploads/competitions/diploma/".$file_name;
    $file_path = base_path()."uploads/competitions/diploma/".$file_name;
    if($file_name && file_exists($file_path))
    {
        ?>
        <tr>
            <th>
                <?php echo $this->lang->line("participant_email_attachment")?>
            </th>
            <td>
                <p>
                    <a href="<?php echo $file_url?>" download>
                        <?php echo $participant["diploma"]?>
                    </a> 
                </p>						            
            </td>
        </tr>
        <?php

        $email_content 	= $competition['email_content'];
    }
    ?>						            		
    <tr>
		<th>
			<?php echo $this->lang->line("participant_email_content")?>
            <?php
            foreach($email_templates as $email_template)
            {
                ?>
                <p>
                    <a href="<?php echo admin_url()."competitions/send_email/".$participant["competitions_participant_id"]."/".$email_template["email_template_id"]?>">
                        <button type="button">
                            <?php echo $email_template["name"]?>
                        </button>    
                    </a>                   
                </p>
                <?php
            }
            if($email_template_id)
            {
                ?>
                <p>
                    <a href="<?php echo admin_url()."competitions/send_email/".$participant["competitions_participant_id"]?>">
                        X [anuleaza]
                    </a>
                </p>
                <?php
            }
            ?>
		</th>
        <td height="350">	
            <?php
            if(isset($participant["prize"]))		
                $email_content = $participant["prize"]["email_content"];

            if($email_template_id)
            {
                foreach($email_templates as $email_template)
                {
                    if($email_template_id == $email_template["email_template_id"])
                    {
                        $email_content = $email_template["description"];
                        break;
                    }
                }
            }    
            ?>
			<?php echo form_error("content");?>
			<textarea type="text" name="content" class="html_textarea" style="height:200px"><?php echo set_value("content", $email_content); ?></textarea>
		</td>
	</tr>
    <tr>
        <th width="<?php echo $th_width?>">
        <td>
            <input type="submit" name="Send" id="Send" value="<?php echo $this->lang->line("participant_send_email");?>" onclick="$('#loading').html('<img src=<?php echo file_url()?>images/loading.gif width=20> sending...')"/>
			<div id="loading"></div>
        </td>
    </tr>
</table>
</form>