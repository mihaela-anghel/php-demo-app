<html>
<head>
    <title></title>
    <style type="text/css">

        body{	
            font-family:"Helvetica"; 
            font-size:24px; 
            color:#000000;           
            margin: 0;
        }   
        .fundal{
            width:100%;
            height:100%;
            position:absolute;
            z-index:-1;
        }
        .container{                                
            width:100%;
            height:100%;
            margin:auto; 
            padding-left:1.6cm;  

            text-align:left;  
            font-weight:bold;                              
        }                                     
        .prize{           
            color:#ffffff;            
            padding-top:4.2cm;
            font-size: 1.6cm;
            font-weight:bolder;           
        }
        .name{
            padding-top:2cm;
            font-size:1.1cm;                                  
            font-weight:bolder;
            text-transform:uppercase;
        }
        .school{
            margin-top:-0.1cm;
            font-size:0.5cm;                                  
            font-weight:300;
        }
        .competition{                        
            padding-top:1.9cm;
            font-size:0.78cm;                                  
            font-weight:bolder;        
        }
        .category{
            margin-top:-0.1cm;
            font-size:0.5cm;                                  
            font-weight:300;
        }   
        .data, .number{                                           
            padding-left:1.4cm;          
            font-size:0.6cm;                      
            font-weight:bolder;
        }  
        .data{
            padding-top:1.3cm;
        }
        .number{                                
            padding-top:0.5cm;
        }  
        .partners{
            padding-top:1.65cm;
        }
        
        @page  {
            margin: 0cm;
        }
    </style>
</head>
<body>  
        
    <?php
    $this->load->helper('date');
   
    //diplama
    if(isset($participant["prize"]) && $participant["prize"]["type"] == "prize")    
        $background_url = file_url()."images/diploma.jpg"; 
    
    //diploma speciala
    elseif(isset($participant["prize"]) && $participant["prize"]["type"] == "special_diplama")
        $background_url = file_url()."images/diploma.jpg";

    //certificat de participare
    else
        $background_url = file_url()."images/certificate.jpg";

    if($participant["project_filename"] || $participant["project_link_extern"])
    {    
        ?> 
        <img src="<?php echo $background_url?>" class="fundal">   
        <div class="container">                            
            <div class="prize"><?php if(isset($participant["prize"])) echo $participant["prize"]["certificate"]; else echo "&nbsp;"?></div>                
            <div class="name"><?php echo $participant["name"]?></div>
            <div class="school"><?php echo $participant["school"]?>, <b><?php echo $participant["city"]?>, <?php echo $participant["country_name"]?></b></div>
            <div class="competition"><?php echo $competition["name"]?></div>
            <div class="category">age cat. <?php echo $participant["age_category_name"]?>, contest cat. <?php echo $participant["category_name"]?></div>
            <div class="data"><?php echo custom_date(date("Y-m-d"), "en");?></div> 
            <div class="number"><?php echo $participant["project_number"]?></div> 
            <?php
            if(isset($partners) && $partners)  
            {
                ?>
                <div class="partners">
                    <?php
                    foreach($partners as $partner)
                    {                    
                        //image	
                        $file_name		= $partner["image"];
                        $file_path 		= base_path()."uploads/partners/".$file_name;
                        $file_url 		= file_url()."uploads/partners/".$file_name;
                        if($file_name && file_exists($file_path))
                        {
                            ?>
                            <img src="<?php echo $file_url?>" height="200">                                                                   
                            <?php                                                                                              
                        }                                                                                       
                    }
                    ?>
                </div>
                <?php                
            }
            ?>                        
        </div>       
        <?php
    }
    ?>  
             
</body>
</html>
