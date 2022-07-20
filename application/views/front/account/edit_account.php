<?php $this->load->helper('form'); ?>

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

$disable_if_participated = "";
if($closed_number)
    $disable_if_participated = "pointer-events:none";
?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo current_url()?>" method="post" enctype="multipart/form-data"> 

            <!-- personal info start-->
            <!-- <h3><?php echo $this->lang->line('user_personal_info');?></h3> -->

            <?php
            /*
            <div class="row m-0 mb-3 bg-light border py-3">                
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_name')?></small>:<?php echo $user["name"]?></div> 
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_birthday')?></small>: <?php echo custom_date($user['birthday'], $this->default_lang);?></div> 
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_city')?></small>: <?php echo $user["city"]?></div> 
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_school')?></small>: <?php echo $user["school"]?></div>
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_country')?></small>: <?php echo $user["country"]?></div>                                  
                <div class="col-sm-6"><small><?php echo $this->lang->line('user_guide')?></small>: <?php echo $user["guide"]?></div>                 
            </div>
            */?>

            <!-- personal info start-->                              
            <?php /*<h3><?php echo $this->lang->line('user_personal_info');?></h3>*/?>     
            <div class="form-row">
                
                <div class="form-group col-md-6">
                    <label for="email">
                        <?php echo $this->lang->line('user_email')?>  
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_email_info"]?>"><i class="far fa-question-circle"></i></span>          
                    </label>            
                    <input type="text" name="email" id="email" value="<?php echo set_value('email',$user["email"]); ?>" placeholder="<?php echo $this->lang->line('user_email')?>" class="form-control <?php if(form_error('email')) echo "is-invalid";?>" required>            
                    <?php echo form_error('email'); ?>
                </div>   
                
                <?php if($this->setting->item["register_phone_active"]=="yes") { ?>
                <div class="form-group col-md-6">
                    <label for="phone">
                        <?php echo $this->lang->line('user_phone')?>
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_phone_info"]?>"><i class="far fa-question-circle"></i></span>
                    </label>            
                    <input type="text" name="phone" id="phone" value="<?php echo set_value('phone',$user["phone"]); ?>" placeholder="<?php echo $this->lang->line('user_phone')?>" class="form-control <?php if(form_error('phone')) echo "is-invalid";?>" <?php if($this->setting->item["register_phone_required"]=="yes") echo "required";?>>            
                    <?php echo form_error('phone'); ?>
                </div>
                <?php } ?>
               
                <?php
                /*
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
                */?>

                <?php if($this->setting->item["register_birthday_active"]=="yes") { ?>
                <div class="form-group col-md-6">
                    <label for="birthday">
                        <?php echo $this->lang->line('user_birthday')?>
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_birthday_info"]?>"><i class="far fa-question-circle"></i></span>
                    </label>
                    <div class="input-group">            
                        <input type="text" name="birthday" id="birthday" value="<?php echo set_value('birthday',$user["birthday"]); ?>" placeholder="<?php echo $this->lang->line('user_birthday')?>" class="form-control datepicker <?php if(form_error('birthday')) echo "is-invalid";?>" <?php if($this->setting->item["register_birthday_required"]=="yes") echo "required";?> readonly autocomplete="off" style="<?php echo $disable_if_participated?>">            
                        <div class="input-group-append" onclick="$(this).prev().focus()" style="<?php echo $disable_if_participated?>">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <?php echo form_error('birthday'); ?>
                    </div>                    
                </div> 
                <?php } ?>
            </div>                       

            <?php if($this->setting->item["register_image_active"]=="yes") { ?>
            <div class="form-group">                    
                <label for="image">
                    <?php echo $this->lang->line('user_image')?>
                    <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_image_info"]?>"><i class="far fa-question-circle"></i></span>
                </label>  
                <div class="custom-file">
                    <input type="file" name="image" id="image" class="custom-file-input ajax-upload" aria-describedby="image-help" onchange="$(this).next('.custom-file-label').html($(this).val().split('\\').pop())">
                    <label class="custom-file-label" for="image">Choose file</label>
                </div>
                <small id="image-help" class="form-text text-muted">                            
                    JPG, GIF, PNG, max 3 Mb
                </small>
                <?php //echo form_error('image'); ?>  

                <?php
                //default value
                $file_name = ""; 
                $file_url = "";
                if(isset($_POST['image_filename']))
                {
                    $file_name  = $_POST['image_filename']; 
                    if($file_name)
                    {
                        if(empty($user["image"]))
                            $file_url   = file_url()."uploads/users/temp/".$_POST['image_filename'];               
                        else
                        {
                            $file_url   = file_url()."uploads/users/".$user['image'];

                            $old_path 	= base_path()."uploads/users/".$user['image'];
							$temp_path 	= base_path()."uploads/users/temp/".$_POST['image_filename'];							
							if(file_exists($temp_path))							
								if(	!(filesize($temp_path) == filesize($old_path) && md5_file($temp_path) == md5_file($old_path)))									
									$file_url   = file_url()."uploads/users/temp/".$_POST['image_filename'];															
                        }                            
                    }    
                } 
                elseif(isset($user['image']))
                {
                    $file_name  = $user['image'];
                    if($file_name)  
                        $file_url   = file_url()."uploads/users/".$user['image'];               
                }                                  
                ?>                
                <div class="ajax-output d-none border rounded bg-light p-2" data-file-name="<?php echo $file_name?>" data-file-url="<?php echo $file_url?>"></div> 
                <input type="hidden" name="image_filename" id="image_filename" value="<?php echo set_value('image_filename'); ?>" class="ajax-hidden-field <?php if(form_error('image_filename')) echo "is-invalid";?>">   
                <?php echo form_error('image_filename'); ?>                                                                                                                                                                     
            </div>
            <?php } ?>

            <div class="form-row">   
                <?php if($this->setting->item["register_city_active"]=="yes") { ?>
                <div class="form-group col-md-6">
                    <label for="city">
                        <?php echo $this->lang->line('user_city')?>
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_city_info"]?>"><i class="far fa-question-circle"></i></span>
                    </label>            
                    <input type="text" name="city" id="city" value="<?php echo set_value('city',$user["city"]); ?>" placeholder="<?php echo $this->lang->line('user_city')?>" class="form-control <?php if(form_error('city')) echo "is-invalid";?>" <?php if($this->setting->item["register_city_required"]=="yes") echo "required";?>>            
                    <?php echo form_error('city'); ?>
                </div>
                <?php } ?>

                <?php if($this->setting->item["register_country_active"]=="yes") { ?>                    
                <div class="form-group col-md-6">
                    <label for="country_id">
                        <?php echo $this->lang->line('user_country')?>
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_country_info"]?>"><i class="far fa-question-circle"></i></span>
                    </label>                            
                    <select name="country_id" id="country_id" class="form-control <?php if(form_error('country_id')) echo "is-invalid";?>" <?php if($this->setting->item["register_country_required"]=="yes") echo "required";?> style="<?php echo $disable_if_participated?>">
                        <option value=""><?php echo $this->lang->line('select')?></option>
                        <?php	
                        foreach($countries as $country)
                        {		
                            if($user['country_id'] == $country['country_id']) $selected = true; else $selected = false;
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
                    <input type="text" name="school" id="school" value="<?php echo set_value('school',$user["school"]); ?>" placeholder="<?php echo $this->lang->line('user_school')?>" class="form-control <?php if(form_error('school')) echo "is-invalid";?>" <?php if($this->setting->item["register_school_required"]=="yes") echo "required";?>>            
                    <?php echo form_error('school'); ?>
                </div> 
                <?php } ?>

                <?php if($this->setting->item["register_guide_active"]=="yes") { ?>            
                <div class="form-group col-md-6">
                    <label for="guide">
                        <?php echo $this->lang->line('user_guide')?>
                        <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_guide_info"]?>"><i class="far fa-question-circle"></i></span>
                    </label>            
                    <input type="text" name="guide" id="guide" value="<?php echo set_value('guide',$user["guide"]); ?>" placeholder="<?php echo $this->lang->line('user_guide')?>" class="form-control <?php if(form_error('guide')) echo "is-invalid";?>" <?php if($this->setting->item["register_guide_required"]=="yes") echo "required";?>>            
                    <?php echo form_error('guide'); ?>
                </div> 
                <?php } ?>
            </div>           
            
            <?php if($this->setting->item["register_school_certificate_active"]=="yes") { ?>
            <div class="form-group">                    
                <label for="school_certificate">
                    <?php echo $this->lang->line('user_school_certificate')?>
                    <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $this->setting->item["register_school_certificate_info"]?>"><i class="far fa-question-circle"></i></span>
                </label>  
                <div class="custom-file">
                    <input type="file" name="school_certificate" id="school_certificate" class="custom-file-input ajax-upload" aria-describedby="school_certificate-help" onchange="$(this).next('.custom-file-label').html($(this).val().split('\\').pop())">
                    <label class="custom-file-label" for="school_certificate">Choose file</label>
                </div>
                <small id="school_certificate-help" class="form-text text-muted">                            
                    PDF, DOC, DOCX, XLS, XLSX, JPG, GIF, PNG, max 3 Mb
                </small>
                <?php echo form_error('school_certificate'); ?>  

                <?php
                //default value
                $file_name = ""; 
                $file_url = "";
                if(isset($_POST['school_certificate_filename']))
                {
                    $file_name  = $_POST['school_certificate_filename']; 
                    if($file_name)
                    {
                        if(empty($user["school_certificate"]))
                            $file_url   = file_url()."uploads/users/temp/".$_POST['school_certificate_filename'];               
                        else
                        {
                            $file_url   = file_url()."uploads/users/school_certificates/".$user['school_certificate'];

                            $old_path 	= base_path()."uploads/users/school_certificates/".$user['school_certificate'];
							$temp_path 	= base_path()."uploads/users/temp/".$_POST['school_certificate_filename'];							
							if(file_exists($temp_path))							
								if(	!(filesize($temp_path) == filesize($old_path) && md5_file($temp_path) == md5_file($old_path)))									
									$file_url   = file_url()."uploads/users/temp/".$_POST['school_certificate_filename'];															
                        }                            
                    }    
                } 
                elseif(isset($user['school_certificate']))
                {
                    $file_name  = $user['school_certificate'];
                    if($file_name)  
                        $file_url   = file_url()."uploads/users/school_certificates/".$user['school_certificate'];               
                }      
                ?>
                <div class="ajax-output d-none border rounded bg-light p-2" data-file-name="<?php echo $file_name?>" data-file-url="<?php echo $file_url?>"></div> 
                <input type="hidden" name="school_certificate_filename" id="school_certificate_filename" value="<?php echo set_value('school_certificate_filename'); ?>" class="ajax-hidden-field <?php if(form_error('school_certificate_filename')) echo "is-invalid";?>">   
                <?php echo form_error('school_certificate_filename'); ?>                                                                                                                                                      
            </div>
            <?php } ?>

            <!-- personal info end-->                               

            <!-- account start-->                                          
            <!-- <h3><?php echo $this->lang->line('user_account_info');?></h3> -->
            <!-- account end--> 

            <?php
            /*  
            <!-- personal info start-->
            <h3><?php echo $this->lang->line('user_personal_info');?></h3>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name" class="required">
                        <?php echo $this->lang->line('user_name')?>
                    </label>            
                    <input type="text" name="name" id="name" value="<?php echo set_value('name', $user["name"]); ?>" placeholder="<?php echo $this->lang->line('user_name')?>" class="form-control <?php if(form_error('name')) echo "is-invalid";?>" required>            
                    <?php echo form_error('name'); ?>
                </div> 

                <div class="form-group col-md-6">
                    <label for="birthday" class="required">
                        <?php echo $this->lang->line('user_birthday')?>
                    </label>            
                    <input type="text" name="birthday" id="birthday" value="<?php echo set_value('birthday', $user["birthday"]); ?>" placeholder="<?php echo $this->lang->line('user_birthday')?>"  class="form-control <?php if(form_error('birthday')) echo "is-invalid";?>" required>            
                    <?php echo form_error('birthday'); ?>
                </div> 
            </div>                 

            <div class="form-row">                
                <div class="form-group col-md-6">
                    <label for="city" class="required">
                        <?php echo $this->lang->line('user_city')?>
                    </label>            
                    <input type="text" name="city" id="city" value="<?php echo set_value('city', $user["city"]); ?>" placeholder="<?php echo $this->lang->line('user_city')?>"  class="form-control <?php if(form_error('city')) echo "is-invalid";?>" required>            
                    <?php echo form_error('city'); ?>
                </div>
                            
                <div class="form-group col-md-6">
                    <label for="country_id" class="required">
                        <?php echo $this->lang->line('user_country')?>
                    </label>                            
                    <select name="country_id" id="country_id"  class="form-control <?php if(form_error('country_id')) echo "is-invalid";?>" required>
                        <option value=""><?php echo $this->lang->line('select')?></option>
                        <?php	
                        foreach($countries as $country)
                        {		
                            if($user["country_id"] == $country['country_id']) $selected = true; else $selected = false;
                            ?><option value="<?php echo $country['country_id']?>" <?php echo set_select('country_id',$country['country_id'],$selected)?> ><?php echo $country['country_name']?></option><?php
                        }
                        ?>
                    </select>
                    <?php echo form_error('country_id'); ?>                                       
                </div>                     
            </div>  

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="school" class="required">
                        <?php echo $this->lang->line('user_school')?>
                    </label>            
                    <input type="text" name="school" id="school" value="<?php echo set_value('school', $user["school"]); ?>" placeholder="<?php echo $this->lang->line('user_school')?>"  class="form-control <?php if(form_error('school')) echo "is-invalid";?>" required>            
                    <?php echo form_error('school'); ?>
                </div> 

                <div class="form-group col-md-6">
                    <label for="guide" class="required">
                        <?php echo $this->lang->line('user_guide')?>
                    </label>            
                    <input type="text" name="guide" id="guide" value="<?php echo set_value('guide', $user["guide"]); ?>" placeholder="<?php echo $this->lang->line('user_guide')?>"  class="form-control <?php if(form_error('guide')) echo "is-invalid";?>" required>            
                    <?php echo form_error('guide'); ?>
                </div> 
            </div> 
            <!-- personal info end-->

            <!-- address info start--> 
            <h3><?php echo $this->lang->line('user_address_info');?></h3>

            <div class="form-row">                
                <div class="form-group col-md-6">
                    <label for="address" class="required">
                        <?php echo $this->lang->line('user_address')?>
                    </label>            
                    <input type="text" name="address" id="address" value="<?php echo set_value('address', $user["address"]); ?>" placeholder="<?php echo $this->lang->line('user_address')?>"  class="form-control <?php if(form_error('address')) echo "is-invalid";?>" required>            
                    <?php echo form_error('address'); ?>
                </div>
                            
                <div class="form-group col-md-6">                         
                    <label for="region" class="required">
                        <?php echo $this->lang->line('user_region')?>
                    </label>
                    <select name="region" id="region"  class="form-control <?php if(form_error('region')) echo "is-invalid";?>" required>
                        <option value=""><?php echo $this->lang->line('select')?></option>
                        <?php	
                        foreach($judete as $judet)
                        {		
                            if($user["judet"] == $judet['judet']) $selected = true; else $selected = false;
                            ?><option value="<?php echo $judet['judet']?>" <?php echo set_select('region', $judet['judet'],$selected)?> ><?php echo $judet['judet']?></option><?php
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
                <input type="text" name="postal_code" id="postal_code" value="<?php echo set_value('postal_code', $user["postal_code"]); ?>" placeholder="<?php echo $this->lang->line('user_postal_code')?>"  class="form-control <?php if(form_error('postal_code')) echo "is-invalid";?>" required>
                <?php echo form_error('postal_code'); ?>
            </div>
            <!-- address info end-->                                
                        
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <?php if($user["newsletter"]) $selected = true; else $selected = false;  ?>  
                    <input type="checkbox" name="newsletter" id="newsletter" value="1" <?php echo set_checkbox('newsletter','1', $selected); ?> class="custom-control-input <?php if(form_error('newsletter')) echo "is-invalid";?>">
                    <label class="custom-control-label" for="newsletter">
                        <?php echo $this->lang->line('user_newsletter')?>
                    </label>                            
                </div>
                <?php echo form_error('newsletter'); ?>
            </div>                                                                                             
            */?>

            <div class="form-group">
                <button type="submit" name="Edit" class="btn btn-secondary">
                    <?php echo $this->lang->line('user_edit_account_save');?>
                </button>
            </div>  

        </form>
    </div>
</div>