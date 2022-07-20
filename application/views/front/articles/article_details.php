<?php
$this->load->helper("date");
?>

<!-- Article -->
<section class="article">
    <div class="container">
        <div class="row">
            <div class="col-12">
                
                    
                <!-- Page content -->
                <div class="row">
                    <div class="col">
                        <div class="detail-content">                                                                                                                                                
                            <?php
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
                            ?>

                            <h2 class="blog-title"><?php echo $article["name"]?></h2>                                                                  

                            <?php                                
                            //abstract
                            if($article["abstract"])
                            {
                                ?>
                                <div class="blockquote">
                                    <p><i class="fa fa-location-arrow"></i> <?php echo nl2br($article["abstract"])?></p>
                                </div>
                                <?php
                            }                                

                            //image                                
                            $file_name 		= $article['image'];
                            $file_url		= $this->config->item('base_url').'uploads/pages/'.$file_name;
                            $file_path 		= $this->config->item('base_path').'uploads/pages/'.$file_name;
                            if($file_name && file_exists($file_path))
                            {	
                                ?> 
                                <img class="img-fluid float-right" src="<?php echo $file_url?>" alt="<?php echo htmlspecialchars($this->page_meta_title)?>"/>                 
                                <?php                       
                            }	
                            ?>                    
                            <?php echo $article["description"]?>                                                                                               
                        </div>
                    </div>
                </div>
                <!-- End Page content -->
                
                <?php                                        
                //files
                //====================================================
                if(isset($article["files"]) && $article["files"])
                {
                    require("article_files.inc.php");
                }

                //goole map
                //=========================================================                    
                if($article["map"])
                {        
                    ?>		                                        
                    <div class="row">
                        <div class="col-12">
                            <!-- map-area -->
                            <div class="map-area mt-3">                               
                                <?php echo $article["map"];?>
                            </div>
                            <!-- map-area end--> 
                        </div>
                    </div>    
                    <?php
                }

                //images
                //====================================================
                if(isset($article["images"]) && $article["images"])
                {
                    require("article_images.inc.php");
                }
                        
                //videos
                //====================================================
                if(isset($article["videos"]) && $article["videos"])
                {
                    require("article_videos.inc.php");
                }	
                ?>
                
                <?php
                /*
                <!-- Share -->
                <div class="row mt-4">
                    <div class="col">
                        <hr>
                        <div class="fb-like" data-href="<?php echo current_url()?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                        <div class="fb-share-button" data-href="<?php echo urlencode(current_url())?>" data-layout="button_count" data-size="large">
                            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(current_url())?>&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div> 
                <!-- End Share -->   
                */?>   
                
            </div>                               
        </div>
    </div>
</section>
<!--/ End Blog Single -->

<?php
/*
 <!-- Right Sidebar -->
 <div class="col-lg-4 col-12">
    <?php
    require_once("right.inc.php");
    ?>
</div>
<!-- End Right Sidebar -->   
*/
?>