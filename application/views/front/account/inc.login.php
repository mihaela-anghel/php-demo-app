<?php $this->load->helper('form'); ?>

<?php 
if($this->page_right) 
{ 
    ?>
    <h4><?php echo $this->lang->line('user_login')?></h4>
    <p><small class="form-text text-muted"><?php echo $this->lang->line('user_login_text')?></small></p>
    <?php 
} 
?> 

<?php /*<form action="<?php echo current_url()?>" method="post" novalidate>*/?>
<form id="login-form" action="<?php echo base_url().$this->default_lang_url?>account/login_by_ajax" method="post">
    
    <div class="form-group">	
        <label for="login_username"><?php echo $this->lang->line('user_email')?></label>					
        <input type="text" name="login_username" id="login_username" value="<?php echo set_value('login_username'); ?>" placeholder="<?php echo $this->lang->line('user_email')?>" class="form-control <?php if(form_error('login_username')) echo "is-invalid";?>" required>
        <?php echo form_error('login_username');?>
    </div>

    <div class="form-group">
        <label for="login_password"><?php echo $this->lang->line('user_password')?></label>					
        <input type="password" name="login_password" id="login_password" value="<?php echo set_value('login_password'); ?>" placeholder="<?php echo $this->lang->line('user_password')?>" class="form-control <?php if(form_error('login_password')) echo "is-invalid";?>" required>
        <?php echo form_error('login_password');?>
    </div>

    <div id="login-form-output"></div>

    <div class="form-group">                    
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="stay_logged" id="stay_logged" value="1" <?php echo set_checkbox('login_password','1'); ?> class="custom-control-input <?php if(form_error('stay_logged')) echo "is-invalid";?>">
            <label class="custom-control-label" for="stay_logged">
                <?php echo $this->lang->line('user_stay_logged')?>
            </label>        
            <?php echo form_error('stay_logged'); ?>                                                                                         
        </div>                    
    </div>                

    <div class="form-group">                                         
        <button type="submit" name="Login" class="btn btn-secondary btn-block">
            <?php echo $this->lang->line('user_login_submit')?>
        </button>                                   
    </div>

    <div class="form-group">      
        <?php
        if($this->uri->rsegment(2) != "login_page" && $this->uri->rsegment(2) != "register")
        {
            ?>                                   
            <a href="<?php echo base_url().$this->default_lang_url?>account/register" title="<?php echo $this->lang->line('user_registration')?>">
                <?php echo $this->lang->line('user_registration')?>
            </a>
            <br>
            <?php
        }
        ?>        
        <a href="<?php echo base_url().$this->default_lang_url?>account/forgot_password" title="<?php echo $this->lang->line('user_forgot_password')?>">
            <?php echo $this->lang->line('user_forgot_password')?>
        </a>                              
    </div> 

    <?php
    if(isset($users_number))
    {
        ?>
        <div class="text-secondary">
            <small>
                <em>
                    <?php echo $this->lang->line('our_community')?>: <br/>

                    <?php echo $countries_number?> 
                    <!-- trigger Countries Modal -->
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#countriesModal">
                        <?php echo $this->lang->line('countries')?> 
                    </a>, 

                    <?php echo $users_number?> <?php echo $this->lang->line('users_registered')?>                                                                                 
                </em>
            </small>
        </div>

        <!-- Countries Modal -->
        <div class="modal fade" id="countriesModal" tabindex="-1" role="dialog" aria-labelledby="countriesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="countriesModalLabel"><?php echo $this->lang->line('our_community')?>: <?php echo $countries_number?> <?php echo $this->lang->line('countries')?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        foreach($registered_countries as $country)
                        {
                            ?><div><?php echo $country["country_name"]?></div><?php
                        }
                        ?>
                    </div>                            
                </div>
            </div>
        </div>
                                                                          
        <?php
    }
    ?>

    <!-- <input type="hidden" name="page_url" id="page_url" value="<?php echo current_url()?>"> -->	  
</form>            