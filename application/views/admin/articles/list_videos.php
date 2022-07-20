<?php
//SUBMENU
require_once("menu.php");

//LOAD FORM HELPER
$this->load->helper('form');
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#file').uploadifive({
		'uploadScript'    	: '<?php echo admin_url()?>articles/videos_upload/<?php echo $article['article_id']?>',				
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

<table cellpadding="10" cellspacing="3">
    <tr>             
        <td valign="top" class="border">
            <h2>Adauga cod video (embed video)</h2>
            <div class="small"><pre><xmp>Exp: <iframe width="560" height="315" src="https://www.youtube.com/embed/gcsbfGYxTp0" title="YouTube video player" frameborder="0"></iframe></xmp></pre></div>
            <!--UPLOAD FORM-->
            <form action="" method="post">
                <table>
                    <tr>
                        <td>
                            <textarea name="video" style="width:400px; height:70px;"><?php echo set_value("video")?></textarea>                
                        </td>
                        <td>
                            <input type="submit" name="Add" value="<?php echo $this->lang->line('add')?>" />            
                        </td>
                    </tr>
                </table>
            </form>
            <?php echo form_error("video"); ?>            
        </td>
        <td valign="top" class="border">
            <h2>Adauga fisier video (mp4)</h2>            
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
        </td>  
    </tr>
</table>

<!--LISTING-->
<form action="" method="post" name="listForm" onsubmit="if(!confirm('<?php echo $this->lang->line('confirm')?>')) return false;">
<table class="list_table">
    <tr>
        <th><?php echo $this->lang->line('article_videos')?></th>
        <th><?php echo $this->lang->line('order')?></th>
        <th><?php echo $this->lang->line('status')?></th>
        <th><?php echo $this->lang->line('actions')?></th>
        <th><?php echo $this->lang->line('actions')?></th>	
	</tr>
	<?php      
    if(!$videos)
    {
        ?><tr><td colspan="5"><?php echo $this->lang->line('no_entries');?></td></tr><?php
    }
    foreach($videos as $video)
    {
        ?>
        <tr>
            <td>
                <div class="video" style="width:200px">
                    <?php
                    if($video['filename'])
                    {
                        ?>                        
                        <video controls autoplay muted width="100%">
                            <source src="<?php echo base_url()?>uploads/articles/videos/<?php echo $video['filename']?>?<?php echo uniqid('')?>" type="video/mp4">                            
                            Your browser does not support the video tag.
                        </video>                                                                         
                        <?php
                    }
                    if($video['video'])
                    {
                        ?>
                        <div class="video">
                            <?php echo $video["video"]?>
                        </div>
                        <?php
                    }
                    ?> 
                </div>
			</td>
            <td>
                <input type="text" value="<?php echo $video["order"]?>" onblur="window.location='<?php echo admin_url()?>articles/videos_change/<?php echo $video["video_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>            
            <td>
				<?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."articles/videos_change/".$video["video_id"]	
                                );
                if($this->admin_access['videos'])	
                {                    								
                    ?>
                    <span class="hide"><?php echo json_encode($aux)?></span>
                    <a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($video[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <?php echo ($video[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
                    </a> 					
                    <?php
                }
                else
                {
                    echo ($video[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1]);
                }
                ?>
            </td>   
            <td>	
				<?php
                //actions
				if($this->admin_access["videos"])	
                {
                    ?>
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>articles/videos_delete/<?php echo $video["video_id"] ?>'" class="delete">
                        <?php echo $this->lang->line("delete"); ?>
                    </a>
                    <?php
                }
                ?>
            </td>            
            <td>  
            	<input type="checkbox" name="item[]" value="<?php echo $video["video_id"]?>" />
				<?php echo $this->lang->line("delete"); ?>
            </td>    
        </tr>
        <?php
    }
    ?>
</table>
<?php
//delete all
if($videos)	
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