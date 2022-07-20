<!--TINYMCE-->
<script type="text/javascript" src="<?php echo base_url();?>tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>tinymce/init.js"></script>

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
        <th width="<?php echo $th_width?>">
            <?php echo $this->lang->line("user_name"); ?>
        </th>
        <td>
            <?php echo form_error('name'); ?>
            <input type="text" name="name" id="name" value="<?php echo set_value("name"); ?>"/>*
        </td>
    </tr>    
    <tr>
        <th>
            <?php echo $this->lang->line("user_city"); ?>
        </th>
        <td>
            <?php echo form_error('city'); ?>
            <input type="text" name="city" id="city" value="<?php echo set_value("city"); ?>"/>
        </td>
    </tr> 
    <tr>
        <th>
            <?php echo $this->lang->line("user_country"); ?>
        </th>
        <td>
            <?php echo form_error('country_id'); ?>	
            <select name="country_id">
            <option value=""><?php echo $this->lang->line('select')?></option>
            <?php	
            foreach($countries as $country)
            {		
                if(175 == $country['country_id']) $selected = true; else $selected = false;
                ?><option value="<?php echo $country['country_id']?>" <?php echo set_select('country_id',$country['country_id'],$selected)?> ><?php echo $country['country_name']?></option><?php
            }
            ?>
            </select>
        </td>
    </tr>                 
    <tr>
        <th>
            <?php echo $this->lang->line("user_birthday"); ?>
        </th>
        <td>
            <?php echo form_error('birthday'); ?>
            <input type="text" name="birthday" id="birthday" value="<?php echo set_value("birthday"); ?>" readonly/>
            <script type="text/javascript" language="javascript">
            $(document).ready(function(){
                $('input[name="birthday"]').attachDatepicker({ 
                    rangeSelect: false, firstDay: 1, dateFormat: 'yy-mm-dd' 
                }); 
            });
        </script>
        </td>
    </tr>     
    <tr>
        <th>
            <?php echo $this->lang->line("user_school"); ?>
        </th>
        <td>
            <?php echo form_error('school'); ?>
            <input type="text" name="school" id="school" value="<?php echo set_value("school"); ?>"/>
        </td>
    </tr>    
    <tr>
        <th>
            <?php echo $this->lang->line("user_guide"); ?>
        </th>
        <td>
            <?php echo form_error('guide'); ?>
            <input type="text" name="guide" id="guide" value="<?php echo set_value("guide"); ?>"/>
        </td>
    </tr>  
    <tr>
        <th>
            <?php echo $this->lang->line("user_phone"); ?>
        </th>
        <td>
            <?php echo form_error('phone'); ?>
            <input type="text" name="phone" id="phone" value="<?php echo set_value("phone"); ?>"/>
        </td>
    </tr>   
    <tr>
        <th>
            <?php echo $this->lang->line("user_email"); ?>
        </th>
        <td>
            <?php echo form_error('email'); ?>
            <input type="text" name="email" id="email" value="<?php echo set_value("email"); ?>"/>
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("user_password"); ?>
        </th>
        <td>
            <?php echo form_error('password'); ?>
            <input type="text" name="password" id="password" value="<?php echo set_value("password"); ?>"/>            
        </td>
    </tr>
    <tr>
        <th>
            <?php echo $this->lang->line("user_confirm_password"); ?>
        </th>
        <td>
            <?php echo form_error('confirm_password'); ?>
            <input type="text" name="confirm_password" id="confirm_password" value="<?php echo set_value("confirm_password"); ?>"/>
        </td>
    </tr>                   
    <tr>
        <th>
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
				if($value == "1") $selected = true; else $selected = false;
				?><option value="<?php echo $value?>" <?php echo set_select("active",$value,$selected); ?>><?php echo $label?></option><?php
			}
			?>
			</select>
		</td>
	</tr>	
    <tr>
        <th>
        <td>
            <input type="submit" name="Add" id="Add" value="<?php echo $this->lang->line("add");?>"/>
        </td>
    </tr>
</table>
</form>