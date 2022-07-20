<?php
//error message
if(isset($_SESSION['error_message_login_area'])) 
{		 
    if(!isset($_SESSION['auth']))
    {
        ?><p class="alert alert-danger"><?php echo $_SESSION['error_message_login_area']?></p><?php 	
    }
    unset($_SESSION['error_message_login_area']);
}
?>

<div class="right-sidebar mb-3 collapse dont-collapse-lg" id="login-area">
    <div class="bg-light border border-secondary p-3">
        <button class="btn btn-light d-block d-lg-none float-right" data-toggle="collapse" data-target="#login-area" aria-expanded="false" aria-controls="login-area">
            <i class="fas fa-window-close"></i>
        </button>
        <?php
        if(isset($_SESSION['auth']['name']))
        {
            ?>
            <!-- account menu start-->
            <?php require_once(APPPATH."views/front/account/inc.menu.php")?>
            <!-- account menu end-->            
            <?php
        }
        else
        {
            ?>
            <!-- login form start-->
            <?php require_once(APPPATH."views/front/account/inc.login.php")?>
            <!-- login form end-->
            <?php    
        }        
        ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <?php
        //get box order
        $aux = explode(",",$this->setting->item["right_results_and_comming_soon_order"]);
        $order_results = $aux[0];
        $order_comming = $aux[1];

        //archive
        if(isset($right_competitions) && $right_competitions)
        {   
            ?>
            <div class="right-sidebar mb-3 d-none d-lg-block order-<?php echo $order_results?>">
                <div class="bg-light border border-secondary p-3">
                    <h4><?php echo $this->lang->line('results'); ?></h4>
                    <?php
                    foreach($right_competitions as $right_competition)
                    {
                        ?>
                        <div class="rounded border p-2 pb-0 my-3">
                            <h5><strong><?php echo $right_competition["name"]?></strong></h5>
                            <p><?php echo $this->lang->line("competition_type_".$right_competition['type']);?></p>
                            <div class="font-italic mb-3">                    
                                <div><?php echo $this->lang->line('close_date'); ?>: <?php echo custom_date($right_competition['end_registration_date'], $this->default_lang);?></div>
                                <div><?php echo ucfirst($this->lang->line("participants"))?>: <?php echo $right_competition['participants_number'];?></div>
                                <div class="">
                                    <?php 
                                    if($right_competition["type"] == "national")                                            
                                        echo ucfirst($this->lang->line("schools")).": ".$right_competition['schools_number'];
                                    else
                                        echo ucfirst($this->lang->line("countries")).": ".$right_competition['countries_number'];    
                                    ?>                    
                                </div>
                            </div> 
                            <?php
                            //results
                            foreach($global_pages as $key=>$global_page)
                            {
                                if($global_page["page_id"] == 5)
                                {
                                    ?>
                                    <div>
                                        <a href="<?php echo $global_page["url"]?>/<?php echo $right_competition['url_key'];?>" class="alert alert-primary btn-block text-center mb-0">
                                            <?php echo $this->lang->line("view_results")?>
                                        </a>
                                    </div>
                                    <?php 
                                    break;                                           
                                }
                            }
                            ?>
                        </div>
                        <?php                
                    }
                    ?>

                    <?php
                    //results
                    foreach($global_pages as $key=>$global_page)
                    {
                        if($global_page["page_id"] == 5)
                        {
                            ?>
                            <p>
                                <a href="<?php echo $global_page["url"]?>" class="btn btn-secondary btn-block">
                                    <?php echo $this->lang->line("archive")?>
                                </a>
                            </p>
                            <?php 
                            break;                                           
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        //next competition
        if(isset($next_competitions) && $next_competitions)
        {
            ?>
            <div class="right-sidebar mb-3 d-none d-lg-block order-<?php echo $order_comming?>">
                <div class="bg-light border border-secondary p-3">
                    <h4><?php echo $this->lang->line('comming_soon'); ?></h4>
                    <?php  
                    foreach($next_competitions as $next_competition)
                    {               
                        ?>
                        <div class="rounded border p-2 pb-0 my-3">
                            <?php
                            $file_url		= "";
                            $file_name 		= $next_competition['code_language_image'];
                            $file_path 		= $this->config->item('base_path').'uploads/competitions/code_language_images/'.$file_name;
                            if($file_name && file_exists($file_path))    
                                $file_url	    = $this->config->item('base_url').'uploads/competitions/code_language_images/'.$file_name;               
                            if($file_url)
                            {
                                ?>
                                <picture>
                                    <img src="<?php echo $file_url?>" class="img-fluid border rounded p-1 mb-2" alt="<?php echo $next_competition["name"]?>">
                                </picture>                                        
                                <?php
                            }                               
                            ?>
                            <h5><strong><?php echo $next_competition["name"]?></strong></h5>
                            <p><?php echo $this->lang->line("competition_type_".$next_competition['type']);?></p> 
                            <div class="font-italic">          
                                <div><?php echo $this->lang->line('competition_categories'); ?>:</div>                          
                                <ul>
                                    <?php
                                    foreach($next_competition["categories"] as $category)
                                    {
                                        ?><li><?php echo $category["category_name"]?></li><?php
                                    }
                                    ?>  
                                </ul>                                                                            
                            </div>
                            <div class="alert alert-primary text-center mb-0">
                                <?php echo $this->lang->line("starts_on")?> <?php echo custom_date($next_competition['start_registration_date'], $this->default_lang);?>
                            </div>  
                        </div>
                        <?php
                    }
                    ?> 
                </div>
            </div>
            <?php
        }

        if(!$this->agent->is_mobile())
        {
            ?>
            <div class="right-sidebar mb-3 d-none d-lg-block order-3">
                <?php
                require("inc.verify-genuine.php");
                ?>
            </div>
            <?php

            ?>
            <div class="right-sidebar mb-3 d-none d-lg-block order-4">
                <?php
                require(APPPATH."views/front/banners_right.inc.php");
                ?>
            </div>
            <?php
        }
        ?>        
    </div>
</div>
