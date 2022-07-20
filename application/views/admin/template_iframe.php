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

<?php //My functions?>
<script language="javascript" type="text/javascript">var base_url = "<?php echo base_url();?>"; var base_path = "<?php echo base_path();?>";</script>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/main.js"></script>

<?php //Iframe Auto Height?>
<script language="javascript" type="text/javascript">$(document).ready(function() { auto_height("fancybox-outer","body",60); auto_height("fancybox-inner","body",50); }); </script>

</head>
<body class="body_bg_template_iframe">
<?php 
//page title
if(isset($this->page_title)) 
{	
	?><h1><?php echo $this->page_title;?></h1><?php	
}		
//page content
if(isset($body) && $body != "")	
	$this->load->view($body); 
?>	
</body>
</html>
