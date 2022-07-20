<!-- header start -->
<header>        
    <div class="container-fluid container-lg">
        <div class="row">
            <!-- logo start-->
            <div class="col-lg-3 d-none d-lg-block p-0">
                <div id="logo" class="pr-3">                    
                    <a href="<?php echo base_url().$this->default_lang_url;?>" title="<?php echo htmlspecialchars($this->logo_title)?>">
                        <img src="<?php echo base_url()?>images/logo.png" alt="<?php echo htmlspecialchars($this->logo_title)?>" class="img-fluid" />
                    </a>
                </div>
            </div>
            <!-- logo end-->
            
            <div class="col-lg-9 p-0"> 
                
                <!-- top header start -->
                <div class="top-header"> 

                    <!-- partners start -->
                    <div class="top-partners owl-carousel">
                        <?php  
                        $i=0;                      
                        foreach($global_partners as $global_partner)
                        {
                            if($global_partner["on_header"] == "1")
                            {
                                //image	
                                $file_name		= $global_partner["image"];
                                $file_path 		= base_path()."uploads/partners/".$file_name;
                                $file_url 		= file_url()."uploads/partners/".$file_name;
                                if($file_name && file_exists($file_path))
                                {
                                    ?>
                                    <div class="item align-items-center">
                                        <?php if($global_partner["url"]) { ?><a href="<?php echo $global_partner["url"];?>" target="_blank" rel="nofollow"><?php } ?>
                                            <img src="<?php echo $file_url?>" title="<?php echo $global_partner["name"];?>">
                                        <?php if($global_partner["url"]) { ?></a><?php } ?>                                        
                                    </div>                                                                   
                                    <?php
                                    $i++;                                                                       
                                }                                       
                            }                            
                        }
                        ?>                     
                    </div>
                    <!-- partners end -->                                   

                    <div class="my-1 my-lg-3">                        
                        <h2><?php echo $this->setting->item["text_header"]?></h2>                    
                    </div>                                    
                </div>
                <!-- top header end -->                                
                
                <!-- navigation start -->
                <nav class="navbar navbar-expand-lg navbar-menu">

                    <a class="navbar-brand d-block d-lg-none mr-auto order-1 order-lg-1" href="<?php echo base_url().$this->default_lang_url;?>" title="<?php echo htmlspecialchars($this->logo_title)?>">
                        <img src="<?php echo base_url()?>images/logo.png" alt="<?php echo htmlspecialchars($this->logo_title)?>" class="img-fluid" style="max-height:40px"/>
                    </a>

                    <button class="btn btn-light d-block d-lg-none mr-3 order-3 order-lg-4" data-toggle="collapse" data-target="#login-area" aria-expanded="false" aria-controls="login-area">
                        <?php
                        if(isset($_SESSION['auth'])) 
                        { 
                            ?><i class="fa fa-user-check"></i><?php
                        }
                        else
                        { 
                            ?><i class="fa fa-user-lock"></i><?php
                        }
                        ?>                        
                    </button>

                    <button class="navbar-toggler collapsed order-4 order-lg-2" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar top-bar mt-0"></span>
                        <span class="icon-bar middle-bar"></span>
                        <span class="icon-bar bottom-bar"></span>
                    </button>                    

                    <div class="collapse navbar-collapse order-5 order-lg-3" id="navbar-menu">
                        <?php 						
                        foreach($menu_pages as $key=>$menu_page)
                        {            
                            $active_class = "";
                            /* if( (isset($page["page_id"]) && $page["page_id"] == $menu_page["page_id"] ) ||
                            (isset($page["parent_id"]) && $page["parent_id"] == $menu_page["page_id"] )
                            ) */
                            if( isset($page["page_id"]) && $page["page_id"] == $menu_page["page_id"] && $menu_page["level"]  == 0)                            
                                $active_class .= " active"; 
                            
                            $menu_page["li_attributes"] = array();
                            $menu_page["a_attributes"] = array();

                            if($menu_page["parent_id"] == 0)
                            {
                                if($menu_page["childs_number"] == 0)
                                {
                                    $menu_page["li_attributes"]     = array(    "class"         => "nav-item");

                                    $menu_page["a_attributes"]      = array(    "class"         => "nav-link ".$active_class,
                                                                                "id"            => "menu-".$menu_page["page_id"]
                                                                            );
                                }    
                                else
                                {
                                    $menu_page["li_attributes"]     = array(    "class"          => "nav-item dropdown");
                                    
                                    $menu_page["a_attributes"]      = array(    "class"          => "nav-link dropdown-toggle ".$active_class,
                                                                                "id"            => "menu-".$menu_page["page_id"],
                                                                                "data-toggle"   => "dropdown",
                                                                                "aria-haspopup" => "true",
                                                                                "aria-expanded" => "false",
                                                                            );
                                }
                            }
                            else
                            {
                                if($menu_page["childs_number"] == 0)
                                {
                                    $menu_page["li_attributes"]     = array();

                                    $menu_page["a_attributes"]      = array(    "class"         => "dropdown-item ".$active_class);
                                }    
                                else
                                {
                                    $menu_page["li_attributes"]     = array(    "class"         => "dropdown-submenu");
                                    
                                    $menu_page["a_attributes"]      = array(    "class"         => "dropdown-item dropdown-toggle ".$active_class,
                                                                                "id"            => "menu-".$menu_page["page_id"],
                                                                                "data-toggle"   => "dropdown",
                                                                                "aria-haspopup" => "true",
                                                                                "aria-expanded" => "false",
                                                                            );
                                }
                            }                                                                                                              	
                                
                            $menu_pages[$key] = $menu_page;
                        }			
                        echo $this->tree->print_tree($menu_pages);	
                        ?>                                                
                    </div>                    
                    
                    <?php
                    if(count($global_languages) > 1)											
                    {
                        ?>
                        <!-- top languages start -->
                        <div class="top-languages dropdown mr-2 mr-lg-0  order-2 order-lg-5">
                            <button type="button" id="languages" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?php
                                //langs	                            
                                foreach($global_languages as $language)
                                {				
                                    if($language['code'] == $this->default_lang)
                                    {
                                        echo ucfirst($language['name']);
                                        break;
                                    }                                   								                                    
                                }
                                ?>
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="languages">                                 
                                <?php
                                //langs												
                                foreach($global_languages as $language)
                                {				
                                    if($language['code'] == $this->default_lang)
                                        continue;

                                    //if($this->uri->segment(2))
                                        //$go_to = base_url().str_replace($this->uri->segment(1)."/",$language['code']."/",uri_string());
                                    //else
                                        $go_to = base_url().$language['code']."/";	
                                    ?>
                                    <li>																	
                                        <a href="<?php echo $go_to?>" title="<?php echo $language['name']?>" class="dropdown-item">
                                            <?php echo ucfirst($language['name'])?>
                                        </a>
                                    </li>								
                                    <?php
                                }
                                ?>                                                               
                            </ul>
                        </div>
                        <!-- top languages end -->
                        <?php
                    }
                    ?>
                </nav>
                <!-- navigation end -->

            </div>            
        </div>
    </div>       
