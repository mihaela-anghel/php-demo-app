<div class="bg-light border border-secondary p-3">
    <h4><?php echo $this->lang->line('verify_certificate'); ?></h4>
    
    <form action="<?php echo base_url()."home/verify_certificate"?>" class="genuine-form" method="post" autocomplete="off">
        <div class="form-group">                
            <input type="text" name="serial_number" value="" placeholder="s/n" class="form-control" required>
            <small><?php echo $this->lang->line('verify_certificate_text'); ?></small>              
        </div> 

        <div class="form-row">
            <div class="form-group col-12">                                          
                <input type="text" name="captcha" placeholder="<?php echo $this->lang->line('captcha')?>" class="form-control" required>                                          
            </div>                                                                                                              
        </div>

        <div class="form-row">
            <div class="form-group col-5">
                <a href="javascript:void(0)" onclick="document.getElementById('captcha-img').src='<?php echo base_url()?>myclasses/captcha/captcha.php'" title="<?php echo $this->lang->line('captcha_secure_code')?>">
                    <img id="captcha-img" src="<?php echo base_url()?>myclasses/captcha/captcha.php" alt="<?php echo $this->lang->line('captcha_secure_code')?>" class="img-fluid">                                                
                </a>
            </div> 

            <div class="form-group col-1 pt-2">
                <a href="javascript:void(0)" onclick="document.getElementById('captcha-img').src='<?php echo base_url()?>myclasses/captcha/captcha.php'" title="<?php echo $this->lang->line('captcha_secure_code')?>">
                    <i class="fa fa-sync-alt"></i>
                </a>
            </div> 

            <div class="col-6 text-sm-right">                            
                <button type="submit" name="Check" class="btn btn-secondary">
                    <?php echo $this->lang->line('verify_certificate_check');?>
                </button>
            </div>
        </div>       
    </form>  
    <div class="genuine-form-output"></div>      

</div>