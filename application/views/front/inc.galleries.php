<?php
if(isset($galleries_categories) && $galleries_categories)
{
    ?>
    <div class="accordion" id="accordion">
    <?php
    foreach($galleries_categories as $key=>$galleries_category)
    {
        ?>
        <div class="card">
            <div id="card-header-<?php echo $key?>">
                <div class="card-header">
                    <h3 class="mb-0">
                        <div data-toggle="collapse" data-target="#card-body-<?php echo $key?>" aria-expanded="true" aria-controls="card-body-<?php echo $key?>">
                            <?php echo $galleries_category["galleries_category_name"]?>
                        </div>
                    </h3>
                </div>                
            </div>
            <div id="card-body-<?php echo $key?>" class="collapse show" aria-labelledby="card-header-<?php echo $key?>" data-parent="#accordion">
                <div class="card-body">
                    <?php
                    if(!$galleries_category["galleries"])
                        echo "...";
                    foreach($galleries_category["galleries"] as $k=>$gallery)
                    {
                        ?>
                        <div>
                            <a href="<?php echo base_url().$this->default_lang_url.$gallery["url_key"]?>">
                                <i class="fa fa-images"></i> <?php echo $gallery["name"]?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>                    
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