</header>
<!-- header end -->	

<?php
//Ad text
if($this->setting->item["ad_text"]) 
{
    ?>        
    <div class="alert alert-primary text-center rounded-0">                        
        <?php echo nl2br($this->setting->item["ad_text"])?>
    </div> 
    <?php
}
?> 

<?php
//menu exp:
/*
<ul class="navbar-nav ml-auto mt-2 mt-lg-0 mr-3">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menu-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Homepage
        </a>
        <ul class="dropdown-menu" aria-labelledby="menu-1">
            <li><a class="dropdown-item" href="#">
                    Homepage 1</a>
            </li>
            <li><a class="dropdown-item" href="#">
                    Homepage 2</a>
            </li>
            <li><a class="dropdown-item" href="#">
                    Homepage 3</a>
            </li>
            
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="menu-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Pages
        </a>
        <ul class="dropdown-menu" aria-labelledby="menu-2">
            <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">Demo 1</a>
                <ul class="dropdown-menu ">
                    <li><a class="dropdown-item" href="#">
                            Demo sub one</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                                Demo sub two</a>
                    </li>                                                                                        
                </ul>
            </li>
            <li class="dropdown-submenu">
                <a class="dropdown-item dropdown-toggle" href="#">Demo 2</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">
                            Demo sub two</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            Demo sub three</a>
                    </li>
                    
                </ul>
            </li>
            
        </ul>
    </li>
    
    <li class="nav-item dropdown mega-dropdown">
        <a class="nav-link dropdown-toggle" href="" id="menu-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Blocks
        </a>
        <ul class="dropdown-menu mega-dropdown-menu" aria-labelledby="menu-4">
            <li class="row">
                <ul class="col">
                    <li><a class="dropdown-item" href="#">
                            block 1</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 2</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 3</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 4</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 5</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 6</a>
                    </li>
                </ul>
                <ul class="col">
                    <li><a class="dropdown-item" href="#">
                            block 7</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 8</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 9</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 10</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 11</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 12</a>
                    </li>
                </ul>
                <ul class="col">
                    <li><a class="dropdown-item" href="#">
                            block 13</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 14</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 15</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 16</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 17</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 18</a>
                    </li>
                </ul>
                <ul class="col">
                    <li><a class="dropdown-item" href="#">
                            block 19</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 20</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 21</a>
                    </li>
                    <li><a class="dropdown-item" href="#">
                            block 22</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="contact-us.html">Contact us</a>
    </li>
</ul>
*/
?>

