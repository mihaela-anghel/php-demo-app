<!doctype html>
<html lang="<?php echo $this->default_lang?>">

    <head>
        <style>
        body{	
            font-family:"Helvetica"; 
            font-size:9px; 
            color:#000000;           
            margin: 0;           
        } 
        picture
        {
            display:block;
        } 
        img
        {
            height:50px;
            width:auto;
            margin-top:30px;           
        }
        h1
        {
            text-align:center;
        }
        h2
        {
            font-size:14px;
            background:#E77918;
            color:#ffffff;
            padding:5px 0px;
            margin-bottom:3px;
            text-align:center;
            display:block;
        }
        h3
        {
            font-size:11px;
            padding:5px 10px;
            background:#007CC2;
            color:#ffffff;
        }
        hr
        {
            height: 1px;
            border:0px;
            background:#efefef;
            margin:5px 0px;
            display:block;
        }
        .header
        {
            text-align:center;
        }
        .prices
        {
            text-align:center;           
        }
        .price
        {            
            border-top:0;
            width:33%;
            display:inline-block;           
        }
        .results
        {
            font-size:7px;
            font-weight:300;
        }        
        </style>
    </head>

    <body>       
        <?php 
        //require("inc.results.php"); 
        ?>

        <?php
        //current competition
        if(isset($competition))
        {
            ?>                
            <h1><?php echo $competition["name"]?></h1>
            <?php
            if($competition["status"] == "close")
            {
                ?>
                <div class="header">
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
                    <div class="prices"> 
                        <h2><?php echo $this->lang->line("competition_prizes")?></h2>                                
                        <?php                                   
                        foreach($competition["prizes"] as $prize)
                        {
                            if($prize["type"] == "prize")
                            {
                                ?> 
                                <div class="price">                                        
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
                                            <img src="<?php echo $file_url?>" alt="<?php echo $prize["prize_name"]?>">
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
                <div class="results">
                    <?php
                    $category_name      = "";
                    $age_category_name  = "";
                    $i = 1;
                    foreach($competition['results'] as $participant)
                    {
                        //if($this->setting->item["show_nowinners_in_results_page"] == "no" && !isset($participant["prize"]))
                            //continue;

                        if($category_name != $participant['category_name'] || $age_category_name != $participant['age_category_name'])
                        {
                            $category_name      = $participant['category_name'];
                            $age_category_name  = $participant['age_category_name'];
                            ?>
                            <h3><?php echo $category_name?>, <?php echo $age_category_name?></h3>
                            <?php
                        }  
                        ?>                        
                        <?php echo $i?>.   
                        <?php
                        if($participant["diploma"] == "")
                            echo "*<i>";
                        ?>
                        <?php echo $participant["name"]?>, 
                        <?php echo $participant["school"]?>, 
                        <?php echo $participant["city"]?>, <?php echo $participant["country_name"]?>, 
                        <?php echo $participant["note"]?>,                                                               
                        <?php 
                        if(isset($participant["prize"]))
                        {
                            echo  $this->lang->line("winner_message").", ".$participant["prize"]["certificate"];  

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
                                ,                                      
                                <a href="<?php echo $project_link?>" target="_blank">
                                    <?php echo $this->lang->line("competition_project")?>
                                </a>
                                <?php  
                            }
                        }    
                        else  if($participant["diploma"] == "")
                            echo "Submission deadline exceeded";
                        else
                            echo $this->lang->line("not_a_winner_message");                            
                        ?> 
                        <?php
                        if($participant["diploma"] == "")
                            echo "</i>";
                        ?>  
                        <hr>                                                                                              
                        <?php
                        $i++;
                    }
                    ?>
                </div>
                <small>
                    * competitors who have exceeded the project submission deadline
                </small>
                <?php                        
            }
            else
            {
                ?>
                <div class="results">
                    <h5>
                        <?php echo custom_date($competition['show_results_date'], $this->default_lang);?><br>                                
                        <?php echo $this->lang->line("show_results_date");?>
                    </h5>  
                </div>
                <?php
            }           
        }   
        ?>

    </body>

</html>