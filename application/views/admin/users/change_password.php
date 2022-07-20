<?php
//SUBMENU
require_once("menu.php");
?>

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
 
<div class="row">
    <div class="col-md-4">       

        <?php $this->load->helper('form'); ?>
        <form action="<?php echo current_url()?>" method="post">                   
            <div class="form-group">
                <label for="new_password">
                    <?php echo $this->lang->line('user_change_password_new')?>
                </label> 
                <br>              
                <input type="text" name="new_password" id="new_password" value="<?php echo set_value('new_password'); ?>" placeholder="<?php //echo $this->lang->line('user_change_password_new')?>" class="form-control <?php if(form_error('new_password')) echo "is-invalid";?>" required>                
                <?php echo form_error('new_password');?>
            </div>

            <br> 
            <div class="form-group">
                <label for="confirmed_new_password">
                    <?php echo $this->lang->line('user_change_password_confirm')?>
                </label> 
                <br>                
                <input type="text" name="confirmed_new_password" id="confirmed_new_password" value="<?php echo set_value('confirmed_new_password'); ?>" placeholder="<?php //echo $this->lang->line('user_change_password_confirm')?>" class="form-control <?php if(form_error('confirmed_new_password')) echo "is-invalid";?>" required>                
                <?php echo form_error('confirmed_new_password');?>
            </div>

            <br> 
            <div class="form-group">
				<button type="submit" name="Change" class="btn btn-secondary">
					<?php echo $this->lang->line('user_change_password')?>
				</button> 
			</div>                           
        </form>        

    </div>
</div>