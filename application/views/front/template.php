<!doctype html>
<html lang="<?php echo $this->default_lang?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">		
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Meta tags -->
		<?php if(isset($this->page_meta_title) 		&& $this->page_meta_title != "") 		{ ?><title><?php echo htmlspecialchars($this->page_meta_title); ?><?php if($this->uri->rsegment(1)!="home") echo " - ".htmlspecialchars($this->logo_title)?></title><?php } ?>
		<?php if(isset($this->page_meta_description)&& $this->page_meta_description != "")  { ?><meta name="description" content="<?php echo htmlspecialchars($this->page_meta_description); ?>"><?php } ?>
		<?php if(isset($this->page_meta_keywords) 	&& $this->page_meta_keywords != "") 	{ ?><meta name="keywords" content="<?php echo htmlspecialchars($this->page_meta_keywords); ?>"><?php } ?>
		<?php if(isset($this->canonical_link) 		&& $this->canonical_link) 				{ ?><link rel="canonical" href="<?php echo $this->canonical_link?>"><?php } ?>
								
		<!-- Fontawesome 5.3.1 -->
		<link rel="stylesheet" href="<?php echo file_url();?>fonts/fontawesome-free-5.3.1-web/css/all.css" crossorigin="anonymous">           
		
		<!-- Bootstrap 4.4.1 -->
		<link rel="stylesheet" href="<?php echo file_url();?>js/bootstrap-4.4.1/css/bootstrap.min.css" crossorigin="anonymous"> 

		<!-- OwlCarousel 2.3.4 -->
		<link rel="stylesheet" href="<?php echo file_url();?>js/OwlCarousel2-2.3.4/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="<?php echo file_url();?>js/OwlCarousel2-2.3.4/assets/owl.theme.custom.css">	
		
		<!-- Fancybox 3.5.7 -->
		<link rel="stylesheet" href="<?php echo file_url();?>js/fancybox-master/dist/jquery.fancybox.min.css">

		<!-- DatePicker -->
		<link rel="stylesheet" href="<?php echo file_url();?>js/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker3.standalone.min.css">

		<!-- UploadiFive -->
		<link rel="stylesheet" href="<?php echo file_url();?>js/uploadifive-master-v1.2.2/uploadifive.css">

		<!-- CountDown -->
		<link href="<?php echo file_url();?>js/Attractive-jQuery-Circular-Countdown-Timer-Plugin-TimeCircles/inc/TimeCircles.css" rel="stylesheet">  
		
		<!-- Animate 3.7.2 -->
		<link rel="stylesheet" href="<?php echo file_url()?>css/animate.min.css">

		<?php //Cookie Consent plugin by Silktide?>        
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />

		<!-- Style -->
		<link rel="stylesheet" href="<?php echo file_url()?>css/style.css">		

		<!-- Favicon -->
		<link rel="icon" href="<?php echo file_url()?>images/favicon.png">
		
	</head>

	<body>
		<!-- Header -->
		<?php if(isset($this->page_header) && $this->page_header)	$this->load->view($this->page_header); ?>    

		<!-- Slider -->                            
		<?php  if($this->uri->rsegment(1) == "home") require_once("banners.inc.php");?>

		<?php
		$left_cols_no 	= (isset($this->page_left) && $this->page_left != ""?2:0);
		$right_cols_no 	= (isset($this->page_right) && $this->page_right != ""?3:0);
		$page_cols_no   = 12 - $left_cols_no - $right_cols_no;
		?>
		<main>
			<div class="container-fluid container-lg">
                
                <!-- Articles slider -->                            
                <?php require_once(APPPATH."views/front/articles/articles_slider.inc.php");?>
                
				<div class="row my-3">			
					<!-- Left sidebar -->
					<?php 				
					if(isset($this->page_left) && $this->page_left != "")	
					{
						?>
						<aside class="col-lg-<?php echo $left_cols_no?> order-2 order-lg-1">
							<?php $this->load->view($this->page_left); ?>
						</aside>
						<?php
					}	
					?>
					
					<section class="col<?php if($left_cols_no != 12) echo "-lg-".$page_cols_no?> p-0 order-3 order-lg-2">	                                               
						<div class="border border-secondary p-3 mx-2 mx-lg-0 my-0 py-3">
							<?php if(isset($body) && $body)	$this->load->view($body); ?> 							
						</div>						
					</section>
					
					<!-- Right sidebar -->
					<?php 
					if(isset($this->page_right) && $this->page_right != "")	
					{
						?>
						<aside class="col-lg-<?php echo $right_cols_no?> px-0 pl-0 pl-lg-3 order-1 order-lg-3">
							<?php $this->load->view($this->page_right); ?>
						</aside>
						<?php
					}	
					?>
				</div> 
			</div>						
		</main>

		<!-- Footer -->
		<?php if(isset($this->page_footer) && $this->page_footer)	$this->load->view($this->page_footer); ?>
				
		<!-- jQuery -->
		<script src="<?php echo file_url()?>js/jquery-3.4.1.min.js" crossorigin="anonymous"></script> 
		
		<!-- Bootstrap -->    
		<script src="<?php echo file_url()?>js/bootstrap-4.4.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		
		<!-- OwlCarousel 2.3.4 -->	
   		<script src="<?php echo file_url()?>js/OwlCarousel2-2.3.4/owl.carousel.min.js"></script>

		<!-- Fancybox 3.5.7 -->		
		<script src="<?php echo file_url();?>js/fancybox-master/dist/jquery.fancybox.min.js"></script>

		<!-- DatePicker -->
		<script src="<?php echo file_url();?>js/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js"></script>
		<?php if($this->default_lang != "en") { ?>
		<script src="<?php echo file_url();?>js/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.<?php echo $this->default_lang?>.min.js"></script>
		<?php } ?>

		<!-- jQuery Form Plugin -->
		<script src="<?php echo file_url()?>js/jquery.form.min.js" crossorigin="anonymous"></script>

		<!-- UploadiFive -->
		<script src="<?php echo file_url()?>js/uploadifive-master-v1.2.2/jquery.uploadifive.min.js"></script>

		<!-- CountDown -->
        <script src="<?php echo file_url();?>js/Attractive-jQuery-Circular-Countdown-Timer-Plugin-TimeCircles/inc/TimeCircles2.js"></script>		

		<!-- Main Js -->
		<script>
		var base_url = "<?php echo base_url();?>"; 
		var base_path = "<?php echo base_path();?>"; 
		var lang_code = "<?php echo $this->default_lang?>";
		var required_field = "<?php echo $this->lang->line("required_field")?>";
		var select_project = "<?php echo $this->lang->line("select_project")?>";
		</script>
		<script src="<?php echo file_url();?>js/main.js"></script>
							
		<!--[if lt IE 9]>
		<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<?php //Google Recaptcha ?>
		<script src='https://www.google.com/recaptcha/api.js?&hl=<?php echo $this->default_lang?>'></script>  
		
		<?php
		//cookie params
		$cookie_href = "";
		foreach($global_pages as $key=>$global_page)
		{
			if($global_page["page_id"] == 9)
			{
				$cookie_href = $global_page["url"];				
				break;                                           
			}
		}
		$cookie_message = "Acest site foloseste cookie-uri. Prin continuarea navigarii, esti de acord cu modul de utilizare a acestor informatii.";
		$cookie_dismiss = "Accept";
		$cookie_deny 	= "Mai tarziu";
		$cookie_link 	= "Afla mai multe";
		?>

		<?php //Cookie Consent plugin by Silktide?>        
		<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
		<script>
		window.cookieconsent.initialise({
		"palette": {
			"popup": {
				"background": "#333333",
				"text": "#ffffff",		
				},
			"button": {
				"background": "#E77918",
				"text": "#ffffff"
			}
		},
		"position": "bottom-right",
		"type": "opt-out",
		<?php if($this->default_lang == "ro") { ?>
		"content": {
			"message": "<?php echo $cookie_message?>",
			"dismiss": "<?php echo $cookie_dismiss?>",
			"deny": "<?php echo $cookie_deny?>",
			"link": "<?php echo $cookie_link?>",
			"href": "<?php echo $cookie_href?>"
		}
		<?php } ?>
		});
		</script>  
		
		<?php
		//Google analytics
		$google_analytics = $this->setting->item["google_analytics"];
		if($google_analytics && $google_analytics != "#") 
			echo $google_analytics;
		?> 		
	</body>
</html>