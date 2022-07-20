<!-- Contact area -->
<article id="contact">
    <div class="row">
        <div class="col-12 col-md-5">
            <h3><?php //echo $page["name"]?><?php echo $this->lang->line("contact_info")?></h3>
            <?php require("page_content.inc.php");?>
        </div>
        <div class="col-12 col-md-7">
            <h3><?php echo $this->lang->line("contact_form")?></h3>
            <?php
            $this->load->helper('form');	
            if(isset($done_message))
            {
                ?><div class="alert alert-success"><?php echo $done_message ?></div><?php
            }	
            if(isset($error_message))
            {
                ?><div class="alert alert-danger"><?php echo $error_message ?></div><?php
            }	
            ?>
            <form method="post">  
                <div class="form-group">
                    <label for="contact_name">
                        <?php echo $this->lang->line('contact_name')?>                            
                    </label>
                    <input type="text" name="contact_name" id="contact_name" value="<?php if(isset($_POST)) echo set_value('contact_name')?>" placeholder="<?php echo $this->lang->line('contact_name')?>" class="form-control <?php if(form_error('contact_name')) echo "is-invalid";?>" required>
                    <?php echo form_error('contact_name')?>
                </div>              
               
                <div class="form-row">                    
                    <div class="form-group col-sm-6">
                        <label for="contact_email">
                            <?php echo $this->lang->line('contact_email')?>                            
                        </label>
                        <input type="text" name="contact_email" id="contact_email" value="<?php if(isset($_POST)) echo set_value('contact_email')?>" placeholder="<?php echo $this->lang->line('contact_email')?>" class="form-control <?php if(form_error('contact_email')) echo "is-invalid";?>" required>
                        <?php echo form_error('contact_email')?>
                    </div>                
                    <div class="form-group col-sm-6">
                        <label for="contact_phone">
                            <?php echo $this->lang->line('contact_phone')?>                            
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" value="<?php if(isset($_POST)) echo set_value('contact_phone')?>" placeholder="<?php echo $this->lang->line('contact_phone')?>" class="form-control <?php if(form_error('contact_phone')) echo "is-invalid";?>" required>
                        <?php echo form_error('contact_phone')?>
                    </div>                   
                </div>

                <div class="form-group">
                    <label for="contact_subject">
                        <?php echo $this->lang->line('contact_subject')?>                            
                    </label>
                    <input type="text" name="contact_subject" id="contact_subject" value="<?php if(isset($_POST)) echo set_value('contact_subject')?>" placeholder="<?php echo $this->lang->line('contact_subject')?>" class="form-control <?php if(form_error('contact_subject')) echo "is-invalid";?>" required>
                    <?php echo form_error('contact_subject')?>
                </div>
                <div class="form-group">
                    <label for="contact_message">
                        <?php echo $this->lang->line('contact_message')?>                            
                    </label>
                    <textarea name="contact_message" id="contact_message" rows="3" placeholder="<?php echo $this->lang->line('contact_message')?>" class="form-control <?php if(form_error('contact_message')) echo "is-invalid";?>" required><?php if(isset($_POST)) echo  set_value('contact_message')?></textarea>
                    <?php echo form_error('contact_message'); ?>
                </div>
                <div class="form-row">    
                    <div class="col-12 col-sm-9">
                        <div class="g-recaptcha" data-sitekey="6Lc-I3oUAAAAAE7J1-ank5ziGGpfS0rhdIcWISDY" data-size="normal"></div>
                        <?php echo form_error('g-recaptcha-response')?>                        
                    </div>
                    <div class="col-12 col-sm-3 text-sm-right">                            
                        <button type="submit" name="Send" class="btn btn-secondary"><?php echo $this->lang->line('contact_send');?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <?php
    echo  validation_errors();
    require("page_subpages.inc.php");

    require("page_files.inc.php");
    
    require("page_images.inc.php");
    
    require("page_videos.inc.php");
    ?>

    <?php
    //goole map
    //=========================================================
    $google_map = $this->setting->item("google_map");
    if($google_map && $google_map != '#')
    {        
        ?>
        <div class="google-map">
            <?php echo $this->setting->item["google_map"];?>
        </div>
        <?php
    }	
    ?>
</article>    