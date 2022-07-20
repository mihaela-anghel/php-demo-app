<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(isset($this->page_meta_title) && $this->page_meta_title != "") { ?><title><?php echo $this->page_meta_title; ?></title><?php } ?>

<?php //css ?>
<link rel="stylesheet" type="text/css" href="<?php echo file_url();?>css/style.css" />
<link rel="shortcut icon" type="image/x-con" href="<?php echo base_url();?>images/favicon.png"/>

<?php //jQuery Pack ?>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/jquery-1.4.3.js"></script>

<?php //My functions?>
<script language="javascript" type="text/javascript">var base_url = "<?php echo base_url();?>"; var base_path = "<?php echo base_path();?>";</script>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/functions.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/ajax.js"></script>

</head>
<body>
<?php 
//page title
/*if(isset($this->page_title)) 
{	
	?><h1><?php echo $this->page_title;?></h1><?php	
}*/		
//page content
if(isset($body) && $body != "")	
	$this->load->view($body); 
?>	
</body>
</html>
