<?php
//right banners
if(isset($right_banners) && $right_banners)
{
    $banners 		= $right_banners;		
           
    foreach($banners as $key=>$banner)
    {		
        $banner_src = base_url()."uploads/banners/".$banner['filename'];	
        
        $banner['description_'.$this->default_lang] = nl2br($banner['description_'.$this->default_lang]);
        
        if($banner["url"])
        {
            $banner["url"] = str_replace("en/",$this->default_lang."/",$banner["url"]);
            $banner["url"] = str_replace("ro/",$this->default_lang."/",$banner["url"]);
        }
        ?>
        <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_banner_<?php echo $banner["banner_id"]?>">
            <img class="img-fluid mb-3" src="<?php echo base_url()."uploads/banners/".$banner['filename'];?>" alt="<?php echo $banner['name_'.$this->default_lang]?>">                         
        </a>
        
        <!-- Modal banner-->
        <div class="modal" data-show="true"  id="modal_banner_<?php echo $banner["banner_id"]?>" tabindex="-1" role="dialog" aria-labelledby="text_popup_register_now_inactiv" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">                                               
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php echo $banner['name_'.$this->default_lang]?>
                        </h5>                                       
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>                                            
                    <div class="modal-body">
                        <?php echo $banner['description_'.$this->default_lang]?>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <?php
                            if($banner["url"] != "") 
                            {  
                                ?>
                                <a href="<?php echo $banner['url'] ?>" class="btn btn-secondary">
                                    <?php echo $this->lang->line("read_more")?>
                                </a>
                                <?php
                            } 								
                            ?>             
                            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>                                                                                                                                                                                                                                          
                        </div>                                                                                     
                    </div>
                </div>
            </div>
        </div>
        <?php				
    }                       
}
?>