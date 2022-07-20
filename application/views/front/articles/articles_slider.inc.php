<?php      
$x_cols_no 	= 0;
if(isset($slider_articles) && $slider_articles)
{
    $x_cols_no 	= 12;
    if(trim($this->setting->item["text_kcc_info"]))
        $x_cols_no 	= 9;
}

$y_cols_no 	= 0;
if(trim($this->setting->item["text_kcc_info"]))	
{
    $y_cols_no 	= 12;
    if(isset($slider_articles) && $slider_articles)
        $y_cols_no 	= 3;
}

if($x_cols_no + $y_cols_no)
{
    ?>
    <div class="row">
        <?php
        //slider_articles
        if(isset($slider_articles) && $slider_articles)
        {       
            ?>      
            <div class="col-sm-12 col-lg-<?php echo $x_cols_no?> p-0">
                <article class="breaking-news-area mx-2 mx-lg-0 mt-1">                       
                    <div class="row align-items-center justify-content-center">
                        <div class="col-sm-3 pr-0">
                            <div class="title">
                                <?php echo $this->lang->line("breaking_news")?>
                                <i class="fa fa-caret-right"></i>
                            </div>
                        </div>
                        <div class="col-sm-9 pl-0">
                            <div class="content">
                                <div class="breaking-news owl-carousel owl-theme">
                                    <?php                                 
                                    foreach($slider_articles as $slider_article)
                                    {            
                                        $slider_article_link = base_url().$this->default_lang_url.$slider_article["url_key"];	
                                        ?>
                                        <div class="item"> 
                                            <div>
                                                <a href="<?php echo $slider_article_link?>"><?php echo $slider_article["name"]?></a>                                
                                            </div>                               
                                        </div>                                                                                                
                                        <?php                                                                                        
                                    }
                                    ?>                                                 
                                </div> 
                            </div>  
                        </div>
                    </div>                                             
                </article>  
            </div>                    
            <?php
        }

        if(trim($this->setting->item["text_kcc_info"]))	
        {
            ?>
            <div class="col-sm-12 col-lg-<?php echo $y_cols_no?> px-0 pl-0 pl-lg-<?php echo ($x_cols_no?3:0)?>">
                <div class="mx-2 mx-lg-0 mt-1">                       
                    <div class="external-partent">
                        <div><small><?php echo $this->setting->item["text_kcc_info"]?></small></div>
                        <a href="https://www.qwerty.info" target="_blank">www.qwerty.info</a>
                    </div>
                </div>       
            </div>
            <?php
        }	
        ?>
    </div>
    <?php
}
?>    