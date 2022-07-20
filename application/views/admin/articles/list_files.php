<?php
//SUBMENU
require_once("menu.php");

//LOAD FORM HELPER
$this->load->helper('form');
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#file').uploadifive({
		'uploadScript'    	: '<?php echo admin_url()?>articles/files_upload/<?php echo $article['article_id']?>',				
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
									//window.location.reload();

                                    errors_no = $('#uploadifive-file-queue').find('.error').length;
                                    all_no = uploads.count
                                    if(errors_no == 0)
                                    {
                                        window.location.reload();
                                    } 
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
            	<input id="file" name="file" type="file"/>
            </form>           
		</td>
		<td>
            <div class="small">max 20 Mb</div>
            <div class="small">tineti apasat tasta SHIFT pentru a selecta mai multe fisiere</div>
        </td>
    </tr>
</table>

<!--LISTING-->
<form action="" method="post" name="listForm" onsubmit="if(!confirm('<?php echo $this->lang->line('confirm')?>')) return false;">
<table class="list_table">
    <tr>
        <th><?php echo $this->lang->line('file')?></th>
        <th><?php echo $this->lang->line('order')?></th>
        <th><?php echo $this->lang->line('status')?></th>
        <th><?php echo $this->lang->line('actions')?></th>
        <th><?php echo $this->lang->line('actions')?></th>	
	</tr>
	<?php      
    if(!$files)
    {
        ?><tr><td colspan="5"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($files as $file)
    {
        ?>
        <tr>
            <td>
            	<a href="<?php echo file_url()?>uploads/articles/files/<?php echo $file['filename']?>" target="_blank" class="fancybox_file">
                	<?php echo show_file_name($file['original_filename'])?>
                </a>
			</td>
            <td>
                <input type="text" value="<?php echo $file["order"]?>" onblur="window.location='<?php echo admin_url()?>articles/files_change/<?php echo $file["file_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>            
            <td>
				<?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."articles/files_change/".$file["file_id"]	
                                );
                if($this->admin_access['files'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($file[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($file[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($file[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>   
            <td>	
				<?php
                //actions
				if($this->admin_access["files"])	
                {
                    ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>articles/files_delete/<?php echo $file["file_id"] ?>'" class="delete">
                        <?php echo $this->lang->line("delete"); ?>
                    </a>
                    <?php
                }
                ?>
            </td>            
            <td>  
            	<input type="checkbox" name="item[]" value="<?php echo $file["file_id"]?>" />
				<?php echo $this->lang->line("delete"); ?>
            </td>    
        </tr>
        <?php
    }
    ?>
</table>
<?php
//delete all
if($files)	
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