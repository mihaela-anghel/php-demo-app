<?php $this->load->helper('form'); ?>

<div class="row mx-0">
    <?php
    foreach($current_competition["categories"] as $key=>$category)
    {        
        //check participation
        $participation = false;
        foreach($participations as $participation_)
        {
            if($participation_["category_id"] == $category["category_id"])
            {
                $participation = $participation_;
                break;
            }
        }        
        ?>
        <div class="col-md-12 p-1">
            <div class="row bg-light rounded border border p-3 m-0">
                <div class="col-md-4">
                    <h4>
                        <?php echo $category["category_name"]?>
                        <?php if($age_category) echo "<br>".$age_category["min_age"]." - ".$age_category["max_age"]." ".$this->lang->line("ani")?>
                    </h4>
                    
                    <h3><?php echo $current_competition["name"]?></h3>
                </div>

                <div class="col-md-4">   
                    <!-- Register start-->                               
                    <?php
                    if($participation)
                    {
                        $text = str_replace(    array("{registration_date}"), 
                                                array( custom_date($participation['registration_date'], $this->default_lang)), 
                                                $this->lang->line("user_registered")
                                                );
                        ?>
                        <h6><?php echo $participation["name"]?></h6>
                        <p>
                            <i class="fas fa-check-circle text-success"></i> <span><?php echo $text?></span>                            
                        </p>
                        <?php                        
                        /* if($participation["project_link_extern"] || $participation["project_filename"])                        
                        {
                            $text = str_replace(    array("{project_date}","{end_submit_project_date}"), 
                                                    array( custom_date($participation['project_add_date'], $this->default_lang),  custom_date($current_competition['end_submit_project_date'], $this->default_lang)), 
                                                    $this->lang->line("project_submited")
                                                );
                            ?>
                            <div>
                                <p><i class="fas fa-check-circle text-success"></i> <span><?php echo $text?></span></p>                                 
                            </div>
                            <?php   
                        }
                        else                        
                        {
                            $text = str_replace(    array("{end_submit_project_date}"), 
                                                    array(custom_date($current_competition['end_submit_project_date'], $this->default_lang)), 
                                                    $this->lang->line("need_submit_project")
                                                );
                            ?>
                            <div>
                                <p><i class="fas fa-exclamation-circle text-danger"></i> <small><?php echo $text?></small></p>                             
                            </div>
                            <?php  
                        } */   
                    }
                    elseif($current_competition["is_open_to_register"])
                    {                                               
                        $alert_nationl_only = false;
                        if($current_competition["type"] == "national" && $_SESSION["auth"]["country_id"] != 175)
                            $alert_nationl_only = true;

                        ?>     
                        <h6><?php echo $_SESSION["auth"]["name"]?></h6>                                           
                        <p>                      
                            <button class="btn btn-info btn-lg" data-toggle="modal" data-target="#register-area-category-<?php echo $category["category_id"]?>">
                                <?php echo $this->lang->line("register_now")?>
                            </button>
                        </p>                        

                        <?php
                        if($alert_nationl_only)
                        {
                            ?>
                            <!-- Modal Alert ONLY National Country (Romania) are allowed -->
                            <div class="modal fade" id="register-area-category-<?php echo $category["category_id"]?>" tabindex="-1" role="dialog" aria-labelledby="register-area-title-category-<?php echo $category["category_id"]?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="register-area-title-category-<?php echo $category["category_id"]?>">
                                            <?php echo $current_competition["name"]?>
                                        </h5>                                       
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <?php echo $this->lang->line("registration_need_national")?>                                                                              
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>                                        
                                    </div>
                                    </div>
                                </div>
                            </div>                        
                            <?php
                        }
                        else
                        {
                            ?>
                            <!-- Modal Confirm Registration-->
                            <div class="modal fade" id="register-area-category-<?php echo $category["category_id"]?>" tabindex="-1" role="dialog" aria-labelledby="register-area-title-category-<?php echo $category["category_id"]?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="register-area-title-category-<?php echo $category["category_id"]?>">
                                            <?php echo $current_competition["name"]?>
                                        </h5>                                       
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body bg-light">
                                        <?php echo $current_competition["popup_info"]?>        
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-info">
                                                <div><h3><?php echo $this->lang->line("competition_category")?>: <?php echo $category["category_name"]?></h3></div>
                                                <div><h3><?php if($age_category) echo $this->lang->line("competition_age_category").": ".$age_category["min_age"]." - ".$age_category["max_age"]." ".$this->lang->line("ani"); else echo $this->lang->line("age_not_allowed")?></h3></div>
                                            </li>                                           
                                            <li class="list-group-item list-group-item-info">
                                                <div><h4><?php echo $_SESSION["auth"]["name"]?></h4></div>
                                                <div><?php echo $this->lang->line('user_school')?>: <?php echo $_SESSION["auth"]["school"]?></div>
                                                <div><?php echo $_SESSION["auth"]["city"]?>, <?php echo $this->locations->get_country_name($_SESSION["auth"]["country_id"])?></div>                                                 
                                                <div><?php echo $this->lang->line('user_guide')?>: <?php echo $_SESSION["auth"]["guide"]?></div>                                               
                                            </li>                                                                                     
                                        </ul>                                                                                                                                                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button>
                                        <?php
                                        if($age_category)
                                        {
                                            ?>
                                            <form method="post">
                                                <input type="hidden" name="category_id" value="<?php echo $category["category_id"]?>">  
                                                <input type="submit" name="RegisterToCompetition" value="<?php echo $this->lang->line("confirm_registration")?>" class="btn btn-secondary">                                            
                                            </form>                                                                                        
                                            <?php
                                        }
                                        ?>                                        
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>                        
                            <?php
                        }
                    }
                    else
                    {
                        ?>
                        <h2><i class="fas fa-user-lock"></i></h2>
                        <p><?php echo $this->lang->line("registration_closed");?></p><?php

                        if($current_competition["is_open_soon"])
                        {
                            ?>                            
                            <small>
                                <p>Please return and register starting with first date of registration:</p>
                                <div class="row text-lowercase font-weight-bold">
                                    <div class="col-6 pr-0">:: <?php echo $this->lang->line("start_registration_date")?>:</div> 
                                    <div class="col-6 pr-0"><span><?php echo custom_date($current_competition['start_registration_date'], $this->default_lang);?></span></div>
                                </div>
                                <div class="row text-lowercase">
                                    <div class="col-6 pr-0">:: <?php echo $this->lang->line("end_registration_date")?>:</div> 
                                    <div class="col-6 pr-0"><span><?php echo custom_date($current_competition['end_registration_date'], $this->default_lang);?></span></div>
                                </div>
                                <div class="row text-lowercase">
                                    <div class="col-6 pr-0">:: <?php echo $this->lang->line("end_submit_project_date")?>:</div> 
                                    <div class="col-6 pr-0"><span><?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?></span></div>
                                </div>
                                <div class="row text-lowercase">
                                    <div class="col-6 pr-0">:: <?php echo str_replace("!","",$this->lang->line("show_results_date"))?>:</div> 
                                    <div class="col-6 pr-0"><span><?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?></span></div>
                                </div>
                            </small>
                            <?php
                        }
                    }   
                    
                    //cancel participation
                    if($participation && $current_competition["is_open_to_register"] && $this->setting->item["cancel_registration_to_competition"] == "yes")
                    {
                        ?>      
                        <small>You are allowed to cancel registration to this competition:</small>                  
                        <div>                               
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove-registration-<?php echo $category["category_id"]?>">
                                <i class="fa fa-times"></i> <?php //echo $this->lang->line("cancel")?>Cancel registration
                            </button>                                
                        </div>                           
                        <!-- Modal -->
                        <div class="modal" data-show="true"  id="remove-registration-<?php echo $category["category_id"]?>" tabindex="-1" role="dialog" aria-labelledby="remove-registration-<?php echo $category["category_id"]?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form method="post">   
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remove-registration-<?php echo $category["category_id"]?>">
                                                <?php echo $this->lang->line("confirm_cancel_registration")?>
                                            </h5>                                       
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>                                            
                                        <div class="modal-footer">
                                            <div>            
                                                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("no")?></button>
                                                <input type="hidden" name="participant_id" value="<?php echo $participation["competitions_participant_id"]?>">                                                          
                                                <input type="submit" name="RemoveRegistration" value="<?php echo $this->lang->line("yes")?>" class="btn btn-secondary">                                                                                                                                                                                      
                                            </div>                                                                                       
                                        </div>
                                    </form>    
                                </div>
                            </div>
                        </div>
                        <?php   
                    }
                    ?>
                </div>
                <!-- Register end-->

                <!-- Submit project start--> 
                <div class="col-md-4">               
                <?php               
                if($participation)
                {                    
                    //project external link
                    if($participation["project_link_extern"])
                    {
                        ?>
                        <div class="alert alert-success">                            
                            <strong><?php echo $this->lang->line("competition_project_link")?></strong>:<br>                        
                            <small><a href="<?php echo $participation['project_link_extern']?>" target="_blank"><?php echo $participation['project_link_extern']?></a></small><br>                                                             
                        </div>
                        <?php                        
                    }

                    //project file
                    if($participation["project_filename"])
                    {
                        $project_link      = base_url()."project/".$participation['project_number'];

                        $project_file_path = base_path()."uploads/competitions/projects/".$participation["project_filename"];
                        $project_file_size = round(filesize($project_file_path)/1024);                                              
                        ?>
                        <div class="alert alert-success">                        
                            <strong><?php echo $this->lang->line("competition_project_file")?></strong>:<br>                        
                            <small><a href="<?php echo $project_link?>" download><?php echo $project_link?></a></small><br>
                            <small><?php echo $project_file_size." Kb";?></small>   
                            
                            <?php
                            //remove file
                            if($current_competition["is_open_to_submit"])
                            {
                                ?>                                                        
                                <div>                      
                                    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#remove-project_file-<?php echo $category["category_id"]?>">
                                        <i class="fa fa-trash-alt"></i> <?php echo $this->lang->line("delete")?>
                                    </button>                                
                                </div>                           
                                <!-- Modal -->
                                <div class="modal" data-show="true"  id="remove-project_file-<?php echo $category["category_id"]?>" tabindex="-1" role="dialog" aria-labelledby="remove-project_file-<?php echo $category["category_id"]?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form method="post">   
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remove-project_file-<?php echo $category["category_id"]?>">
                                                        <?php echo $this->lang->line("competition_project_file_remove_confirmation")?>
                                                    </h5>                                       
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>                                            
                                                <div class="modal-footer">
                                                    <div>            
                                                        <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button>
                                                        <input type="hidden" name="participant_id" value="<?php echo $participation["competitions_participant_id"]?>">                                                          
                                                        <input type="submit" name="RemoveProjectFile" value="<?php echo $this->lang->line("delete")?>" class="btn btn-secondary">                                                                                                                                                                                      
                                                    </div>                                                                                       
                                                </div>
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php                        
                    }                   

                    //send project
                    ?>                    
                    <div>
                        <?php
                        if($current_competition["is_open_to_submit"])
                        {
                            if($participation["project_link_extern"] || $participation["project_filename"])                        
                            {
                                $text = str_replace(    array("{project_date}","{end_submit_project_date}"), 
                                                        array( custom_date($participation['project_add_date'], $this->default_lang),  custom_date($current_competition['end_submit_project_date'], $this->default_lang)), 
                                                        $this->lang->line("project_submited")
                                                    );
                                ?>
                                <div>
                                    <p><i class="fas fa-check-circle text-success"></i> <span><?php echo $text?></span></p>                                 
                                </div>
                                <?php   
                            }
                            else                        
                            {
                                $text = str_replace(    array("{end_submit_project_date}"), 
                                                        array(custom_date($current_competition['end_submit_project_date'], $this->default_lang)), 
                                                        $this->lang->line("need_submit_project")
                                                    );
                                ?>
                                <div>
                                    <p><i class="fas fa-exclamation-circle text-danger"></i> <small><?php echo $text?></small></p>                             
                                </div>
                                <?php  
                            }

                            ?>
                            <div>                      
                                <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#submition-area-category-<?php echo $category["category_id"]?>">
                                    <?php echo $this->lang->line("submit_now")?>
                                </button>                                
                            </div>
                            <?php
                            /*
                            <small class="text-muted">
                                <?php echo $this->lang->line("end_submit_project_date")?>                     
                                <?php echo custom_date($current_competition['end_submit_project_date'], $this->default_lang);?>                     
                            </small>
                            <?php 
                            */                            

                            if(validation_errors() && $_POST["participant_id"] == $participation["competitions_participant_id"])
                                echo validation_errors();
                            ?>
                            <!-- Modal -->
                            <div class="modal" data-show="true"  id="submition-area-category-<?php echo $category["category_id"]?>" tabindex="-1" role="dialog" aria-labelledby="submition-area-title-category-<?php echo $category["category_id"]?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form method="post" action="<?php echo base_url().$this->default_lang_url?>account/ajax_submit_project" class="submit-project-form">   
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="submition-area-title-category-<?php echo $category["category_id"]?>">
                                                    <?php echo $current_competition["name"]?>
                                                </h5>                                       
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body bg-light">
                                                <?php echo $current_competition["popup_info"]?> 
                                                <ul class="list-group">
                                                    <li class="list-group-item list-group-item-secondary">
                                                        <div><h3><?php echo $_SESSION["auth"]["name"]?></h3></div>
                                                        <div><h3><?php echo $this->lang->line("competition_category")?>: <?php echo $category["category_name"]?></h3></div>
                                                        <div><h3><?php if($age_category) echo $this->lang->line("competition_age_category").": ".$age_category["min_age"]." - ".$age_category["max_age"]." ".$this->lang->line("ani"); else echo $this->lang->line("age_not_allowed")?></h3></div>                                                
                                                    </li>  
                                                    <li class="list-group-item list-group-item-secondary">
                                                        <?php 
                                                        //submit project link
                                                        ?>
                                                        <div><h4><?php echo $this->lang->line("competition_project_link")?></h4></div> 
                                                        <?php
                                                        if($age_category)
                                                        {
                                                            ?>
                                                            <p><?php echo nl2br($this->setting->item["text_submit_project_link"])?></p>
                                                            <input type="text" name="project_link_extern" value="<?php echo $participation["project_link_extern"]?>" class="form-control">                                              
                                                            <small class="text-muted font-italic">Ex: https://scratch.mit.edu/projects/355208336/</small>    

                                                            <div class="mt-3">
                                                                <div class="text-danger"><strong>Don't forget to SHARE the project!</strong></div>     
                                                                <small class="text-primary">Press SHARE from inside your code on top of the screen!<br>Unshared projects will be disqualified!</small>                                                   
                                                            </div>    
                                                            <?php
                                                        }                                                        
                                                        ?>
                                                    </li>                                                                                                          
                                                    <?php
                                                    //submit project file
                                                    if(isset($_SESSION["auth"]["enable_submit_project_file"]) && $_SESSION["auth"]["enable_submit_project_file"] == "1")
                                                    {                                                    
                                                        ?>
                                                        <li class="list-group-item list-group-item-secondary">                                                                                                                                                                                                                                                                                 
                                                            <div><h4><?php echo $this->lang->line("competition_project_file")?></h4></div> 
                                                            <?php
                                                            if($age_category)
                                                            {
                                                                ?>       
                                                                <p><?php echo nl2br($this->setting->item["text_submit_project_file"])?></p>                                                          
                                                                <input id="project_file_<?php echo $participation["competitions_participant_id"]?>" name="project_file" type="file" class="uploadyfive" />                                              
                                                                <small class="form-text text-muted">ZIP file, max 10 Mb</small>
                                                                
                                                                <input type="hidden" name="project_filename"> 
                                                                <p><strong class="project_filename"></strong></p>                                                                                                                                                                                               
                                                                <?php                                                                
                                                            }                                                        
                                                            ?>                                                          
                                                        </li>                                                                                                     
                                                        <?php
                                                    }
                                                    ?>                                                                                                                 
                                                </ul>                                                 
                                            </div>
                                            <div class="modal-footer">
                                                <div id="submit-project-form-output-<?php echo $participation["competitions_participant_id"]?>" class="submit-project-form-output d-block"></div>                                                                                               

                                                <div>            
                                                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo $this->lang->line("cancel")?></button>
                                                    <?php
                                                    if($age_category)
                                                    {
                                                        ?>                                                                                                                                                                                               
                                                        <input type="hidden" name="participant_id" value="<?php echo $participation["competitions_participant_id"]?>">  
                                                        <input type="submit" name="SubmitProject" value="<?php echo $this->lang->line("confirm_submition")?>" class="btn btn-secondary">                                                                                                                                                                                      
                                                        <?php
                                                    }
                                                    ?> 
                                                </div>                                                                                       
                                            </div>
                                        </form>    
                                    </div>
                                </div>
                            </div>
                            <?php

                            if(isset($current_competition))
                            {
                                ?>
                                <h6>
                                    ATENTION! <br> Competition topic is<br>
                                    <span class="text-danger" style="font-size:140%"><?php echo $current_competition["theme_name"]?></span>
                                </h6>
                                <?php
                            }
                        }
                        else
                        {
                            if(isset($current_competition['results']) && $current_competition['results'] && $current_competition["status"] == "close")
                            {
                                ?>
                                <div>
                                <?php 
                                //text info
                                if(isset($participation["prize"]))
                                {
                                    if($participation["prize"]["type"] == "prize")
                                    {
                                        ?>
                                        <div>
                                            <i class="fas fa-medal text-success"></i> 
                                            <?php echo $this->lang->line("winner_message")." ".$participation["prize"]["certificate"];?>   
                                        </div> 
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <div>
                                            <i class="fas fa-trophy text-success"></i> 
                                            <?php echo $this->lang->line("winner_message")." ".$participation["prize"]["certificate"];?>    
                                        </div>
                                        <?php                                        
                                    }
                                }                                                                                                   
                                elseif($participation["diploma"] != "")
                                {
                                    ?>
                                    <div>
                                        <i class="fas fa-handshake text-secondary"></i> 
                                        <?php echo $this->lang->line("not_a_winner_message")?>   
                                    </div> 
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div>
                                        <i class="fas fa-frown text-secondary"></i> 
                                        You did not submit the project!  
                                    </div> 
                                    <?php
                                }
                                
                                //diploma
                                $file_name  = $participation["diploma"];
                                $file_url   = file_url()."uploads/competitions/diploma/".$file_name;
                                $file_path  = base_path()."uploads/competitions/diploma/".$file_name;                                                               
                                if($file_name)
                                {                                    
                                    if(file_exists($file_path))
                                    {
                                        ?>
                                        <div class="mt-2">                                    
                                            <a href="<?php echo $file_url?>" class="btn btn-danger btn-sm" download>
                                                <i class="fas fa-download"></i>
                                                <?php if($participation["prize_id"]) echo $this->lang->line("competition_diploma"); else echo $this->lang->line("competition_certificate")?>
                                            </a>   
                                        </div>                                
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <div class="mt-2">
                                            <small class="text-muted font-italic">                                    
                                                <?php echo $this->lang->line("file_removed_alert"); ?>: <?php echo $this->setting->item["email"]?>   
                                            </small>                                
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <p> 
                                    <span class="font-weight-bold"><i class="fas fa-lock"></i> <?php echo $this->lang->line("submition_closed");?></span><br>
                                    <small>
                                        <?php echo custom_date($current_competition['show_results_date'], $this->default_lang);?><br>                                
                                        <?php echo $this->lang->line("show_results_date");?>
                                    </small>
                                </p>  
                                <?php
                            }
                        }                            
                        ?>
                    </div> 
                    <?php 
                }
                else if(isset($current_competition))
                {
                    ?>
                    <h6>
                        ATENTION! <br> Competition topic is<br>
                        <span class="text-danger" style="font-size:140%"><?php echo $current_competition["theme_name"]?></span>
                    </h6>
                    <?php
                }
                ?> 
                </div> 
                <!-- Submit project end-->

            </div>
        </div>        
        <?php       
    }
    ?>
</div>
