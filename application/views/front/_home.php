<?php
//current competition
if(isset($current_competition))
{   
    ?>
    <h1><?php echo $current_competition["name"]?></h1>
    <div><?php echo $current_competition["description"]?></div>

    <!--  current competition start -->
    <div class="row mb-4">        
        <?php
        $file_url		= $this->config->item('base_url').'image/r_800x503_crop/uploads/nopictures/nopicture.jpg';
        $file_name 		= $current_competition['image'];
        $file_path 		= $this->config->item('base_path').'uploads/competitions/'.$file_name;
        if($file_name && file_exists($file_path))    
            //$file_url	    = $this->config->item('base_url').'image/r_800x503_crop/uploads/competitions/'.$file_name;               
            $file_url	    = $this->config->item('base_url').'uploads/competitions/'.$file_name;               
        if($file_url)
        {
            ?>
            <!-- image start -->
            <div class="col-12 col-md-6">
                <picture class="">
                    <img src="<?php echo $file_url?>" class="img-fluid" alt="<?php echo $current_competition["name"]?>">
                </picture>
            </div>
            <!-- image end -->
            <?php
        }                               
        ?>

        <div class="col-12 col-md-6 pl-md-0">
            <div class="border border-light p-3">
                <div class="row">

                    <?php
                    //is open
                    if($current_competition["status"] == "open")
                    {
                        //will be open soon
                        if($current_competition["is_open_soon"])
                        {
                            ?>
                            <!-- register start -->
                            <div class="col-12">                            
                                <h3 class="text-center">
                                    <?php echo $this->lang->line("comming_soon"); ?>
                                </h3>
                                <h4 class="text-center">                                
                                    <?php echo $this->lang->line("register_now_text");?>
                                </h4>                                                        
                                <div class="row text-lowercase">
                                    <div class="col-6">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></strong></div>
                                
                                    <div class="col-6">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></strong></div>
                                </div>
                                <?php
                                /*
                                <div class="row text-lowercase mt-2">
                                    <div class="col-6">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></strong></div>
                                </div>
                                */?>
                                <div class="row text-lowercase my-1" style="color:#999999">
                                    <div class="col-6">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></strong></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- register now button -->
                                <button  class="btn btn-secondary btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                                    <?php echo $this->lang->line("register_now")?>
                                </button>

                                <!-- Modal Register/Submit inactiv-->
                                <div class="modal" data-show="true"  id="text_popup_register_now_inactiv" tabindex="-1" role="dialog" aria-labelledby="text_popup_register_now_inactiv" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">                                               
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?php echo $current_competition["name"]?>
                                                </h5>                                       
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>                                            
                                            <div class="modal-body">
                                                <?php echo $this->setting->item["text_popup_register_now_inactiv"]?>                                                                                     
                                            </div>
                                            <div class="modal-footer">
                                                <div>            
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">OK</button>                                                                                                                                                                                                                                          
                                                </div>                                                                                     
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Register/Submit Activ POPUP INFO-->
                                <div class="modal" data-show="true"  id="text_popup_register_now_activ" tabindex="-1" role="dialog" aria-labelledby="text_popup_register_now_activ" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">                                               
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?php echo $current_competition["name"]?>
                                                </h5>                                       
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>                                            
                                            <div class="modal-body">
                                                <?php echo $current_competition["popup_info"]?>                                                                               
                                            </div>
                                            <div class="modal-footer">                                                
                                                <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary <?php //echo $disabled_class?>">
                                                    OK
                                                </a>   
                                                <!-- <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- submit now button -->                                
                                <?php
                                if($current_competition["is_open_to_submit"])
                                {
                                    //active
                                    if($current_competition["popup_info_active"])
                                    {
                                        ?>
                                        <button  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                                            <?php echo $this->lang->line("submit_now")?>
                                        </button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-block <?php //echo $disabled_class?>">
                                            <?php echo $this->lang->line("submit_now")?>
                                        </a> 
                                        <?php    
                                    }
                                    ?>                                                                        
                                    <?php
                                }
                                else
                                {
                                    //disabled
                                    ?>
                                    <button  class="btn btn-secondary btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                                        <?php echo $this->lang->line("submit_now")?>
                                    </button>
                                    <?php            
                                }           
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php 
                                if($this->setting->item["show_registration_numbers"] == "yes") 
                                { 
                                    ?>
                                    <div class="border border-secondary text-lowercase text-center py-1">
                                        <small>
                                            <em>
                                                *<?php echo $this->lang->line("registered_until_now")?>: <br>
                                                <?php 
                                                if($current_competition["type"] == "national")                                            
                                                    echo $current_competition['schools_number']." ".$this->lang->line("schools");
                                                else
                                                    echo $current_competition['countries_number']." ".$this->lang->line("countries");    
                                                ?>, 
                                                <?php echo $current_competition['participants_number'];?> <?php echo $this->lang->line("participants")?>
                                            </em>
                                        </small> 
                                    </div>  
                                    <?php 
                                }   
                                ?>                                                                         
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <div class="countTime" data-date="<?php echo $current_competition['start_registration_date']?> 00:00:00"></div>                                        
                                </div> 
                            </div>                            
                            <!-- register end -->
                            <?php
                        }
                         
                        //register
                        elseif($current_competition["is_open_to_register"])
                        {
                            ?>
                            <!-- register start -->
                            <div class="col-12">                            
                                <h3 class="text-center <?php echo ($this->setting->item["active_blinking"]=="yes"?"blinking":"")?>">
                                    <?php echo $this->lang->line("submition_open"); ?>
                                </h3>
                                <h4 class="text-center">                                
                                    <?php echo $this->lang->line("register_now_text");?>
                                </h4>                                                        
                                <div class="row text-lowercase">
                                    <div class="col-6">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></strong></div>
                                
                                    <div class="col-6">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></strong></div>
                                </div>
                                <?php
                                /*
                                <div class="row text-lowercase mt-2">
                                    <div class="col-6">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></strong></div>
                                </div>
                                */?>
                                <div class="row text-lowercase my-1" style="color:#999999">
                                    <div class="col-6">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></strong></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- register now button -->                               
                                <?php                             
                                if($current_competition["popup_info_active"])
                                {
                                    ?>
                                    <button  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                                        <?php echo $this->lang->line("register_now")?>
                                    </button>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-block">
                                        <?php echo $this->lang->line("register_now")?>
                                    </a> 
                                    <?php    
                                }
                                ?>

                                <!-- Modal Register/Submit Activ POPUP INFO-->
                                <div class="modal" data-show="true"  id="text_popup_register_now_activ" tabindex="-1" role="dialog" aria-labelledby="text_popup_register_now_activ" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">                                               
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?php echo $current_competition["name"]?>
                                                </h5>                                       
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>                                            
                                            <div class="modal-body">
                                                <?php echo $current_competition["popup_info"]?>                                                                               
                                            </div>
                                            <div class="modal-footer">                                                
                                                <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary <?php //echo $disabled_class?>">
                                                    OK
                                                </a>   
                                                <!-- <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- submit now button -->                                
                                <?php
                                if($current_competition["is_open_to_submit"])
                                {                                   
                                    //active
                                    if($current_competition["popup_info_active"])
                                    {
                                        ?>
                                        <button  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                                            <?php echo $this->lang->line("submit_now")?>
                                        </button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-block <?php //echo $disabled_class?>">
                                            <?php echo $this->lang->line("submit_now")?>
                                        </a> 
                                        <?php    
                                    }
                                }
                                else
                                {
                                    //disabled
                                    ?>
                                    <button  class="btn btn-secondary btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                                        <?php echo $this->lang->line("submit_now")?>
                                    </button>
                                    <?php            
                                }           
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php if($this->setting->item["show_registration_numbers"] == "yes") { ?>
                                <div class="border border-secondary text-lowercase text-center py-1 mt-1">
                                    <small>
                                        <em>
                                            *<?php echo $this->lang->line("registered_until_now")?>: <br>
                                            <?php 
                                            if($current_competition["type"] == "national")                                            
                                                echo $current_competition['schools_number']." ".$this->lang->line("schools");
                                            else
                                                echo $current_competition['countries_number']." ".$this->lang->line("countries");    
                                            ?>, 
                                            <?php echo $current_competition['participants_number'];?> <?php echo $this->lang->line("participants")?>
                                        </em>
                                    </small> 
                                </div>  
                                <?php } ?>                                                     
                            </div>
                            <!-- register end -->

                            <div class="col-md-6">
                                <div>                                    
                                    <div class="countTime" data-date="<?php echo $current_competition['end_submit_project_date']?> 23:59:59"></div>                                        
                                    <?php
                                    $end_date       = date_create($current_competition['end_submit_project_date']);
                                    $start_date     = date_create(date("Y-m-d"));
                                    $interval       = date_diff($start_date, $end_date);
                                    $last_days      = $interval->format('%a');

                                    if($last_days > 0 && $last_days <= 10)
                                    {
                                        ?>                                       
                                        <div class="text-danger text-center position-absolute w-100" style="left:0px"><small>Last days to submit your project!</small></div>
                                        <?php
                                    }
                                    ?>
                                </div> 
                            </div>
                            <?php
                        }

                        //submit
                        else if($current_competition["is_open_to_submit"])
                        {
                            ?>
                            <!-- submit start -->
                            <div class="col-12">                            
                                <h3 class="text-center <?php echo ($this->setting->item["active_blinking"]=="yes"?"blinking":"")?>">
                                    <?php echo $this->lang->line("submition_open"); ?>
                                </h3>
                                <h4 class="text-center">                                
                                    <?php echo $this->lang->line("submit_now_text");?>
                                </h4>                                                        
                                <div class="row text-lowercase">
                                    <div class="col-6">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></strong></div>
                                
                                    <div class="col-6">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></strong></div>
                                </div>
                                <?php
                                /*
                                <div class="row text-lowercase mt-2">
                                    <div class="col-6">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></strong></div>
                                </div>
                                */?>
                                <div class="row text-lowercase my-1" style="color:#999999">
                                    <div class="col-6">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></strong></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php
                                if($current_competition["is_open_to_register"])
                                {                                    
                                    //active
                                    if($current_competition["popup_info_active"])
                                    {
                                        ?>
                                        <button  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                                            <?php echo $this->lang->line("register_now")?>
                                        </button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-block <?php //echo $disabled_class?>">
                                            <?php echo $this->lang->line("register_now")?>
                                        </a> 
                                        <?php    
                                    }
                                }
                                else
                                {
                                    //disabled
                                    ?>
                                    <button  class="btn btn-secondary btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                                        <?php echo $this->lang->line("register_now")?>
                                    </button>
                                    <?php            
                                }           
                                ?>
                                
                                <!-- Modal Register/Submit Activ POPUP INFO-->
                                <div class="modal" data-show="true"  id="text_popup_register_now_activ" tabindex="-1" role="dialog" aria-labelledby="text_popup_register_now_activ" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">                                               
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <?php echo $current_competition["name"]?>
                                                </h5>                                       
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>                                            
                                            <div class="modal-body">
                                                <?php echo $current_competition["popup_info"]?>                                                                               
                                            </div>
                                            <div class="modal-footer">                                                
                                                <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary <?php //echo $disabled_class?>">
                                                    OK
                                                </a>   
                                                <!-- <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <button  class="btn btn-secondary btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                                    <?php echo $this->lang->line("submit_now")?>
                                </button>
                            </div>
                            <div class="col-md-6 mt-1">
                                <?php if($this->setting->item["show_registration_numbers"] == "yes") { ?>
                                <div class="border border-secondary text-lowercase text-center py-1">
                                    <small>
                                        <em>
                                            *<?php echo $this->lang->line("registered_until_now")?>:<br/>
                                            <?php 
                                            if($current_competition["type"] == "national")                                            
                                                echo $current_competition['schools_number']." ".$this->lang->line("schools");
                                            else
                                                echo $current_competition['countries_number']." ".$this->lang->line("countries");    
                                            ?>, 
                                            <?php echo $current_competition['participants_number'];?> <?php echo $this->lang->line("participants")?>
                                        </em>
                                    </small> 
                                </div>
                                <?php } ?>                                                      
                            </div> 
                            <!-- submit end -->
                                   
                            <div class="col-md-6">
                                <div>                                    
                                    <div class="countTime" data-date="<?php echo $current_competition['end_submit_project_date']?> 23:59:59"></div> 
                                    <?php
                                    $end_date       = date_create($current_competition['end_submit_project_date']);
                                    $start_date     = date_create(date("Y-m-d"));
                                    $interval       = date_diff($start_date, $end_date);
                                    $last_days      = $interval->format('%a');

                                    if($last_days > 0 && $last_days <= 10)
                                    {
                                        ?>                                       
                                        <div class="text-danger text-center position-absolute w-100" style="left:0px"><small>Last days to submit your project!</small></div>
                                        <?php
                                    }
                                    ?>
                                </div> 
                            </div>
                            <?php      
                        }

                        //results
                        else
                        {
                            ?>
                            <!-- results comming soon start -->
                            <div class="col-12 mb-3">                            
                                <h3 class="text-center">
                                    <?php echo $this->lang->line("submition_close"); ?>
                                </h3>
                                <h4 class="text-center">
                                    <?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?><br>                                
                                    <?php echo $this->lang->line("show_results_date");?>
                                </h4>                                                        
                                <div class="row text-lowercase">
                                    <div class="col-6">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></strong></div>
                                
                                    <div class="col-6">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></strong></div>
                                </div>
                                <?php
                                /*
                                <div class="row text-lowercase">
                                    <div class="col-6">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></strong></div>
                                </div>
                                */?>
                                <div class="row text-lowercase" style="color:#999999">
                                    <div class="col-6">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                    <div class="col-6"><strong><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></strong></div>
                                </div>
                            </div>                            
                            <div class="col-6">
                                <?php if($this->setting->item["show_registration_numbers"] == "yes") { ?>
                                <div class="border border-secondary text-lowercase text-center py-1">
                                    <small>
                                        <em>
                                            *<?php echo $this->lang->line("registered_until_now")?>:<br> 
                                            <?php 
                                            if($current_competition["type"] == "national")                                            
                                                echo $current_competition['schools_number']." ".$this->lang->line("schools");
                                            else
                                                echo $current_competition['countries_number']." ".$this->lang->line("countries");    
                                            ?>, 
                                            <?php echo $current_competition['participants_number'];?> <?php echo $this->lang->line("participants")?>
                                        </em>
                                    </small> 
                                </div> 
                                <?php } ?>                                                      
                            </div> 
                            <div class="col-6">
                                <div>
                                    <div class="countTime" data-date="<?php echo $current_competition['show_results_date']?> 00:00:00"></div>                                        
                                </div> 
                            </div>
                            <!-- results comming soon end -->
                            <?php   
                        }                        
                    }

                    //is close
                    if($current_competition["status"] == "close")
                    {
                        ?>
                        <!-- results start -->
                        <div class="col-12">                            
                            <h3 class="text-center">
                                <?php echo $this->lang->line("submition_close"); ?>
                            </h3>
                            <h4 class="text-center">
                                <?php echo $this->lang->line("show_results_date");?>
                            </h4>                                                        
                            <div class="row text-lowercase">
                                <div class="col-6">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                <div class="col-6"><strong><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></strong></div>
                            
                                <div class="col-6">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                <div class="col-6"><strong><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></strong></div>
                            </div>
                            <?php
                            /*
                            <div class="row text-lowercase mt-2">
                                <div class="col-6">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                <div class="col-6"><strong><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></strong></div>
                            </div>
                            */?>
                            <div class="row text-lowercase my-1" style="color:#999999">
                                <div class="col-6">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                <div class="col-6"><strong><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></strong></div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4"></div>
                        <div class="col-md-6">  
                            <?php
                            //results
                            foreach($global_pages as $key=>$global_page)
                            {
                                if($global_page["page_id"] == 5)
                                {
                                    ?>
                                    <a href="<?php echo $global_page["url"]?>" class="btn btn-secondary btn-block">
                                        <?php echo $this->lang->line("results")?>
                                    </a>
                                    <?php 
                                    break;                                           
                                }
                            }
                            ?>                                                      
                        </div>
                        <?php 
                        if($this->setting->item["show_registration_numbers"] == "yes") 
                        { 
                            ?>
                            <div class="col-md-6">
                                <div class="border border-secondary text-lowercase text-center py-1">
                                    <small>
                                        <em>
                                            *<?php echo $this->lang->line("registered_until_now")?>:<br/>
                                            <?php 
                                            if($current_competition["type"] == "national")                                            
                                                echo $current_competition['schools_number']." ".$this->lang->line("schools");
                                            else
                                                echo $current_competition['countries_number']." ".$this->lang->line("countries");    
                                            ?>, 
                                            <?php echo $current_competition['participants_number'];?> <?php echo $this->lang->line("participants")?>
                                        </em>
                                    </small> 
                                </div>                                                       
                            </div>
                            <?php 
                        } 
                        ?> 
                         <div class="col-md-12" style="margin-bottom:20px"></div>                                                  
                        <!-- results end -->
                        <?php                                               
                    }
                    ?>  

                </div>
            </div>               
        </div>
    </div> 
    
    <div class="row mb-4"> 
        <!-- language start-->
        <div class="col-12 col-md-6 align-self-center">
            <div class="border border-light p-3">                 
                <h3><?php echo $this->lang->line("competition_language")?>:</h3> 
                
                <?php  
                $file_url		= "";
                $file_name 		= $current_competition['code_language_image'];
                $file_path 		= $this->config->item('base_path').'uploads/competitions/code_language_images/'.$file_name;
                if($file_name && file_exists($file_path))    
                    $file_url	    = $this->config->item('base_url').'uploads/competitions/code_language_images/'.$file_name;               
                if($file_url)
                {
                    ?>
                    <picture>
                        <img src="<?php echo $file_url?>" class="img-fluid w-50" alt="<?php echo $current_competition["name"]?>">
                    </picture>                                        
                    <?php
                }                               
                ?>                
                <div><?php echo $current_competition["code_language"]?></div>                
            </div>
        </div>
        <!-- language end-->

        <!-- theme start-->
        <div class="col-12 col-md-6 align-self-center text-center mt-3 mt-md-3">                             
            <h3><?php echo $this->lang->line("competition_subject")?>:</h3>  
            <h4 class="text-dark"><?php echo $current_competition["theme_name"]?></strong></h4>  
            <div><?php echo $current_competition["theme_description"]?></div>     
            <?php
            //scoring rules
            foreach($global_pages as $key=>$global_page)
            {
                if($global_page["page_id"] == 11)
                {
                    ?>
                    <a href="<?php echo $global_page["url"]?>" class="btn btn-secondary">
                        <?php echo $global_page["name"]?>
                    </a>
                    <?php 
                    break;                                           
                }
            }
            ?>                         
        </div>  
        <!-- theme end-->                  
    </div>

    <div class="row mb-4">         
        <!-- categories start-->
        <div class="col-12 col-md-6 align-self-center">                             
            <h3><?php echo $this->lang->line("competition_categories")?>:</h3>  
            <div class="row">
                <?php  
                $file_url		= $this->config->item('base_url').'image/r_300x220_crop/uploads/nopictures/nopicture.jpg';
                $file_name 		= $current_competition['banner'];
                $file_path 		= $this->config->item('base_path').'uploads/competitions/banners/'.$file_name;
                if($file_name && file_exists($file_path))    
                    $file_url	    = $this->config->item('base_url').'uploads/competitions/banners/'.$file_name;               
                if($file_url)
                {
                    ?>
                    <!-- image start -->
                    <div class="col-12 col-md-6 align-self-center">
                        <picture class="">
                            <img src="<?php echo $file_url?>" class="img-fluid" alt="<?php echo $current_competition["name"]?>">
                        </picture>
                    </div>
                    <!-- image end -->
                    <?php
                }                               
                ?>

                <div class="col-12 col-md-6 align-self-center pl-md-0">
                    <?php
                    foreach($current_competition["categories"] as $category)
                    {
                        ?><h5><?php echo $category["category_name"]?></h5><?php
                    }
                    ?> 
                </div>
            </div>
                                      
        </div>  
        <!-- categories end-->  
        
        <!-- age categories start-->
        <div class="col-12 col-md-6 align-self-center text-center mt-3 mt-md-3 pl-md-0">
            <div class="border border-light p-3">                 
                <h3><?php echo $this->lang->line("competition_age_categories")?>:</h3> 
                <ul class="age-categories"> 
                    <?php
                    foreach($current_competition["age_categories"] as $age_category)
                    {
                        ?>
                        <li>
                            <div><strong><?php echo $age_category["min_age"]?>-<?php echo $age_category["max_age"]?></strong></div>
                            <div><?php echo $this->lang->line("ani");?></div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>                
            </div>
        </div>
        <!-- age categories end-->

    </div>

    <?php
    $prizes_no = 0;
    foreach($current_competition["prizes"] as $prize)            
        if($prize["type"] == "prize")
            $prizes_no++;

    $diplomas_no = 0;
    foreach($current_competition["prizes"] as $prize)            
        if($prize["type"] == "special_diplama")
            $diplomas_no++; 

    if($prizes_no)
    {
        ?>
        <!-- prizes start -->
        <div class="row border border-light m-0 mb-4 p-3"> 
            <div class="col-12 p-0">
                <h3><?php echo $this->lang->line("competition_prizes")?></h3>
            </div>
            <?php                                   
            foreach($current_competition["prizes"] as $prize)
            {
                if($prize["type"] == "prize")
                {
                    ?>
                    <div class="col text-center">
                        <?php
                        $file_url		= "";
                        $file_name 		= $prize['image'];
                        $file_path 		= $this->config->item('base_path').'uploads/competitions/prizes/'.$file_name;
                        if($file_name && file_exists($file_path))    
                            $file_url	    = $this->config->item('base_url').'image/rb_100x100_auto/uploads/competitions/prizes/'.$file_name;               
                        if($file_url)
                        {
                            ?>
                            <picture>
                                <img src="<?php echo $file_url?>" class="img-fluid p-3" alt="<?php echo $prize["prize_name"]?>">
                            </picture>                           
                            <?php
                        }   
                        ?> 
                        <p><?php echo $prize["prize_name"]?></p>
                        <h5><?php echo $prize["certificate"]?></h5>                   
                    </div>
                    <?php
                }
            }            
            ?> 
        </div>  
        <!-- prizes end -->
        <?php
    }
    ?>
        
    <div class="row mb-4 ml-0"> 
        <?php
        if($diplomas_no)
        {
            $total_no_special_diploma = 0;
            foreach($current_competition["prizes"] as $prize)                
                if($prize["type"] == "special_diplama")
                    $total_no_special_diploma++;
            
            ?>
            <!-- displomas start -->
            <div class="col-12 col-sm-<?php echo ($total_no_special_diploma>=2?4:8)?>">
                <h5><?php echo $this->lang->line("competition_special_diplomas")?>:</h5>                
                <?php 
                $aux = 0;                                  
                foreach($current_competition["prizes"] as $prize)
                {
                    if($prize["type"] == "special_diplama")
                    {
                        $aux++;
                        ?>
                        <div>
                            <div><i class="fa fa-caret-right"></i> <?php echo $prize["certificate"]?></div>
                            <?php
                            if($prize["prize_name"])
                            {
                                ?>
                                <div class="row ml-0">
                                    <div class="col align-self-center">
                                        <?php
                                        $file_url		= "";
                                        $file_name 		= $prize['image'];
                                        $file_path 		= $this->config->item('base_path').'uploads/competitions/prizes/'.$file_name;
                                        if($file_name && file_exists($file_path))    
                                            $file_url	    = $this->config->item('base_url').'uploads/competitions/prizes/'.$file_name;               
                                        if($file_url)
                                        {
                                            ?>
                                            <picture>
                                                    <img src="<?php echo $file_url?>" class="img-fluid" alt="<?php echo $prize["prize_name"]?>" style="width:50px">
                                                </picture>                                              
                                            <?php
                                        }   
                                        ?>
                                        <?php echo $prize["prize_name"]?>
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>                                           
                        </div>
                        <?php

                        if($total_no_special_diploma >= 2 && ceil($total_no_special_diploma/2) == $aux)
                        {
                            ?>
                            </div>
                            <div class="col-12 col-sm-4">
                            <h5 class="invisible"><?php echo $this->lang->line("competition_special_diplomas")?>:</h5>
                            <?php
                        }
                    }
                }            
                ?> 
            </div>
            <!-- displomas end -->
            <?php
        }            
        ?>
        <!-- certificate start -->
        <div class="col-12 col-sm-4">
            <h5><?php echo $this->lang->line("competition_certificates")?></h5>
            <p><?php echo $this->lang->line("competition_certificates_text")?></p>
        </div>  
        <!-- certificate end -->  
    </div>  
        
    <div class="row text-center">
        <div class="col px-4">
            <?php
            //rules
            foreach($global_pages as $key=>$global_page)
            {
                if($global_page["page_id"] == 3)
                {
                    ?>
                    <a href="<?php echo $global_page["url"]?>" class="btn btn-secondary btn-lg btn-block">
                        <?php echo $this->lang->line("competition_rules")?>
                    </a>
                    <?php 
                    break;                                           
                }
            }
            ?>            
        </div>
        <?php
        //register       
        ?>
        <div class="col px-4">           
            <?php
            if($current_competition["is_open_to_register"])
            {               
                if($current_competition["popup_info_active"])
                {
                    ?>
                    <button  class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                        <?php echo $this->lang->line("register_now")?>
                    </button>
                    <?php
                }
                else
                {
                    ?>
                    <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-lg btn-block <?php //echo $disabled_class?>">
                        <?php echo $this->lang->line("register_now")?>
                    </a> 
                    <?php    
                }
            }
            else
            {
                //disabled
                ?>
                <button  class="btn btn-secondary  btn-lg btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                    <?php echo $this->lang->line("register_now")?>
                </button>
                <?php            
            }           
            ?>
        </div>

        <?php
        //submit              
        ?>
        <div class="col px-4">            
            <?php
            if($current_competition["is_open_to_submit"])
            {                
                if($current_competition["popup_info_active"])
                {
                    ?>
                    <button  class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#text_popup_register_now_activ">
                        <?php echo $this->lang->line("submit_now")?>
                    </button>
                    <?php
                }
                else
                {
                    ?>
                    <a href="<?php echo base_url().$this->default_lang_url."account/register_to_competition"?>" class="btn btn-secondary btn-lg btn-block <?php //echo $disabled_class?>">
                        <?php echo $this->lang->line("submit_now")?>
                    </a> 
                    <?php    
                }
            }
            else
            {
                //disabled
                ?>
                <button  class="btn btn-secondary  btn-lg btn-block disabled" data-toggle="modal" data-target="#text_popup_register_now_inactiv">
                    <?php echo $this->lang->line("submit_now")?>
                </button>
                <?php            
            }           
            ?>
        </div>
        <?php      
         
        //results  
        /*
        if($current_competition["status"] == "close")
        {                    
            ?>
            <div class="col px-4">
                <?php
                //rules
                foreach($global_pages as $key=>$global_page)
                {
                    if($global_page["page_id"] == 5)
                    {
                        ?>
                        <a href="<?php echo $global_page["url"]?>" class="btn btn-secondary">
                            <?php echo $this->lang->line("results")?>
                        </a>
                        <?php 
                        break;                                           
                    }
                }
                ?>                 
            </div>
            <?php                        
        }  
        */      
        ?>
    </div>                 
    <!--  current competition end -->
    <?php   
}
?>
</div>
<div>
<?php
//arbiters
if(isset($home_arbiters))
{    
    ?> 
    <article class="border border-secondary mx-2 mx-lg-0 my-3 py-3 px-4">                 
        <!-- arbiters start -->        
        <div class="row">
            <div class="col-sm-8">
                <h4><?php echo $this->lang->line("arbiters")?></h4>    
            </div>
            <div class="col-sm-4 text-sm-right mt-sm-2">
                <p><small><em class="text-secondary font-weight-bold">( <?php echo count($home_arbiters)?> <?php echo $this->lang->line("arbiters_registered")?> )</em></small></p>
            </div>
        </div>  
        <div class="home-arbiters owl-carousel owl-theme">
            <?php  
            $i=0;                      
            foreach($home_arbiters as $home_arbiter)
            {            
                //image	
                $file_url 		= file_url()."image/r_200x240_crop/images/profile.jpg";
                $file_name		= $home_arbiter["image"];
                $file_path 		= base_path()."uploads/arbiters/".$file_name;
                if($file_name && file_exists($file_path))
                    $file_url 		= file_url()."image/r_200x240_crop/uploads/arbiters/".$file_name;                        
                ?>
                <div class="item align-items-center rounded">
                    <img src="<?php echo $file_url?>" title="<?php echo $home_arbiter["name"];?>" class="mb-2"> 
                    <div><strong><?php echo $home_arbiter["name"];?></strong></div>
                    <div><small><?php echo $home_arbiter["function"];?></small></div>
                    <div><small><?php echo $home_arbiter["company"];?></small></div>
                </div>                                                                                                
                <?php                                                                                        
            }
            ?>                                                 
        </div>        
        <!-- arbiters end -->    
    </article>         
    <?php
}

//testimonials
if(isset($home_testimonials))
{       
    ?>   
    <article class="border border-secondary mx-2 mx-lg-0 my-3 py-3 px-4">               
        <!-- testimonials start -->
        <div class="row">
            <div class="col-sm-8">
                <h4><?php echo $this->lang->line("partners")?></h4>    
            </div>
            <div class="col-sm-4 text-sm-right mt-sm-2">
                <p><small><em class="text-secondary font-weight-bold">( <?php echo count($home_testimonials)?> <?php echo $this->lang->line("partners_registered")?> )</em></small></p>
            </div>
        </div>        
        <div class="home-testimonials owl-carousel owl-theme">
            <?php  
            $i=0;                      
            foreach($home_testimonials as $home_testimonial)
            {            
                //image	
                $file_url 		= file_url()."image/r_100x120_crop/images/profile.jpg";
                $file_name		= $home_testimonial["image"];
                $file_path 		= base_path()."uploads/testimonials/".$file_name;
                if($file_name && file_exists($file_path))
                    $file_url 		= file_url()."image/r_100x120_crop/uploads/testimonials/".$file_name;                        
                ?>
                <div class="item align-items-center border bg-light p-2 rounded clearfix">
                    <picture class="float-left mr-2 mb-2 text-info">
                        <img src="<?php echo $file_url?>" title="<?php echo $home_testimonial["name"];?>" class="mb-2"> 
                        <div><strong><?php echo $home_testimonial["person_name"];?></strong></div>
                        <div><small><?php echo $home_testimonial["function"];?></small></div>
                        <div><small><?php echo $home_testimonial["company"];?></small></div>
                    </picture>
                    
                    <div class="overflow-auto font-italic" style="max-height:190px; min-height:190px">
                        <i class="fa fa-quote-right text-info"></i> <?php echo $home_testimonial["description"];?>
                    </div>
                </div>                                                                                                
                <?php                                                                                        
            }
            ?>                                                         
        </div>              
        <!-- testimonials end -->   
    </article>                     
    <?php
}

//winners
if(isset($home_winners) && $home_winners)
{       
    ?>   
    <article class="border border-secondary mx-2 mx-lg-0 my-3 py-3 px-4">               
        <!-- winners start -->
        <div class="row">
            <div class="col-sm-8">
                <h4><?php echo $this->lang->line("last_winners")?></h4>    
            </div>
            <div class="col-sm-4 text-sm-right mt-sm-2">
                <p><small><em class="text-secondary font-weight-bold">( <?php echo count($home_winners)?> <?php echo $this->lang->line("winners")?> )</em></small></p>
            </div>
        </div>     
        <div class="home-arbiters owl-carousel owl-theme">
            <?php  
            $i=0;                      
            foreach($home_winners as $home_winner)
            {            
                //image	
                $file_url 		= file_url()."image/r_200x240_crop/images/profile.jpg";
                $file_name		= $home_winner["image"];
                $file_path 		= base_path()."uploads/users/".$file_name;
                if($file_name && file_exists($file_path))
                    $file_url 		= file_url()."image/r_200x240_crop/uploads/users/".$file_name;                        
                ?>
                <div class="item align-items-center rounded">
                    <img src="<?php echo $file_url?>" title="<?php echo $home_winner["name"];?>" class="mb-2"> 
                    <div><small><strong><?php echo $home_winner["name"];?></strong></small></div>
                    <div><small><em><?php echo $home_winner["country_name"];?></em></small></div>
                    <div><small><?php echo $home_winner["competition"];?>, <?php echo $home_winner["age_category_name"];?>, <?php echo $home_winner["category_name"];?></small></div>
                    <div class="text-info"><small><?php echo $home_winner["prize"];?></small></div>
                    <div class="mt-2">
                        <?php
                        $project_link = ""; 

                        //project external link
                        if($home_winner["project_link_extern"])                        
                            $project_link = $home_winner["project_link_extern"];

                        //project file
                        elseif($home_winner["project_filename"])                        
                            $project_link      = base_url()."project/".$participation['project_number'];

                        ?>
                        <a href="<?php echo $project_link?>" target="_blank" rel="nofollow" class="btn btn-info btn-sm">
                            <?php echo $this->lang->line("competition_project")?>
                        </a>                       
                    </div>
                </div>                                                                                                
                <?php                                                                                        
            }
            ?>                                                 
        </div>                      
        <!-- winners end -->   
    </article>                     
    <?php
}

?>


<?php
if($this->agent->is_mobile())
{
    ?>
    <article class="mx-2 mx-lg-0 my-3 d-lg-none show">  
        <?php
        require("template/inc.verify-genuine.php");
        ?>
    </article>
    <?php
}

//page
require("page.php");
?>