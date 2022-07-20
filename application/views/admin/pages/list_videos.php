<?php
//SUBMENU
require_once("menu.php");

//LOAD FORM HELPER
$this->load->helper('form');
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#file').uploadifive({
		'uploadScript'    	: '<?php echo admin_url()?>pages/videos_upload/<?php echo $page['page_id']?>',				
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
            <div class="small">MP4, max 10 Mb</div>
        </td>
    </tr>
</table>

<?php
/*
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
<div class="small">Youtube (embed video)</div>
<div class="small"><pre><xmp>Exp: <iframe width="420" height="315" src="http://www.youtube.com/embed/g8lyV7LwY6k" frameborder="0" allowfullscreen></iframe></xmp></pre></div>
*/?>

<!--LISTING-->
<form action="" method="post" name="listForm" onsubmit="if(!confirm('<?php echo $this->lang->line('confirm')?>')) return false;">
<table class="list_table">
    <tr>
        <th><?php echo $this->lang->line('page_video')?></th>
        <th><?php //echo $this->lang->line('page_video_name')?></th>
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
            <td class="video" width="200">
                <?php echo $video['video']?>
                
                <video width="200" height="112" controls autoplay muted>
                    <source src="<?php echo file_url()?>uploads/pages/videos/<?php echo $video['filename']?>?<?php echo uniqid('')?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>  
            </td>
            <td>   
                <span><?php echo $this->lang->line('page_video_name')?></span>             
                
                <input type="text" name="name[<?php echo $video['video_id']?>]" value="<?php echo $video["name"]?>">
                <input type="submit" name="SaveButton[<?php echo $video['video_id']?>]" value="<?php echo $this->lang->line('save')?>">

                <?php echo form_error("name[".$video['video_id']."]")?>
            </td>
            <td>
                <input type="text" value="<?php echo $video["order"]?>" onblur="window.location='<?php echo admin_url()?>pages/videos_change/<?php echo $video["video_id"] ?>/order/'+this.value" style="width:30px;"/>
            </td>            
            <td>
				<?php
                //active				
                $aux = array(	"field" 	=> "active",
                                "labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
                                "values" 	=> array(1, 0),
                                "classes" 	=> array("access", "noaccess"),
                                "url" 		=> admin_url()."pages/videos_change/".$video["video_id"]	
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
                    <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete_file")?>')) window.location='<?php echo admin_url()?>pages/videos_delete/<?php echo $video["video_id"] ?>'" class="delete">
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