<?php $this->load->helper('form'); ?>

<h2><?php echo $this->lang->line('user_registration')?></h2>

<?php echo $this->setting->item["text_register"]?>

<form action="<?php echo current_url()?>" method="post" enctype="multipart/form-data">                                

    <!-- personal info start-->                              
    <?php /*<h3><?php echo $this->lang->line('user_personal_info');?></h3>*/?>     
    <div class="form-row">
        <?php if($this->setting->item["register_name_active"]=="yes") { ?>
        <div class="form-group col-md-6">
            <label for="name">
                <?php echo $this->lang->line('user_name')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_name_info"]?>"><i class="far fa-question-circle"></i></span>                
            </label>                       
            <input type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" placeholder="<?php echo $this->lang->line('user_name')?>" class="form-control <?php if(form_error('name')) echo "is-invalid";?>" <?php if($this->setting->item["register_name_required"]=="yes") echo "required";?>>            
            <?php echo form_error('name'); ?>
        </div> 
        <?php } ?>

        <?php if($this->setting->item["register_birthday_active"]=="yes") { ?>
        <div class="form-group col-md-6">
            <label for="birthday">
                <?php echo $this->lang->line('user_birthday')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_birthday_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>
            <div class="input-group">            
                <input type="text" name="birthday" id="birthday" value="<?php echo set_value('birthday'); ?>" placeholder="<?php echo $this->lang->line('user_birthday')?>" class="form-control datepicker <?php if(form_error('birthday')) echo "is-invalid";?>" <?php if($this->setting->item["register_birthday_required"]=="yes") echo "required";?> readonly autocomplete="off">            
                <div class="input-group-append" onclick="$(this).prev().focus()">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <?php echo form_error('birthday'); ?>
            </div>            
        </div> 
        <?php } ?>
    </div>                       

    <div class="form-row">                  
        <div class="form-group col-md-6">
            <label for="email">
                <?php echo $this->lang->line('user_email')?>  
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_email_info"]?>"><i class="far fa-question-circle"></i></span>          
            </label>            
            <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="<?php echo $this->lang->line('user_email')?>" class="form-control <?php if(form_error('email')) echo "is-invalid";?>" required>            
            <?php echo form_error('email'); ?>
        </div>   
        
        <?php if($this->setting->item["register_phone_active"]=="yes") { ?>
        <div class="form-group col-md-6">
            <label for="phone">
                <?php echo $this->lang->line('user_phone')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_phone_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>            
            <input type="text" name="phone" id="phone" value="<?php echo set_value('phone'); ?>" placeholder="<?php echo $this->lang->line('user_phone')?>" class="form-control <?php if(form_error('phone')) echo "is-invalid";?>" <?php if($this->setting->item["register_phone_required"]=="yes") echo "required";?>>            
            <?php echo form_error('phone'); ?>
        </div>
        <?php } ?>

        <?php if($this->setting->item["register_image_active"]=="yes") { ?>                
        <div class="form-group col-md-6">                    
            <label for="image">
                <?php echo $this->lang->line('user_image')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_image_info"]?>"><i class="far fa-question-circle"></i></span>
                <?php if($this->setting->item["register_image_required"]=="yes") { ?><small class="font-italic" style="color:#999999">*mandatory field</small><?php } ?>  
            </label>  
            <div class="custom-file">
                <input type="file" name="image" id="image" value="<?php echo set_value('image'); ?>" class="custom-file-input ajax-upload" aria-describedby="image-help" onchange="$(this).next('.custom-file-label').html($(this).val().split('\\').pop())">
                <label class="custom-file-label" for="image">Choose file</label>
            </div>
            <small id="image-help" class="form-text text-muted">                            
                JPG, GIF, PNG, max 3 Mb
            </small>
            <?php echo form_error('image'); ?>  

            <?php
            //default value
            $file_name = ""; 
            $file_url = "";
            if(isset($_POST['image_filename']) && $_POST['image_filename'])
            {
                $file_name  = $_POST['image_filename'];   
                $file_url   = file_url()."uploads/users/temp/".$_POST['image_filename'];               
            }    
            ?>
            <div class="ajax-output d-none border rounded bg-light p-2" data-file-name="<?php echo $file_name?>" data-file-url="<?php echo $file_url?>"></div> 
            <input type="hidden" name="image_filename" id="image_filename" value="<?php echo set_value('image_filename'); ?>" class="ajax-hidden-field <?php if(form_error('image_filename')) echo "is-invalid";?>">   
            <?php echo form_error('image_filename'); ?>                                                                                                                                                      
        </div>  
        <?php } ?> 
    </div>

    <div class="form-row">   
        <?php if($this->setting->item["register_city_active"]=="yes") { ?>
        <div class="form-group col-md-6">
            <label for="city">
                <?php echo $this->lang->line('user_city')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_city_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>            
            <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" placeholder="<?php echo $this->lang->line('user_city')?>" class="form-control <?php if(form_error('city')) echo "is-invalid";?>" <?php if($this->setting->item["register_city_required"]=="yes") echo "required";?>>            
            <?php echo form_error('city'); ?>
        </div>
        <?php } ?>

        <?php if($this->setting->item["register_country_active"]=="yes") { ?>                    
        <div class="form-group col-md-6">
            <label for="country_id">
                <?php echo $this->lang->line('user_country')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_country_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>                            
            <select name="country_id" id="country_id" class="form-control <?php if(form_error('country_id')) echo "is-invalid";?>" <?php if($this->setting->item["register_country_required"]=="yes") echo "required";?>>
                <option value=""><?php echo $this->lang->line('select')?></option>
                <?php	
                foreach($countries as $country)
                {		
                    if(in_array($country['country_name'], array("Russia", "Belarus")))
                        continue;

                    if(175 == $country['country_id']) $selected = true; else $selected = false;
                    ?><option value="<?php echo $country['country_id']?>" <?php echo set_select('country_id',$country['country_id'],$selected)?> ><?php echo $country['country_name']?></option><?php
                }
                ?>
            </select>
            <?php echo form_error('country_id'); ?>                                       
        </div>   
        <?php } ?>                  
    </div>

    <div class="form-row">
        <?php if($this->setting->item["register_school_active"]=="yes") { ?>        
        <div class="form-group col-md-6">
            <label for="school">
                <?php echo $this->lang->line('user_school')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_school_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>            
            <input type="text" name="school" id="school" value="<?php echo set_value('school'); ?>" placeholder="<?php echo $this->lang->line('user_school')?>" class="form-control <?php if(form_error('school')) echo "is-invalid";?>" <?php if($this->setting->item["register_school_required"]=="yes") echo "required";?>>            
            <?php echo form_error('school'); ?>
        </div> 
        <?php } ?>

        <?php if($this->setting->item["register_guide_active"]=="yes") { ?>            
        <div class="form-group col-md-6">
            <label for="guide">
                <?php echo $this->lang->line('user_guide')?>
                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_guide_info"]?>"><i class="far fa-question-circle"></i></span>
            </label>            
            <input type="text" name="guide" id="guide" value="<?php echo set_value('guide'); ?>" placeholder="<?php echo $this->lang->line('user_guide')?>" class="form-control <?php if(form_error('guide')) echo "is-invalid";?>" <?php if($this->setting->item["register_guide_required"]=="yes") echo "required";?>>            
            <?php echo form_error('guide'); ?>
        </div> 
        <?php } ?>
    </div>                                

    <?php if($this->setting->item["register_school_certificate_active"]=="yes") { ?>            
    <div class="form-group">                    
        <label for="school_certificate">
            <?php echo $this->lang->line('user_school_certificate')?>
            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_school_certificate_info"]?>"><i class="far fa-question-circle"></i></span>
            <?php if($this->setting->item["register_school_certificate_required"]=="yes") { ?><small class="font-italic" style="color:#999999">*mandatory field</small><?php } ?>  
        </label>  
        <div class="custom-file">
            <input type="file" name="school_certificate" id="school_certificate" value="<?php echo set_value('school_certificate'); ?>" class="custom-file-input ajax-upload" aria-describedby="school_certificate-help" onchange="$(this).next('.custom-file-label').html($(this).val().split('\\').pop())">
            <label class="custom-file-label" for="school_certificate">Choose file</label>
        </div>
        <small id="school_certificate-help" class="form-text text-muted">                            
            JPG, GIF, PNG, max 3 Mb
        </small>
        <?php echo form_error('school_certificate'); ?>  

        <?php
        //default value
        $file_name = ""; 
        $file_url = "";
        if(isset($_POST['school_certificate_filename']) && $_POST['school_certificate_filename'])
        {
            $file_name  = $_POST['school_certificate_filename'];   
            $file_url   = file_url()."uploads/users/temp/".$_POST['school_certificate_filename'];               
        }    
        ?>
        <div class="ajax-output d-none border rounded bg-light p-2" data-file-name="<?php echo $file_name?>" data-file-url="<?php echo $file_url?>"></div> 
        <input type="hidden" name="school_certificate_filename" id="school_certificate_filename" value="<?php echo set_value('school_certificate_filename'); ?>" class="ajax-hidden-field <?php if(form_error('school_certificate_filename')) echo "is-invalid";?>">   
        <?php echo form_error('school_certificate_filename'); ?>                                                                                                                                                      
    </div>
    <?php } ?>
    
    <!-- personal info end--> 

    <!-- account start-->
    <?php /*<h3><?php echo $this->lang->line('user_account_info');?></h3>*/?>

    

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="password">
                <?php echo $this->lang->line('user_password')?>                            
            </label>  
            <input type="text" name="password" id="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo $this->lang->line('user_password')?>" class="form-control <?php if(form_error('password')) echo "is-invalid";?>" aria-describedby="password-help" required>
            <small id="password-help" class="form-text text-muted">                            
                <?php echo $this->lang->line('user_password_help')?>
            </small>
            <?php echo form_error('password'); ?>                                                                                              
        </div> 

        <div class="form-group col-md-6">
            <label for="confirm_password">
                <?php echo $this->lang->line('user_confirm_password')?>
            </label>                            
            <input type="text" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="<?php echo $this->lang->line('user_confirm_password')?>" class="form-control <?php if(form_error('confirm_password')) echo "is-invalid";?>" required>
            <?php echo form_error('confirm_password'); ?>                                  
        </div> 
    </div>
    <!-- account end-->

    <?php
    /*                                
    <!-- address info start--> 
    <h3><?php echo $this->lang->line('user_address_info');?></h3>

    <div class="form-row">                
        <div class="form-group col-md-6">
            <label for="address">
                <?php echo $this->lang->line('user_address')?>
            </label>            
            <input type="text" name="address" id="address" value="<?php echo set_value('address'); ?>" placeholder="<?php echo $this->lang->line('user_address')?>" class="form-control <?php if(form_error('address')) echo "is-invalid";?>">            
            <?php echo form_error('address'); ?>
        </div>
                    
        <div class="form-group col-md-6">                         
            <label for="region">
                <?php echo $this->lang->line('user_region')?>
            </label>
            <select name="region" id="region" class="form-control <?php if(form_error('region')) echo "is-invalid";?>">
                <option value=""><?php echo $this->lang->line('select')?></option>
                <?php	
                foreach($judete as $judet)
                {		
                    ?><option value="<?php echo $judet['judet']?>" <?php echo set_select('region', $judet['judet'])?> ><?php echo $judet['judet']?></option><?php
                }
                ?>
            </select>  
            <?php echo form_error('region'); ?>                                      
        </div>                     
    </div>

    <div class="form-group">
        <label for="postal_code">
            <?php echo $this->lang->line('user_postal_code')?>
        </label>
        <input type="text" name="postal_code" id="postal_code" value="<?php echo set_value('postal_code'); ?>" placeholder="<?php echo $this->lang->line('user_postal_code')?>" class="form-control <?php if(form_error('postal_code')) echo "is-invalid";?>">
        <?php echo form_error('postal_code'); ?>
    </div>
    <!-- address info end-->                                
                
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="newsletter" id="newsletter" value="1" <?php echo set_checkbox('newsletter','1', false); ?> class="custom-control-input <?php if(form_error('newsletter')) echo "is-invalid";?>">
            <label class="custom-control-label" for="newsletter">
                <?php echo $this->lang->line('user_newsletter')?>
            </label>                            
        </div>
        <?php echo form_error('newsletter'); ?>
    </div>                                                                                             
    */?>

    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="terms" id="terms" value="1" <?php echo set_checkbox('terms','1', false); ?> class="custom-control-input <?php if(form_error('terms')) echo "is-invalid";?>" required>
            <label class="custom-control-label" for="terms">                            
                <?php
                //terms page
                $aux = "";
                foreach($global_pages as $key=>$global_page)                            
                    if($global_page["page_id"] == 7)
                    {
                        $aux = '<a href="'.$global_page["url"].'/popup" title="'.$global_page["name"].'" data-fancybox data-type="iframe">';                                   
                        break;                                           
                    }                            
                echo str_replace("<a>",$aux,$this->lang->line('terms_agreement'));
                ?>                             
            </label>                            
            <?php echo form_error('terms'); ?>
        </div>       
    </div> 

    <div class="form-row">
        <div class="form-group col-md-6"> 
            <label for="captcha">
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
        <button type="submit" name="Add" id="Add" class="btn btn-secondary">
            <?php echo $this->lang->line('user_registration_submit');?>
        </button>
    </div>  

</form>