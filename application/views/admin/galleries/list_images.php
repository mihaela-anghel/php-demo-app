<?php
//SUBMENU
require_once("menu.php");

//LOAD FORM HELPER
$this->load->helper('form');
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#file').uploadifive({
		'uploadScript'    	: '<?php echo admin_url()?>galleries/images_upload/<?php echo $gallery['gallery_id']?>',				
		'auto'      		: true,		
		'multi'     		: true,
		'buttonText'	 	: '<?php echo $this->lang->line('upload')?>',
		'queueSizeLimit' 	: 1000,
		'fileObjName'   	: 'file',
		'removeCompleted'	: false,
		'method'   			: 'post',
		'onError'      		: function(errorType) {
									alert('The error was: ' + errorType);
								},
		'onUploadComplete'	 : 	function(file, data){								  								  								 								  																																											
									output 		= data.split('*');
									type 		= output[0];
									message		= output[1];										 									
									if(type == 'success')
									{										
										$('#'+file.queueItem[0].id).append('<div class="done">'+message+'</div>');	
									}
									else if(type == 'error')
									{
										$('#'+file.queueItem[0].id).append('<div class="error">'+message+'</div>');																				
									}						  									
								},
		'onQueueComplete' 	: 	function(uploads) {
									//alert(uploads.successful + ' files were uploaded successfully.');
									window.location.reload();
								},								
		'onFallback'   		: 	function() {
									alert('Oops!  You have to use the non-HTML5 file uploader.');
								}								
				
	});
});
</script>

<!--UPLOAD FORM-->
<table>
    <tr>
        <td>
            <form action="" method="post" enctype="multipart/form-data">
            	<input id="file" name="file" type="file" />
            </form>
		</td>
		<td>
            <div class="small">JPG, GIF, PNG, max 3 Mb, recomandat 1000x1000px</div>
        </td>
    </tr>
</table>

<!--LISTING-->
<form action="" method="post" name="listForm" onsubmit="if(!confirm('<?php echo $this->lang->line('confirm')?>')) return false;">
<table class="list_table">
    <tr>
        <th><?php echo $this->lang->line('image')?></th>
        <th><?php echo $this->lang->line('order')?></th>
        <th><?php echo $this->lang->line('status')?></th>
        <th><?php echo $this->lang->line('actions')?></th>
        <th><?php echo $this->lang->line('actions')?></th>	
	</tr>
	<?php      
    if(!$images)
    {
        ?><tr><td colspan="5"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($images as $image)
    {
        ?>
        <tr>
            <td>
            	<a href="<?php echo file_url()?>uploads/galleries/images/<?php echo $image['filename']?>?<?php echo uniqid('')?>" class="fancybox_image">
                	<img src="<?php echo file_url()?>uploads/galleries/images/<?php echo $image['filename']?>?<?php echo uniqid('')?>" width="100"/>
                </a>
			</td>
            <td>
                <input type="text" value="<?php echo $image["order"]?>" onblur="window.location='<?php echo admin_url()?>galleries/images_change/<?php echo $image["image_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>            
            <td>
				<?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."galleries/images_change/".$image["image_id"]	
                                );
                if($this->admin_access['images'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($image[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($image[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($image[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>   
            <td>	
				<?php
                //actions
				if($this->admin_access["images"])	
                {
                    ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>galleries/images_delete/<?php echo $image["image_id"] ?>'" class="delete">
                        <?php echo $this->lang->line("delete"); ?>
                    </a>
                    <?php
                }
                ?>
            </td>            
            <td>  
            	<input type="checkbox" name="item[]" value="<?php echo $image["image_id"]?>" />
				<?php echo $this->lang->line("delete"); ?>
            </td>    
        </tr>
        <?php
    }
    ?>
</table>
<?php
//delete all
if($images)	
{
	?>
	<p align="right">
        <input type="button" onclick="checkAll(document.listForm.elements['item[]']);"   value="<?php echo $this->lang->line('select_all')?>" />
        <input type="button" onclick="uncheckAll(document.listForm.elements['item[]']);" value="<?php echo $this->lang->line('deselect_all')?>" />
        <input type="submit" name="DeleteSelected" value="<?php echo $this->lang->line('delete_selected')?>" class="button"/>
    </p>
	<?php
}	
?>
</form>