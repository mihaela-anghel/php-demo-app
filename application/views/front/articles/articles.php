
<?php
if($this->uri->rsegment(1) == "articles")
{
	//page
	require_once(APPPATH."views/front/page.php");
}
?>

<!-- Articles -->
<section class="blog b-archives section pb-5">
    <div class="container">        
        <?php                    
        if(empty($articles))
        {
            ?>
            <div class="row">
                <div class="col">
                    <p><?php echo $this->lang->line('no_entries'); ?></p>
                </div>
            </div>
            <?php		
        }
        else
        {                            
            ?>                
            <div class="row">
               <?php               
               $this->load->helper("text");               
               foreach($articles as $key=>$article)
               {
                    $article_link = base_url().$this->default_lang_url.$article["url_key"];		
                    
                    if($article["abstract"] == "")
                    {
                        $article["abstract"] = strip_tags($article["description"]);	
                        $article["abstract"] = character_limiter($article["abstract"], 300);
                    }    				
                        
                    //image
                    //====================================================
                    $file_url		= $this->config->item('base_url').'image/r_350x450_crop/uploads/nopictures/nopicture.gif';
                    $file_name 		= $article['image'];		
                    $file_path 		= $this->config->item('base_path').'uploads/articles/'.$file_name;	
                    if($file_name && file_exists($file_path))
                        $file_url		= $this->config->item('base_url').'image/r_350x450_crop/uploads/articles/'.$file_name;
                   ?>
                   <div class="col-12 d-flex mb-3 pl-0">
                       <!-- Article -->
                       <div class="item">
                           <div class="row">                                                               
                                <div class="col-12">                                    
                                    <h3><a href="<?php echo $article_link?>"><?php echo $article["name"]?></a></h3>
                                </div>
                                <div class="col-10"> 
                                    <?php
                                    /*<img class="img-fluid m-3" src="<?php echo $file_url?>" alt="<?php echo $article["name"]?>"> */
                                    ?>
                                    <p>
                                        <?php echo $article['abstract'];?>                                                                                                                                              
                                    </p>  
                                </div>
                                <div class="col-2"> 
                                    <div class="button">
                                        <a href="<?php echo $article_link?>" class="btn btn-light">
                                            <?php echo $this->lang->line("read_more")?>
                                        </a>                                             
                                    </div>                                                   
                                    <?php
                                    /*
                                    if($article["map"])
                                    {
                                        ?>
                                        <div class="blog-info">
                                            <span>
                                                <i class="fa fa-map-marker"></i>
                                                <a href="<?php echo $article["url"]?>" data-toggle="modal" data-target="#mapModal<?php echo $key?>">
                                                    Localizare pe harta
                                                </a>
                                            </span>
                                            <!-- Modal -->
                                            <div class="modal fade" id="mapModal<?php echo $key?>" tabindex="-1" role="dialog" aria-labelledby="Localizare pe harta" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $article["name"]?></h5>                                                       
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="map">
                                                                <?php echo $article["map"]?>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    if($article["url"])
                                    {
                                        ?>
                                        <div class="blog-info">
                                            <span>
                                                <i class="fa fa-link"></i>
                                                <a href="<?php echo $article["url"]?>" target="_blank">
                                                    <?php echo substr($article["url"],0,strpos($article["url"],"/",8))?>
                                                </a>
                                            </span>                                                
                                        </div>
                                        <?php
                                    }  
                                    */                                      
                                    ?>
                                </div>                                                                   
                           </div>                           
                       </div>
                       <!-- End Article -->
                   </div>                       
                   <?php                  
               }               
               ?>
            </div>
            
            <div class="row">
                <div class="col text-center">
                    <?php echo $pagination; ?>
                </div>
            </div>

            <?php			              
        }	
        ?>                                
    </div>
</section>
<!--/ End Public services -->