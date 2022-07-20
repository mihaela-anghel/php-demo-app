<?php                    
if(isset($articles) && $articles)
{                            
    ?>                
    <div class="learnedu-sidebar">
        <!-- Right pages -->
        <div class="single-widget categories">
            <h3 class="title"><?php echo $page["name"]?></h3>
            <ul>
                <?php               
                $this->load->helper("text");               
                foreach($articles as $key=>$article)
                {
                    $article_link = base_url().$this->default_lang_url.$article["url_key"];		
                    
                    if($article["abstract"] == "")
                        $article["abstract"] = strip_tags($article["description"]);	
                    $article["abstract"] = character_limiter($article["abstract"], 120);				
                        
                    //image
                    //====================================================
                    $file_url		= $this->config->item('base_url').'image/r_350x450_crop/uploads/nopictures/nopicture.gif';
                    $file_name 		= $article['image'];		
                    $file_path 		= $this->config->item('base_path').'uploads/articles/'.$file_name;	
                    if($file_name && file_exists($file_path))
                        $file_url		= $this->config->item('base_url').'image/r_350x450_crop/uploads/articles/'.$file_name;
                    ?>
                    <li>
                        <div class="single-post">
                            <div class="post-info">
                                <h3><a href="<?php echo $article_link?>"><?php echo $article["name"]?></a></h3>                                  
                            </div>
                            <div class="post-img">
                                <a href="<?php echo $article_link?>">
                                    <img src="<?php echo $file_url?>" alt="<?php echo $article['name']?>"/>
                                </a> 
                            </div>                           
                            <div class="post-info">                                
                                <span>
                                    <i class="fa fa-location-arrow"></i><?php echo $article["abstract"]?>
                                </span>                               
                            </div>                                                      
                        </div> 
                    </li>                                          
                    <?php                  
                }               
                ?>
            </ul>
        </div>
        <!--/ End Right pages -->
    </div>                         
    <?php			              
}	
?>