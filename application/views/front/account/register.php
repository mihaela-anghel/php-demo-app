<?php 
$show_form = true;

//title
/*if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }*/
   
//done message
if(isset($_SESSION['done_message'])) 
{		 
    ?><p class="alert alert-success text-center h5"><?php echo $_SESSION['done_message']?></p><?php 	
    unset($_SESSION['done_message']);

    $show_form = false;
}
//error message
if(isset($_SESSION['error_message'])) 
{		 
    ?><p class="alert alert-success"><?php echo $_SESSION['error_message']?></p><?php 	
    unset($_SESSION['error_message']);
}

if($show_form)
{
	?>
    <div class="row">          
        <div class="col">                    
            <!-- register form start-->   
            <?php require_once("inc.register.php")?>
            <!-- register form end-->       
        </div>    
    </div>
    <?php
}
?>