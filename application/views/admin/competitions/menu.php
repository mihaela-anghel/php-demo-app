<?php
if(isset($competition["name"]))
{
    ?><h2><?php echo $competition["name"]?></h2><?php
}
if(isset($competition_details["name"][$this->admin_default_lang_id]))
{
    ?><h2><?php echo $competition_details["name"][$this->admin_default_lang_id]?></h2><?php
}
?>

<!--SUBMENU-->
<ul class="submenu">	
	<?php 
    if($this->admin_access['edit_competition'])	
    {	
        $class = ($this->uri->rsegment(2) == "edit_competition" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>competitions/edit_competition/<?php echo $competition["competition_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("edit_competition"); ?>
            </a>
		</li>
		<?php
    }     
    if($this->admin_access['prizes'])	
    {	
        $class = ($this->uri->rsegment(2) == "prizes" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>competitions/prizes/<?php echo $competition["competition_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("competition_prizes"); ?>
            </a>
		</li>
		<?php
    }
    if($this->admin_access['participants'])	
    {	
        $class = ($this->uri->rsegment(2) == "participants" ? "selected" : "");
        ?>
        <li>
        	<a href="<?php echo admin_url()?>competitions/participants/<?php echo $competition["competition_id"] ?>" class="<?php echo $class?>">
				<?php echo $this->lang->line("competition_participants_registered"); ?>
            </a>
		</li>
		<?php
    }	  
    ?>       
</ul>

