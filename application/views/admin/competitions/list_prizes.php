<?php
//SUBMENU
require_once("menu.php");
?>

<!--SUBMENU-->
<p>
    <a href="<?php echo admin_url()?>competitions/add_prize/<?php echo $competition["competition_id"]?>" class="add fancybox_iframe" rel="1000,900">
        <?php echo $this->lang->line('add_prize'); ?>
    </a>
</p>

<!--Listing-->
<table class="list_table">
<tr>	
    <th><?php echo $sort_label['t1.prize_id'];?>Id</th>
    <th><?php echo $this->lang->line('prize_certificate')?></th>
    <th><?php echo $this->lang->line('prize_type')?></th>
    <th><?php echo $this->lang->line('prize_name')?></th>
    <th><?php echo $this->lang->line('prize_email_content')?></th>
    <!-- <th><?php echo $this->lang->line('prize_description')?></th> -->
    <th><?php echo $this->lang->line('prize_image')?></th>   
    <th><?php echo $sort_label['t1.order'];?><?php echo $this->lang->line('order')?></th>    
    <th><?php echo $this->lang->line('actions')?></th>
</tr>
<?php  
if(!$prizes)
{
	?><tr><td colspan="8" align="center"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}
//$nr=0;
foreach($prizes as $prize)
{
	?>
	<tr>	
        <td><?php echo $prize['prize_id']?></td>
        <td>
            <?php echo $prize['certificate'];?>
        </td>
        <td>
            <?php echo $this->lang->line("prize_type_".$prize['type']);?>
        </td>
        <td>
            <?php echo $prize['prize_name'];?>
        </td>
        <td>
            <?php echo $prize['email_content'];?>
        </td>
        <!-- <td>
            <?php echo $prize['prize_description'];?>
        </td> -->       	       
        <td>
            <?php
            //image	
            $image = $prize['image'];
            $impath = $this->config->item('base_path').'uploads/competitions/prizes/'.$image;
            $imurl = $this->config->item('base_url').'uploads/competitions/prizes/'.$image;
            
            if($image != '' && file_exists($impath))
            {
                ?>
                <a href="<?php echo $imurl?>" class="fancybox_image"><img src="<?php echo $imurl;?>" width="50"/><?php //echo $this->lang->line('view')?></a>
                 &raquo; <a href="#" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete_file')?>')) window.location='<?php echo $this->config->item('admin_url') ?>competitions/delete_prize_file/image/<?php echo $prize['prize_id']?>'"><?php echo $this->lang->line('delete')?></a>
                <?php
            }
            else
            {
                ?> &raquo; <a href="<?php echo $this->config->item('admin_url')?>competitions/upload_prize_file/image/<?php echo $prize['prize_id']?>" class = "fancybox_iframe" rel="600,300"><?php echo $this->lang->line('upload')?></a><?php
            }
            ?>
        </td>                        
        <td>
            <input type="text" size="2" class="input" value="<?php echo $prize['order']?>" onblur="window.location='<?php echo $this->config->item('admin_url')?>competitions/change_prize/<?php echo $prize['prize_id'] ?>/order/'+this.value" style="width:30px;"/>
        </td>			          
        <td>	
            <?php
            // actiuni
            if($this->admin_access['edit_prize'])
            {
                ?><a href="<?php echo $this->config->item('admin_url')?>competitions/edit_prize/<?php echo $prize['prize_id'] ?>" class="edit fancybox_iframe" rel="1000,900"><?php echo $this->lang->line('edit'); ?></a><?php
            }	
			if($this->admin_access['delete_prize'])
            {
                ?><a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo $this->config->item('admin_url')?>competitions/delete_prize/<?php echo $prize['prize_id'] ?>'" class="delete"><?php echo $this->lang->line('delete'); ?></a><?php                
            }
            ?>		
        </td>    
	</tr>
	<?php
}
?>
</table>