<!-- Page area -->
<article id="page">
    <?php
    if( !($page["page_id"] == 5 && isset($competition)))
        require("page_content.inc.php");

    require("page_subpages.inc.php");

    require("page_files.inc.php");

    require("page_images.inc.php");

    require("page_videos.inc.php");

    require("inc.galleries.php");

    if($page["page_id"] == 3)
        require("inc.rules.php");

    if($page["page_id"] == 4)
        require("inc.prizes.php");  
        
    if($page["page_id"] == 5)
    {
        //url competition
        if(isset($competition))
        {
            //title
            if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }
            
            require("inc.results.php");  
        }    
        
        //current competition
        /* elseif(isset($current_competition))
        {
            $competition = $current_competition;        
            require("inc.results.php");     
        } */

        //archive competitions
        if(isset($archive_competitions) && $archive_competitions)
        {
            ?>
            <div class="mt-4">
            <h3><?php echo $this->lang->line('archive'); ?></h3>
            <?php
            foreach($archive_competitions as  $archive_competition)
            {
                ?>
                <div class="row bg-light rounded border border py-3 px-0 m-0 mb-1">

                    <div class="col-md-6">
                        <h6><?php echo $archive_competition["name"]?></h6> 
                        <p><?php echo $this->lang->line("competition_type_".$archive_competition['type']);?></p>                      
                    </div>
                    <div class="col-md-4">
                        <div class="font-italic">                    
                            <div><?php echo $this->lang->line('close_date'); ?>: <?php echo custom_date($archive_competition['end_registration_date'], $this->default_lang);?></div>
                            <div><?php echo ucfirst($this->lang->line("participants"))?>: <?php echo $archive_competition['participants_number'];?></div>
                            <div class="">
                                <?php 
                                if($archive_competition["type"] == "national")                                            
                                    echo ucfirst($this->lang->line("schools")).": ".$archive_competition['schools_number'];
                                else
                                    echo ucfirst($this->lang->line("countries")).": ".$archive_competition['countries_number'];    
                                ?>                    
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-2">                                                
                        <?php
                        //results
                        foreach($global_pages as $key=>$global_page)
                        {
                            if($global_page["page_id"] == 5)
                            {
                                ?>
                                <p>
                                    <a href="<?php echo $global_page["url"]?>/<?php echo $archive_competition['url_key'];?>" class="btn btn-dark btn-sm">
                                        <?php echo $this->lang->line("results")?>
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
            ?>
            </div>
            <?php                       
        }
    }    
    ?>
</article>