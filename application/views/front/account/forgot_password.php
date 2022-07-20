<?php  
$show_form = true;

//title
if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }

//done message
if(isset($_SESSION['done_message'])) 
{		 
	?><p class="alert alert-success"><?php echo $_SESSION['done_message']?></p><?php 	
	unset($_SESSION['done_message']);
	$show_form = false;
}
//error message
if(isset($_SESSION['error_message'])) 
{		 
	?><p class="alert alert-success"><?php echo $_SESSION['error_message']?></p><?php 	
	unset($_SESSION['error_message']);
}

if($secure_string)
	$show_form = false;

if($show_form)
{
	?>	
	<div class="row">
		<div class="col-md-8">

			<?php $this->load->helper('form'); ?>
			<form action="<?php echo current_url()?>" method="post">
				<div class="form-group">	
					<label for="email">
						<?php echo $this->lang->line('user_email')?>
					</label>					
					<input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="<?php echo $this->lang->line('user_email')?>" class="form-control <?php if(form_error('email')) echo "is-invalid";?>" required>
					<small class="form-text text-muted">
						<?php echo $this->lang->line('user_forgot_password_info')?>
					</small>
					<?php echo form_error('email');?>
				</div>

				<div class="form-row">
					<div class="form-group col-md-6"> 
						<label for="captcha" class="required">
							<?php echo $this->lang->line('captcha')?>
						</label>                       
						<input type="text" name="captcha" id="captcha" value="<?php //echo set_value('captcha'); ?>" placeholder="<?php echo $this->lang->line('captcha')?>" class="form-control <?php if(form_error('captcha')) echo "is-invalid";?>" required>
						<?php echo form_error('captcha'); ?>                        
					</div>                                    
					<div class="form-group col-md-6">
						<a href="javascript:void(0)" onclick="document.getElementById('captcha-img').src='<?php echo base_url()?>myclasses/captcha/captcha.php'" title="<?php echo $this->lang->line('captcha_secure_code')?>">
							<img id="captcha-img" src="<?php echo base_url()?>myclasses/captcha/captcha.php" alt="<?php echo $this->lang->line('captcha_secure_code')?>" class="img-fluid">
							<i class="fa fa-sync-alt"></i>
						</a>
					</div>                              
				</div>

				<div class="form-group">
					<button type="submit" name="Send" class="btn btn-secondary">
						<?php echo $this->lang->line('user_forgot_password_send')?>
					</button> 
				</div> 							
			</form>
		</div>
	</div>
	<?php
}
?>