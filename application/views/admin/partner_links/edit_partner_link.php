<!--TINYMCE-->
<script type="text/javascript" src="<?php echo base_url();?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>tinymce/init.js"></script>

<script type="text/javascript">
    // construiec un array cu toate div-urile care trebuie ascunse
    var vector_page=new Array('div_file_type','div_script_type');
</script> 

<?php
//LOAD FORM HELPER
$this->load->helper("form"); 
$th_width 		= "150";
$table_width 	= "100%";

//DONE OR ERROR MESSAGE
if(isset($error_message) && $error_message) { ?><p class="error"><?php echo $error_message ?></p><?php }
if(isset($done_message) && $done_message) 	{ ?><p class="done"><?php echo $done_message ?></p><?php }
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } ?>

<form action="" method="post" enctype="multipart/form-data">
    <table class="form_table" width="<?php echo $table_width?>">       
        <tr>
            <th width="<?php echo $th_width?>"></th>
            <td>
                <?php	
                if($partner_link['type'] == 'script')	
                {
                    $default_1 = TRUE;
                    $default_0 = FALSE;
                }
                else	
                {
                    $default_0 = TRUE;
                    $default_1 = FALSE;
                }	
                ?>
                <input type="radio" name="type" value="script" <?php echo set_radio('type', 'script', $default_1)?> onclick="show_div(vector_page,'div_script_type') " /><?php echo $this->lang->line('partner_link_script_type')?>
                <input type="radio" name="type" value="file" <?php echo set_radio('type', 'file', $default_0)?> onclick="show_div(vector_page,'div_file_type') " /><?php echo $this->lang->line('partner_link_file_type')?>
            </td>
        </tr> 
    </table>

    <table class="form_table" width="<?php echo $table_width?>" id="div_file_type" style="display:<?php if(( isset($_POST['type']) && $_POST['type'] == 'file') || (!isset($_POST['type']) && $partner_link['type'] == 'file')) echo 'block'; else echo 'none';?>">
        <tr>
            <th width="<?php echo $th_width?>">
                <?php echo $this->lang->line('partner_link_file')?>
            </th>
            <td>
                <?php echo form_error('file');  ?>
                <input type="file" name="file" id="file" />*
                <p>JPG, GIF, PNG, max 3 Mb</p>
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $this->lang->line("partner_link_name"); ?>
            </th>
            <td>
                <?php echo form_error('name'); ?>
                <input type="text" name="name" id="name" value="<?php echo set_value("name", $partner_link["name"]); ?>" style="width:600px;" />
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $this->lang->line("partner_link_url"); ?>
            </th>
            <td>
                <?php echo form_error('url'); ?>
                <input type="text" name="url" id="url" value="<?php echo set_value("url", $partner_link["url"]); ?>" style="width:600px;" />
                <div class="small">(exp: http://www.exemple.com)</div>
            </td>
        </tr>
    </table>

    <table class="form_table" width="<?php echo $table_width?>" id="div_script_type" style="display:<?php if((isset($_POST['type']) && $_POST['type'] == 'script') || (!isset($_POST['type']) && $partner_link['type'] == 'script')) echo 'block'; else echo 'none';?>">
        <tr>
            <th width="<?php echo $th_width?>">
                <?php echo $this->lang->line("partner_link_script"); ?>                
            </th>
            <td>
                <?php echo form_error('script');  ?>
                <textarea name="script" style="width:600px;" ><?php echo  set_value('partner_link_script', $partner_link["script"])?></textarea>
            </td>
        </tr> 
    </table>  

    <table class="form_table" width="<?php echo $table_width?>">
        <tr>
            <th width="<?php echo $th_width?>">
                <?php echo $this->lang->line("status")?>
            </th>
            <td>
                <?php
                echo form_error("active");
                $active = array(	"0" => $this->lang->line("inactive"),
                                    "1" => $this->lang->line("active")	);
                ?>
                <select name="active">
                <?php
                foreach($active as $value=>$label)
                {
                    if($partner_link["active"] == $value) $selected = true; else $selected = false;
                    ?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$selected); ?>><?php echo $label?></option><?php
                }
                ?>
                </select>
            </td>
        </tr>	
        <tr>
            <th></th>
            <td>
                <input type="submit" name="Edit" id="Edit" value="<?php echo $this->lang->line("save");?>"/>
            </td>
        </tr>
    </table>
</form>