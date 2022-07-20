<h1><?php echo $this->page_title?></h1>

<div class="row mx-0">    
    <?php
    if($participations)
    {
        foreach($participations as $participation)
        {                        
            ?>
            <div class="col-md-12 p-1">
                <div class="row bg-light rounded border border p-3 m-0">
                    <div class="col-md-4">
                        <h6>
                            <?php if(isset($participation["competition"])) echo $participation["competition"]["name"]?><br>
                            <?php echo $participation["category_name"]?><br>                        
                            <?php echo $participation["age_category_name"]?>
                        </h6>                                                
                    </div>

                    <div class="col-md-4">
                        <div><strong><?php echo $this->lang->line("user_guide")?></strong>: <?php echo $participation["guide"]?></div>
                    
                        <?php
                        //project external link
                        if($participation["project_link_extern"])
                        {
                            ?>
                            <div>                            
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
                            <div>                               
                                <strong><?php echo $this->lang->line("competition_project_file")?></strong>:<br>                        
                                <small><a href="<?php echo $project_link?>" download><?php echo $project_link?></a></small><br>
                                <small><?php echo $project_file_size." Kb";?></small>                                                                    
                            </div>   
                            <?php
                        }
                        ?>                                     
                        <small class="text-muted font-italic"><?php echo $this->lang->line("send_date")?> <?php echo custom_date($participation['project_add_date'], $this->default_lang)?></small>            
                    </div>

                    <div class="col-md-4">
                        <div><strong><?php echo $this->lang->line("results")?></strong>: <?php echo $participation["note"]?></div>
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

                        //id
                        if($participation["project_link_extern"] || $participation["project_filename"]) 
                        {
                            ?>
                            <div class="mt-2">ID: <?php echo $participation["project_number"]?></div>
                            <?php
                        }
                        ?>
                    </div>

                </div>
            </div>                
            <?php 
        }                           
    } 
    else
    {
        ?>
        <h5>
            <?php echo $this->lang->line("no_competitions");?>
        </h5>  
        <?php
    }    
?>
</div>