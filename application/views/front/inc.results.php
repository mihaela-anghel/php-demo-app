<?php
//current competition
if(isset($competition))
{
    ?>
    <div class="row">
		<div class="col">
            <h3><?php echo $competition["name"]?></h3>
            <?php
            if($competition["status"] == "close")
            {
                ?>
                <div class="mb-3">
                    <small>
                        Competition statistics:
                        <?php echo $competition['participants_number'];?> <?php echo "competitors, "; //echo $this->lang->line("participants")?>                    
                        <?php                                                     
                        echo $competition['schools_number']." ".$this->lang->line("schools");

                        if($competition["type"] != "national") 
                            echo ", ".$competition['countries_number']." ".$this->lang->line("countries");    
                        ?>                     
                    </small> 
                </div> 

                <?php
                $prizes_no = 0;
                foreach($competition["prizes"] as $prize)            
                    if($prize["type"] == "prize")
                        $prizes_no++;

                if($prizes_no)
                {
                    ?>
                    <!-- prizes start -->
                    <div class="row border border-light m-0 mb-4 p-3"> 
                        <div class="col-12 p-0">
                            <h3><?php echo $this->lang->line("competition_prizes")?></h3>
                        </div>
                        <?php                                   
                        foreach($competition["prizes"] as $prize)
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
                <?php
            }
            if(isset($competition['results']) && $competition['results'] && $competition["status"] == "close")
            {
                ?>
                <p class="text-right">
                    <?php
                    //results
                    foreach($global_pages as $key=>$global_page)
                    {
                        if($global_page["page_id"] == 5)
                        {
                            ?>
                            <a href="<?php echo $global_page["url"]?>/<?php echo $competition['url_key'];?>/download" class="btn btn-dark btn-sm">
                                <i class="fa fa-download"></i>
                                Download all scores
                            </a>                              
                            <?php 
                            break;                                           
                        }
                    }
                    ?>
                    
                </p>
                <div class="table-responsive">
                    <table class="table">
                    <?php
                    $category_name      = "";
                    $age_category_name  = "";
                    foreach($competition['results'] as $participant)
                    {
                        if($this->setting->item["show_nowinners_in_results_page"] == "no" && !isset($participant["prize"]))
                            continue;

                        if($category_name != $participant['category_name'] || $age_category_name != $participant['age_category_name'])
                        {
                            $category_name      = $participant['category_name'];
                            $age_category_name  = $participant['age_category_name'];
                            ?>
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="6">
                                        <h5><?php echo $category_name?>, <?php echo $age_category_name?></h5>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                        }  
                        ?>
                        <tr class="<?php if($participant["diploma"] == "") echo "invalid-participant"?>">
                            <td>
                                <?php
                                if($participant["diploma"] == "")
                                    echo "*";
                                ?>
                                <?php echo $participant["name"]?>
                            </td>
                            <td><?php echo $participant["school"]?></td>
                            <td><?php echo $participant["city"]?>,<br><?php echo $participant["country_name"]?></td>
                            <td><?php echo $participant["note"]?></td>
                            <td style="font-size:20px">
                                <?php 
                                if(isset($participant["prize"]))
                                {
                                    if($participant["prize"]["type"] == "prize")
                                    {
                                        ?><i class="fas fa-medal text-success"></i> <?php                                   
                                    }
                                    else
                                    {
                                        ?><i class="fas fa-trophy text-primary"></i> <?php 
                                    }
                                }                                                                   
                                else
                                {
                                    ?><i class="fas fa-handshake text-secondary"></i> <?php                                    
                                } 
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(isset($participant["prize"]))
                                {
                                    echo  $this->lang->line("winner_message")."<br>".$participant["prize"]["certificate"];  

                                    if($participant["prize"]["type"] == "prize")
                                    {
                                        $project_link = ""; 

                                        //project external link
                                        if($participant["project_link_extern"])                        
                                            $project_link = $participant["project_link_extern"];

                                        //project file
                                        elseif($participant["project_filename"])                        
                                            $project_link      = base_url()."project/".$participant['project_number'];

                                        ?>
                                        <div>
                                            <a href="<?php echo $project_link?>" target="_blank" rel="nofollow" class="btn btn-info btn-sm">
                                                <?php echo $this->lang->line("competition_project")?>
                                            </a> 
                                        </div>
                                        <?php  
                                    }
                                }    
                                else  if($participant["diploma"] == "")
                                    echo "Submission deadline exceeded";
                                else
                                    echo $this->lang->line("not_a_winner_message");
                                    
                                ?>                                     
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </table>
                </div>

                <small>
                    * competitors who have exceeded the project submission deadline
                </small>
                <?php
            }
            else
            {
                ?>
                <h5>
                    <?php echo custom_date($competition['show_results_date'], $this->default_lang);?><br>                                
                    <?php echo $this->lang->line("show_results_date");?>
                </h5>  
                <?php
            }
            ?>		
		</div>
	</div>
   
    <?php 
}   
?>