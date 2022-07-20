<?php
//current competition
if(isset($current_competition))
{
    ?>
    <div class="row">
		<div class="col">
            <h3><?php echo $current_competition["name"]?></h3>
			<?php echo $current_competition["rules"]?>		
		</div>
	</div>
    <?php 
}        
?>