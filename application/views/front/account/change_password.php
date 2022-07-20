<!--Account menu--> 
<?php //require_once("account_menu.php")?>    

<?php 
//title
if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }
  
//done message
if(isset($_SESSION['done_message'])) 
{		 
	?><p class="alert alert-success"><?php echo $_SESSION['done_message']?></p><?php 	
	unset($_SESSION['done_message']);
}
//error message
if(isset($_SESSION['error_message'])) 
{		 
	?><p class="alert alert-success"><?php echo $_SESSION['error_message']?></p><?php 	
	unset($_SESSION['error_message']);
}
?>

<div class="row">
    <div class="col-md-4">       

        <?php $this->load->helper('form'); ?>
        <form action="<?php echo current_url()?>" method="post">  
            <div class="form-group">
                <label for="actual_password">
                    <?php echo $this->lang->line('user_change_password_actual')?>
                </label>
                <input type="password" name="actual_password" id="actual_password" value="<?php echo set_value('actual_password'); ?>" placeholder="<?php echo $this->lang->line('user_change_password_actual')?>" class="form-control <?php if(form_error('actual_password')) echo "is-invalid";?>" required>
                <?php echo form_error('actual_password');?>
            </div>
                        
            <div class="form-group">
                <label for="new_password">
                    <?php echo $this->lang->line('user_change_password_new')?>
                </label>
                <input type="password" name="new_password" id="new_password" value="<?php echo set_value('new_password'); ?>" placeholder="<?php echo $this->lang->line('user_change_password_new')?>" class="form-control <?php if(form_error('new_password')) echo "is-invalid";?>" required>                
                <?php echo form_error('new_password');?>
            </div>
        
            <div class="form-group">
                <label for="confirmed_new_password">
                    <?php echo $this->lang->line('user_change_password_confirm')?>
                </label>
                <input type="password" name="confirmed_new_password" id="confirmed_new_password" value="<?php echo set_value('confirmed_new_password'); ?>" placeholder="<?php echo $this->lang->line('user_change_password_confirm')?>" class="form-control <?php if(form_error('confirmed_new_password')) echo "is-invalid";?>" required>                
                <?php echo form_error('confirmed_new_password');?>
            </div>

            <div class="form-group">
				<button type="submit" name="Change" class="btn btn-secondary">
					<?php echo $this->lang->line('user_change_password')?>
				</button> 
			</div>                           
        </form>        

    </div>
</div>