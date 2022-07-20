<?php
$this->load->helper('form');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->page_meta_title; ?></title>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/admin.css"/>
</head>
<body style="padding:20px;">
<p class="footer" align="center">Va rugam sa uploadati fisiere in format jpg, gif, png de maxim 1 Mb</p>
<div align="center"><?php if(isset($message) && $message) echo '<div class = "error">'.$message.'</div>'; ?></div>
<form action="" method="post" enctype="multipart/form-data">
<table width="350" cellpadding="3" cellspacing="1">   
  <tr>    
    <td align="right">
	<input type="file" name="file" id="file" class="input"/></td>
	<td align="left">
	<input type="submit" name="Upload" id="Upload" value="<?php echo $this->lang->line('upload');?>" class="button"/>
	</td>
  </tr>
</table>
</form>
</body>
</html>