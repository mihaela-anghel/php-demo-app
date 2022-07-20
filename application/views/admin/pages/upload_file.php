<?php
//LOAD FORM HELPER
$this->load->helper("form"); 

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>
<ul>
<?php
if($type == "image")
{
	?>
	<li>JPG, GIF, PNG, max 3 Mb</li>    
    <li>latime recomandata 800px</li>
	<?php
}
if($type == "banner")
{
	?>
	<li>JPG, GIF, PNG, max 3 Mb</li>
    <li>dimensiune recomandata 1600x375 px</li>
	<?php
}
if($type == "icon")
{
	?>
	<li>PNG transparent, max 3 Mb</li>
    <li>dimensiune recomandata 35x35 px (subpagini Home)</li>
    <li>dimensiune recomandata 80x80 px (subpagini Despre noi)</li>
	<?php
}
?>
</ul>

<form action="" method="post" enctype="multipart/form-data">
<table class="form_table">
    <tr>    	
   		<td>
			<?php echo form_error("file"); ?>
            <input type="file" name="file" id="file"/>
		</td>
        <td>
        	<input type="submit" name="Upload" id="Upload" value="<?php echo $this->lang->line("upload");?>"/>
        </td>
    </tr> 
</table>      
</form>