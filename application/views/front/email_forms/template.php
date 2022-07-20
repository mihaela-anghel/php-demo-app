<html>
<head>
    <title>{title}</title>
    <style type="text/css">
        body, html, p, div, span, td, th{	
            font-family:arial; 
            font-size:12px; 
        }
        body{ 
            margin:5px; 
            padding:0px;  
        }
        a{ 
            color: #0066CC; 
            text-decoration:none; 
        }
        a:hover{ 
            text-decoration: underline; 
        }                
    </style>
</head>
<body>    
    <div style="border:solid 1px #cccccc; border-radius:6px; padding:20px">        
        {body} 
        <hr style="margin:20px 0px; height:1px; border:0; background:#666666;"/>
        <p><a href="<?php echo base_url();?>"><?php echo $this->setting->item["site_name"]?></a></p>
        <a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>images/logo.png" border="0" width="100"/></a>        
    </div>       
</body>
</html>