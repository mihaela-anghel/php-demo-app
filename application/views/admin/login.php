<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(isset($this->page_meta_title) && $this->page_meta_title != "") { ?><title><?php echo $this->page_meta_title; ?></title><?php } ?>

<?php //css ?>
<link rel="stylesheet" type="text/css" href="<?php echo file_url();?>css/admin/admin.css" />
<link rel="shortcut icon" type="image/x-con" href="<?php echo base_url();?>images/admin/favicon.png"/>

<?php //jQuery Pack ?>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/jquery-1.4.3.js"></script>
</head>
<body class="body_bg_login">    
<div class="div_login">
    <table>    
        <tr>
            <td><h1><?php echo $this->lang->line("cms")?></h1></td>
        </tr>	
        <tr>
            <td>
				<?php
                $this->load->helper('form');
                echo validation_errors();              
                ?>
                <form action="<?php echo admin_url()?>login" method="post" name="form">
                    <div><?php echo $this->lang->line("username")?></div>
                    <input type="text" name="admin_username" value="<?php echo  set_value("admin_username")?>"/>
                    <script language="javascript" type="text/javascript">document.form.admin_username.focus()</script>

                    <p><?php echo $this->lang->line("password")?></p>
                    <input type="password" name="admin_password"/>

                    <p><input type="submit" name="AdminLogin" value="Login"/></p>                
                </form>
            </td>
        </tr>
        <tr>
            <td>
                <a href="<?php echo base_url()?>" target="_blank">
					<?php echo $this->lang->line("home")?>
				</a> | 
                <a href="http://webdesignsoft.ro" target="_blank">
					<?php echo $this->lang->line("powered_by")?> WebDesignSoft
                </a>
                 - Copyright &copy; <?php echo  date("Y")?>           
            </td>
        </tr>	
    </table>   
</div>	
</body>
</html>
