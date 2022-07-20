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

<?php //jQuery Fancybox ?>
<?php if(!in_array($this->uri->rsegment("1"), array("no"))) { ?>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/jquery.fancybox-1.3.0/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/jquery.fancybox-1.3.0/fancybox/jquery.fancybox-1.3.0.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo file_url();?>js/admin/jquery.fancybox-1.3.0/fancybox/jquery.fancybox-1.3.0.css" media="screen"/>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/jquery.fancybox-1.3.0/fancybox/custom.js"></script>
<?php } ?>

<?php //jQuery Calendar ?>
<?php if(!in_array($this->uri->rsegment("1"), array("no"))) { ?>
<script src="<?php echo file_url();?>js/admin/datepicker/jquery.date.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo file_url();?>js/admin/datepicker/jquery.cal.js" type="text/javascript" language="javascript"></script>
<link rel="stylesheet" href="<?php echo file_url();?>js/admin/datepicker/flora.css" type="text/css" media="screen"/>
<?php } ?>

<?php //jQuery UploadiFive ?>
<?php if(in_array($this->uri->rsegment("2"), array("images","files","videos"))) { ?>
<link rel="stylesheet" href="<?php echo file_url();?>js/admin/UploadiFive-master/uploadifive.css" type="text/css" />
<script type="text/javascript" src="<?php echo file_url()?>js/admin/UploadiFive-master/jquery.uploadifive.min.js"></script>
<?php } ?>
      
 
<?php //My functions?>
<script language="javascript" type="text/javascript">var base_url = "<?php echo base_url();?>"; var base_path = "<?php echo base_path();?>";</script>
<script language="javascript" type="text/javascript" src="<?php echo file_url();?>js/admin/main.js"></script>
</head>
<body class="body_bg_template">

<?php //header ?>
<?php if(isset($this->page_header) && $this->page_header != "")	$this->load->view($this->page_header); ?>

<?php //content ?>
<table id="table_content">	
    <tr>
        <td class="page_content">  
		              
        <?php //navigation ?>
        <div class="navigation">		 
		<?php 
		if($this->uri->segment("2") == "home" || $this->uri->segment("2") == "")
		{
			?><span><?php echo $this->lang->line("home")?></span><?php
		}
		else
		{
			?><a href="<?php echo admin_url()?>"><?php echo $this->lang->line("home")?></a><?php
		}		
		$nav_section_name = $this->global_admin->get_section_name($this->uri->segment("2"));
		if($this->uri->segment("3") != "" && $this->uri->segment("3") != "index") 
		{ 
			?><a href="<?php echo admin_url().$this->uri->segment("2"); ?>"><?php echo $nav_section_name; ?></a><?php
			if(isset($this->page_title)) { ?><span><?php echo $this->page_title?></span><?php }
		}
		else if($this->uri->segment("2") != "home")
		{
			?><span><?php echo $nav_section_name;?></span><?php
		} 		
		?>	
		</div>
        
        <?php //page content ?>        	
        <?php if(isset($this->page_title)) {	?><h1><?php echo $this->page_title;?></h1><?php	} ?>    	
        <?php if(isset($body) && $body != "")	$this->load->view($body); ?>        
        
        </td>
    </tr>
</table>

<?php //footer ?>
<?php if(isset($this->page_footer) && $this->page_footer != "")	$this->load->view($this->page_footer); ?>

</body>
</html>
