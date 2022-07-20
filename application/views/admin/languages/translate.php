<?php
//DONE OR ERROR MESSAGE
if(isset($_SESSION["done_message"]))
{	
	?><div class="done"><?php echo $_SESSION["done_message"];?></div><?php 
	unset($_SESSION["done_message"]);
}
if(isset($_SESSION["error_message"]))
{	
	?><div class="error"><?php echo $_SESSION["error_message"];?></div><?php 
	unset($_SESSION["error_message"]);
}	
?>

<!--INFO-->
<h2><?php echo $lang["name"]?></h2>
<p><?php echo $this->lang->line("translation_info")?></p>

<!--LISTING-->
<table>
    <tr>
    	<td width="200" valign="top" bgcolor="#efefef">
			<?php
            if(!$files)
            {
                echo $this->lang->line("translation_files_empty");
            }
            else
            {			
                ?>
                <p class="bold"><?php echo $this->lang->line("translation_files")?>:</p>
                <p><?php echo $this->lang->line("translation_files_info")?></p>
                <?php
            }
            foreach($files as $key=>$file)
            {
                ?>
                <p>
                    <a href="<?php echo admin_url()?>languages/translate/<?php echo $lang["lang_id"]?>/<?php echo substr($file["file_name"],0,-4)?>">
                        <?php echo ($key+1).". ".$file["file_name"]?>
                    </a>
                </p>
                <?php
            }
            ?>
        </td>
        <td valign="top">
            <h2><?php echo $file_name;?></h2>        
            <?php
            if($lines)
            {
                ?>
                <form action="" method="post">
                <table width="100%">
				<?php
                foreach($lines as $line)
                {
                    if($line["nr"]==2)
                    {
                        ?>
                        <tr>					
                            <td>
								<?php echo str_replace("<?php","",$line["left"])?>
                            </td>
                            <td>
                                =
                                <?php					
                                $text 			= trim($line["right"]);
                                $text_without_1 = substr($text,0,-1);
                                $text_without_1 = trim($text_without_1);
                                $first_quote 	= substr($text_without_1, 0,1);
                                $last_quote  	= substr($text_without_1, -1);
                                $text_without_2 = substr($text_without_1,0,-1);
                                $text_without_3 = substr($text_without_2,1);					
                                ?>                  
                                <input type="text" name="message[<?php echo $line["i"]?>]" value="<?php echo htmlspecialchars($text_without_3)?>" style="width:500px;"/>
                            </td>
                        </tr>
                        <?php
                    }
                    else if(trim($line["left"]))
                    {
                        ?>
                        <tr>					
                            <td colspan="2" class="small"><?php echo str_replace("<?php","",$line["left"])?></td>                            
                        </tr>
                        <?php
                    }
                }  
                ?>
                </table>
                <p align="right">
                	<input type="submit" name="Save" value="<?php echo $this->lang->line("save")?>"/>
                </p>
                </form>            
                <?php         
            }
            ?>   
       </td>
    </tr>
</table>