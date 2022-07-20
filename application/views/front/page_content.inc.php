<div class="page-content mb-4 clearfix">
    <?php
    //image
    $file_url		= "";
    $file_name 		= $page['image'];
    $file_path 		= $this->config->item('base_path').'uploads/pages/'.$file_name;
    if($file_name && file_exists($file_path))
    {
        $file_url	    = $this->config->item('base_url').'uploads/pages/'.$file_name;
        $file_url_th	= $this->config->item('base_url').'uploads/pages/'.get_thumb_name($file_name);
    }    
    if($file_url)
    {
        ?>
        <picture class="float-right w-50 ml-2 mb-2">
            <source media="(min-width: 650px)" srcset="<?php echo $file_url?>">
            <img src="<?php echo $file_url_th?>" class="img-fluid img-thumbnail" alt="<?php echo $page["name"]?>">
        </picture>
        <?php
    } 

    //title
    if(isset($this->page_title)) { ?><h1><?php echo $this->page_title?></h1><?php }

    //content
    echo $page["description"];
    ?>
</div>


    