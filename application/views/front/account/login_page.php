<?php   
//done message
if(isset($_SESSION['done_message'])) 
{		 
    ?><p class="alert alert-success"><?php echo $_SESSION['done_message']?></p><?php 	
    unset($_SESSION['done_message']);
}
//error message
if(isset($_SESSION['error_message'])) 
{		 
    ?><p class="alert alert-danger"><?php echo $_SESSION['error_message']?></p><?php 	
    unset($_SESSION['error_message']);
}
?>

<div class="row">
    <div class="col-sm-6">  
        <div class="bg-light border p-3 m-4"> 
            <h4><?php echo $this->lang->line("user_does_have_account")?></h4> 
            <div><?php echo $this->setting->item["text_old_account"]?></div>    
            <div class="w-50 my-4">                        
                <!-- login form start-->
                <?php require_once("inc.login.php")?>
                <!-- login form end-->
            </div>
        </div>
    </div>            
    <div class="col-sm-6">       
        <div class="bg-light border p-3 m-4"> 
            <h4><?php echo $this->lang->line("user_not_have_account")?></h4>   
            <div><?php echo $this->setting->item["text_new_account"]?></div>   
            <div class="w-50 my-4">                                                                      
                <a href="<?php echo base_url().$this->default_lang_url."account/register"?>" class="btn btn-secondary">
                    <?php echo $this->lang->line("user_registration_submit")?>
                </a> 
            </div>           		
        </div>
    </div>      
</div>