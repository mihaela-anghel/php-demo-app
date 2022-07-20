<?php
/*
//current competition
if(isset($current_competition))
{
    ?>
    <div class="row">
		<div class="col">
            <h4><?php echo $current_competition["name"]?></h4>            
        </div>
    </div>    

   <div class="row bg-light mb-3 p-3">		
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

    <?php
    $total_no_special_diploma = 0;
    foreach($current_competition["prizes"] as $prize)                
        if($prize["type"] == "special_diplama")
            $total_no_special_diploma++;
    ?>    
    <div class="row">		
        <div class="col-12 col-sm-<?php echo ($total_no_special_diploma>=2?4:8)?>">	
        <h3><?php echo $this->lang->line("competition_special_diplomas")?></h3>
        <?php   
        $aux = 0;                                 
        foreach($current_competition["prizes"] as $prize)
        {
            if($prize["type"] == "special_diplama")
            {
                $aux++;
                ?>                
                <div><i class="fa fa-caret-right"></i> <?php echo $prize["certificate"]?></div>
                <?php
                if($prize["prize_name"])
                {
                    ?>
                    <p>                        
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
                    </p>                     
                    <?php
                }
                if($total_no_special_diploma >= 2 && ceil($total_no_special_diploma/2) == $aux)
                {
                    ?>
                    </div>
                    <div class="col-12 col-sm-4">
                    <h3 class="invisible"><?php echo $this->lang->line("competition_special_diplomas")?>:</h3>
                    <?php
                }               
            }
        }            
        ?> 
        </div>				
    
		<div class="col-12 col-sm-4">	
            <h3><?php echo $this->lang->line("competition_certificates")?></h3>
            <p><?php echo $this->lang->line("competition_certificates_text")?></p>
        </div>
    </div>     
    <?php 
}  
*/      
?>