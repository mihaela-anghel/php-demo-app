
<!-- footer start -->       
<footer>    
    <div class="container-fluid container-lg text-left">
        <?php
        //right banners
        if($this->agent->is_mobile())
        {            
            ?>
            <div class="d-block d-lg-block">    
            <?php
            require(APPPATH."views/front/banners_right.inc.php");
            ?>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <em><?php echo $this->setting->item["text_footer"]?></em>
        </div>
        <div class="row">
            <div class="col px-0">                
                <ul class="footer-partners">
                    <?php 						
                    foreach($global_partners as $key=>$global_partner)
                    {                       
                        if($global_partner["on_footer"] == "1")
                        {
                            //image	
                            $file_name		= $global_partner["image"];
                            $file_path 		= base_path()."uploads/partners/".$file_name;
                            $file_url 		= file_url()."uploads/partners/".$file_name;
                            if($file_name && file_exists($file_path))
                            {
                                ?>                           
                                <li>
                                    <?php if($global_partner["url"]) { ?><a href="<?php echo $global_partner["url"];?>" target="_blank" rel="nofollow"><?php } ?>
                                        <img src="<?php echo $file_url?>" title="<?php echo $global_partner["name"];?>" class="img-fluid">
                                    <?php if($global_partner["url"]) { ?></a><?php } ?> 
                                </li>                                                                                       
                                <?php                                                                                               
                            }   
                        }                                                                       
                    }			
                    ?>
                </ul>
            </div>
        </div>

        <div class="row bg-light">
            <div class="col">
                <ul class="footer-menu">
                <?php 						
                $i = 0;
                foreach($global_pages as $key=>$global_page)
                {
                    if($global_page["on_footer"] == "1")
                    {
                        $i++;
                        ?>
                        <li><a href="<?php echo $global_page["url"]?>"><?php echo $global_page["name"]?></a></li>
                        <?php                        
                        /* if($i==4)
                        {
                            ?></ul>
                            <ul><?php
                        } */
                    }
                }			
                ?>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col">                
                <div class="copyright py-3">
                    Copyright &copy; <?php echo date("Y")?> <a href="<?php echo base_url()?>"><?php echo $this->setting->item["site_name"]?></a>.  
                    <?php echo $this->lang->line("all_rights_reserved")?>
                    <?php /*<a class="powered_by" href="http://www.webdesignsoft.ro" target="_blank" title="Web Design">Web design  by WebDesignSoft</a>*/?>
                </div>
            </div>
        </div>

        <!-- partner links start -->                              
        <?php         
        if(isset($global_partner_links) && $global_partner_links)
        {						
            ?>
            <div class="row mb-3">
                <div class="col px-0">  
                    <ul class="footer-partners">
                    <?php
                    foreach($global_partner_links as $key=>$global_partner_link)
                    {                                                   
                        ?>                           
                        <li>
                            <?php
                            if($global_partner_link["type"] == "file")
                            {            
                                //image	
                                $file_name		= $global_partner_link["filename"];
                                $file_path 		= base_path()."uploads/partner_links/".$file_name;
                                $file_url 		= file_url()."uploads/partner_links/".$file_name;
                                if($file_name && file_exists($file_path))
                                {
                                    ?>
                                    <?php if($global_partner_link["url"]) { ?><a href="<?php echo $global_partner_link["url"];?>" target="_blank" rel="nofollow"><?php } ?>
                                        <img src="<?php echo $file_url?>" title="<?php echo $global_partner_link["name"];?>">
                                    <?php if($global_partner_link["url"]) { ?></a><?php } ?> 
                                    <?php
                                }
                            }
                            if($global_partner_link["type"] == "script")
                            {            
                                echo $global_partner_link["script"];
                            }
                            ?>
                        </li>                                                                                       
                        <?php                                                                                                                                                                                                    
                    }
                    ?>
                    </ul>
                </div>
            </div>
            <?php
        }			
        ?>
        <!-- partner links end -->
        
    </div>            
</footer> 
<!-- footer end -->  