<?php 
//title
 if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }
   
//done message
if(isset($_SESSION['done_message'])) 
{		 
    ?><p class="alert alert-success"><?php echo $_SESSION['done_message']?></p><?php 	
    unset($_SESSION['done_message']);
}
//error message
if(isset($_SESSION['error_message'])) 
{		 
    ?><p class="alert alert-success"><?php echo $_SESSION['error_message']?></p><?php 	
    unset($_SESSION['error_message']);
}
?>