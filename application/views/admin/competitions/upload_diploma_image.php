<?php
//LOAD FORM HELPER
$this->load->helper("form"); 

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php } ?>

<p>
    JPG, GIF, PNG, max 6 Mb, 3531x2489 px (1090x770px 300 dpi)    
</p>


<div style="height:400px">
    <table cellpadding="10">
        <tr>
            <td class="border" valign="top">
                <h2>Diploma</h2>                
                <form action="" method="post" enctype="multipart/form-data">
                    <?php echo form_error("file"); ?>
                    <input type="file" name="file" id="file"/>
                    <input type="hidden" name="type" value="diploma">
                    <input type="submit" name="Upload" value="<?php echo $this->lang->line("upload");?>"/>          
                </form> 
                <p><img src="<?php echo file_url()?>images/diploma.jpg?<?php echo uniqid()?>" alt="Diploma" width="300"></p>           
            </td>
            <td class="border"  valign="top">
                <h2>Certificat</h2>                
                <form action="" method="post" enctype="multipart/form-data">
                    <?php echo form_error("file"); ?>
                    <input type="file" name="file" id="file"/>
                    <input type="hidden" name="type" value="certificate">
                    <input type="submit" name="Upload" value="<?php echo $this->lang->line("upload");?>"/>          
                </form>     
                <p><img src="<?php echo file_url()?>images/certificate.jpg?<?php echo uniqid()?>" alt="Diploma" width="300"></p>      
            </td>
        </tr>
    </table>
</div>
