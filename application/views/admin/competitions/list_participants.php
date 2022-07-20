<?php
//SUBMENU
require_once("menu.php");
?>

<!--SUBMENU-->
<p>
    <a href="<?php echo admin_url()?>competitions/participants/<?php echo $competition["competition_id"]?>/export" class="go">
        <?php echo $this->lang->line('competition_export'); ?>
    </a>

    <a href="<?php echo admin_url()?>competitions/send_email_to_all_participants/<?php echo $competition["competition_id"]?>" class="go fancybox_iframe" rel="1000,900">
        <?php echo $this->lang->line('participant_send_email_to_all_participants')?>
    </a>  

    <a href="<?php echo admin_url()?>competitions/participants_raport/<?php echo $competition["competition_id"]?>" class="go fancybox_iframe" rel="1000,900">
        <?php echo $this->lang->line('participant_raport')?>
    </a> 

    <?php
    if($participants)
    {
        ?>
        <a href="<?php echo admin_url()?>competitions/import_participants_notes/<?php echo $competition["competition_id"]?>" class="fancybox_iframe go" rel="600,400">Importa notele</a>        
        <?php
    }
    ?>

    <a href="javascript:void(0)" onclick="$('#search').slideToggle('fast');" class="go">
        <?php echo $this->lang->line('search'); ?>
    </a> 
    
</p>

<!--SEARCH FORM-->
<div id="search" style="display:<?php if(isset($_SESSION[$section_name]['search_by'])) echo 'block'; else echo 'none';?>">
<form action="" method="post">
<table>	
	<?php
    $i = 5;
    foreach($search_by as $key=>$search)
    {
        if($key%$i == 0) { ?><tr><?php } 	
        ?>	
        <th>
            <?php echo $search['field_label']; ?>:
        </th>
        <td>
            <?php		
            //input
            if($search['field_type'] == 'input')
            {		
                ?>
                <input type="text" name="<?php echo $search['field_name']?>" value="<?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']])) echo $_SESSION[$section_name]['search_by'][$search['field_name']]?>"/>
                <?php
				if(substr_count($search['field_name'], 'date'))
				{
					?>
					<script type="text/javascript" language="javascript">
						$(document).ready(function(){
							$('input[name="<?php echo $search['field_name'];?>"]').attachDatepicker({ rangeSelect: false, firstDay: 1, dateFormat: 'yy-mm-dd' }); 
						});
					</script> 
					<?php
				}	
            }
            
            //select
            if($search['field_type'] == 'select')
            {				
                ?>
                <select name="<?php echo $search['field_name']?>">
                    <option value="">&nbsp;</option>
                    <?php
                    foreach($search['field_values'] as $option_value => $option_label)
                    {
                        $aux 			= explode("-",$option_value);
						$option_value 	= $aux[0];
						$level			= (isset($aux[1])?$aux[1]:0);
						?><option value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && $option_value == $_SESSION[$section_name]['search_by'][$search['field_name']]) echo 'selected="selected"';?>><?php echo str_repeat("&nbsp;",($level*6))?><?php echo $option_label?></option><?php
                    }
                    ?>
                </select>
                <?php
            }
            
            //checkbox
            if($search['field_type'] == 'checkbox')
            {										
                foreach($search['field_values'] as $option_value => $option_label)
                {
                    ?><input type="checkbox" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked="checked"';?> /><?php echo $option_label?><?php			
                }		
            }	
            
            //radio
            if($search['field_type'] == 'radio')
            {				
                foreach($search['field_values'] as $option_value => $option_label)
                {
                    ?><input type="radio" name="<?php echo $search['field_name']?>[]" value="<?php echo $option_value?>" <?php if(isset($_SESSION[$section_name]['search_by'][$search['field_name']]) && !empty($_SESSION[$section_name]['search_by'][$search['field_name']]) && in_array($option_value,$_SESSION[$section_name]['search_by'][$search['field_name']])) echo 'checked="checked"';?> /><?php echo $option_label?><?php
                }		
            }	
            ?>
        </td>
        <?php
		if($key+1 == count($search_by)) 
		{
			for($j=1; $j<=($i-1-($key%$i)); $j++) 
			{	
				if($j < ($i-1-($key%$i)))
				{
					if(count($search_by) > $i)
					{
						?><th></th><td></td><?php 
					}
				}
				else
				{
					?>
                    <th></th>
                    <td>
                    	<input type="submit" name="Search" value="<?php echo $this->lang->line('search')?>"/>
						<input type="submit" name="Reset"  value="<?php echo $this->lang->line('cancel')?>"/>
                    </td>
					<?php
				}
			}			
			?></tr><?php
		}
		else if(($key+1)%$i == 0) 
		{ 
			echo "</tr>";			
		}	
		if(($key+1)%$i == 0 && !isset($search_by[$key+1]))
		{
			?>
			<tr>
				<td colspan="<?php echo ($i*2)?>" align="right">
					<input type="submit" name="Search" value="<?php echo $this->lang->line('search')?>"/>
					<input type="submit" name="Reset"  value="<?php echo $this->lang->line('cancel')?>"/>
				</td>
			</tr>
			<?php
		}	       
    }
    ?>  
</table>
</form>
</div>

<?php 
if(isset($_SESSION['done_message']))  		{ ?><p class="done"><?php echo $_SESSION['done_message'] ?></p><?php unset($_SESSION['done_message']); }
if(isset($_SESSION['error_message'])) 		{ ?><p class="error"><?php echo $_SESSION['error_message'] ?></p><?php unset($_SESSION['error_message']); } 
?>

<div style="background:#efefef; padding:10px; color:#E77918">
    <strong>Raport: </strong>

    <strong style="margin-left:30px;">Total s-au inscris:</strong>
    <small>
        <?php echo $competition["participants_number_all"]?> <?php echo $this->lang->line('competition_participants'); ?> / 
        <?php echo $competition["schools_number_all"]?> <?php echo $this->lang->line('competition_schools'); ?> / 
        <?php echo $competition["countries_number_all"]?> <?php echo $this->lang->line('competition_countries'); ?>
    </small>

    <strong style="margin-left:30px;">Au trimis proiectul:</strong>
    <small>
        <?php echo $competition["participants_number"]?> <?php echo $this->lang->line('competition_participants'); ?> / 
        <?php echo $competition["schools_number"]?> <?php echo $this->lang->line('competition_schools'); ?> / 
        <?php echo $competition["countries_number"]?> <?php echo $this->lang->line('competition_countries'); ?>
    </small>
    
    <strong style="margin-left:30px;">Nu au trimis proiectul:</strong>
    <small>
        <?php echo $competition["participants_number_registered"]?> <?php echo $this->lang->line('competition_participants'); ?> / 
        <?php echo $competition["schools_number_registered"]?> <?php echo $this->lang->line('competition_schools'); ?> / 
        <?php echo $competition["countries_number_registered"]?> <?php echo $this->lang->line('competition_countries'); ?>
    </small>
</div>

<!--Listing-->
<form action="" method="post" name="listForm">
<p class="small" style="float:left">ATENTIE! Generati diplomele dupa ce completati si salvati notele si premiile. Daca intervin modificari asupra notelor sau premiilor si ati generat deja diplomele, va trebui sa le generati din nou pentru a le actualiza.</p>
<?php
if($participants && !(isset($_SESSION[$section_name]['search_by'])))
{                   
    ?>
    <p style="float:right"><input type="submit" name="DeleteAllParticipants" value="Elimina toti participantii" onclick="if(!confirm('<?php echo $this->lang->line('confirm'); ?>')) return false;" style="background:#cc0000"></p>
    <?php                  
}
?>
<table class="list_table">
<tr>	
    <th><?php echo $this->lang->line('user_name')?></th>    	
    <th><?php echo $this->lang->line('user_email')?></th> 
    <th><?php echo $this->lang->line('user_birthday')?></th>   	
    <th><?php echo $this->lang->line('user_address')?></th>
    <th><?php echo $this->lang->line('user_school')?></th>
    <th><?php echo $this->lang->line('competition_category')?></th>
    <th><?php echo $this->lang->line('competition_age_category')?></th>
    <th><?php echo $this->lang->line('participant_registration_date')?></th>
    <th><?php echo $this->lang->line('participant_project')?></th>
    <th colspan="2">
        <?php        
        if($participants)
        {
            ?>
            <input type="submit" name="Save" value="<?php echo $this->lang->line('save')?>" style="background:#E77918">            
            <?php
        }
        if($participants && !(isset($_SESSION[$section_name]['search_by'])))
        {
            ?>
            <input type="submit" name="GenereazaDiplomele" value="Genereaza diplomele" style="background:#007CC2" onclick="$('#loading').html('<img src=<?php echo file_url()?>images/loading.gif width=20> sending...')">            
            <div id="loading"></div>
            <?php
        }
        ?>
    </th> 
    <th>
        <?php
        if($participants)
        {           
            //download all dimplomas
            $has_diplomas = 0;
            foreach($participants as $participant)
                if($participant["diploma"])
                    $has_diplomas++;

            if($has_diplomas)
            {
                ?>
                <input type="submit" name="DownloadZip" value="Download ZIP" style="background:#000000">
                <?php 
            }                             
        }
        ?>
    </th>    
</tr>
<?php  
if(!$participants)
{
	?><tr><td colspan="12"><?php echo $this->lang->line('no_entries');?></td></tr><?php
}

$this->load->helper('date');
$this->load->helper("form"); 

$category_name      = "";
$age_category_name  = "";
foreach($participants as $participant)
{
    if($category_name != $participant['category_name'] || $age_category_name != $participant['age_category_name'])
    {
        $category_name      = $participant['category_name'];
        $age_category_name  = $participant['age_category_name'];
        ?>
        <tr>
            <td colspan="12" style="background-color:#efefef">
                <strong><?php echo $category_name?>, <?php echo $age_category_name?></strong>
            </td>
        </tr>
        <?php
    }        
    ?>
	<tr>	
        <td>
            <?php echo $participant['name'];?>
        </td>
        <td>
            <?php echo $participant['email'];?>
            <div><?php echo $this->lang->line('user_phone')?>: <?php echo $participant['phone'];?></div>
            <div>User ID: <?php echo $participant['user_id'];?></div>
        </td>
        <td>
            <?php //echo $this->lang->line('user_birthday')?>
            <?php echo custom_date($participant['birthday'], $this->admin_default_lang);?>
        </td>
        <td>
            <?php echo $participant['city'];?><br>
            <?php echo $participant['country_name'];?>
        </td>
        <td>
            <?php echo $participant['school'];?><br> 
            <?php echo $this->lang->line('user_guide')?> <?php echo $participant['guide'];?>
        </td>
        <td>
            <?php echo $participant['category_name'];?>
        </td>
        <td>
            <?php echo $participant['age_category_name'];?>
        </td>         
        <td>
            <?php echo $this->lang->line('participant_registration_date')?> <?php echo custom_date($participant['registration_date'], $this->admin_default_lang);?>
        </td>    
        <td>
            <?php
            if($participant["project_link_extern"] || $participant["project_filename"])
            {
                if($participant["project_link_extern"])
                {
                    ?>
                    <div>
                        <strong><?php echo $this->lang->line("participant_project_link_extern")?></strong>
                        (<a href="<?php echo admin_url()?>competitions/edit_participant/<?php echo $participant['competitions_participant_id']?>" class="fancybox_iframe edit" rel="600,200"><?php echo $this->lang->line("edit")?></a>)
                    </div>
                    
                    <div><a href="<?php echo $participant['project_link_extern']?>" target="_blank"><?php echo $participant['project_link_extern']?></a></div>

                    <?php /*
                    <div>
                        <a href="javascript:void(0)" onclick="$.get('<?php echo admin_url()?>competitions/ajax_set_project_verified/<?php echo $participant['competitions_participant_id']?>', function( data ) { if(data == 'ok') window.open('<?php echo $participant['project_link_extern']?>','_blank'); window.location.href=window.location.href });">
                            <?php echo $participant['project_link_extern']?>
                        </a>
                    </div>
                    */?>
                    <?php
                }
                if($participant["project_filename"])
                {
                    $project_link = base_url()."project/".$participant['project_number'];
                    ?>
                    <div><strong><?php echo $this->lang->line("participant_project_file")?></strong></div>
                    
                    <?php /*<div><a href="<?php echo $project_link?>" onclick="if($(''))"><?php echo $project_link?></a></div>*/?>

                    <div>
                        <a href="javascript:void(0)" onclick="$.get('<?php echo admin_url()?>competitions/ajax_set_project_verified/<?php echo $participant['competitions_participant_id']?>', function( data ) { if(data == 'ok') window.open('<?php echo $project_link?>','_blank'); window.location.href=window.location.href });">
                            <?php echo $project_link?>
                        </a>
                    </div>

                    <?php
                }
                ?>
                <div><?php echo custom_date($participant['project_add_date'], $this->admin_default_lang);?></div>

                <div>
                    <?php echo $this->lang->line('participant_project_verified')?>:
                    <?php
                    //project_verified				
                    $aux = array(	"field" 	=> "project_verified",
                                    "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("", ""),
                                    "url" 		=> admin_url()."competitions/change_participant/".$participant["competitions_participant_id"]."/project_verified/".($participant["project_verified"]=="1"?"0":"1")	
                                    );
                                       								
                    ?>
                    <a href="<?php echo $aux["url"]?>" class="<?php echo ($participant[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <b style="text-transform:uppercase"><?php echo ($participant[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?></b>                    
                    </a> 					                    
                </di>
                <?php                    
            }            
            else
            {
                ?>
                <div class="error">NETRIMIS</div>
                
                <div>
                    <strong><?php echo $this->lang->line("participant_project_link_extern")?></strong>
                    (<a href="<?php echo admin_url()?>competitions/edit_participant/<?php echo $participant['competitions_participant_id']?>" class="fancybox_iframe edit" rel="600,200"><?php echo $this->lang->line("edit")?></a>)
                </div>
                <?php
            }
            ?>

            <br><br>
            <div>
                <strong><?php echo $this->lang->line("participant_comment")?></strong>
                (<a href="<?php echo admin_url()?>competitions/edit_participant_comment/<?php echo $participant['competitions_participant_id']?>" class="fancybox_iframe edit" rel="600,200"><?php echo $this->lang->line("edit")?></a>)
            </div>            
            <?php echo nl2br($participant['comment'])?>      

        </td>  
        <td style="background:#F1B26D">  
            <?php
            if($participant["project_link_extern"] || $participant["project_filename"])
            {
                ?>          
                <?php echo $this->lang->line('participant_note')?>
                <input type="text" name="note[<?php echo $participant['competitions_participant_id']?>]" value="<?php echo set_value("note[".$participant['competitions_participant_id']."]", ($participant['note']>0?$participant['note']:"") )?>" style="width:50px">
                <?php echo form_error("note[".$participant['competitions_participant_id']."]");?>

                <input type="hidden" name="participant_ids[<?php echo $participant['competitions_participant_id']?>]" value="<?php echo $participant['competitions_participant_id']?>">
                <input type="hidden" name="participant_diplomas[<?php echo $participant['competitions_participant_id']?>]" value="<?php echo $participant['diploma']?>">
                <?php
            }
            ?>
        </td>
        <td style="background:#F1B26D">
            <?php
            if($participant["project_link_extern"] || $participant["project_filename"])
            {
                ?>
                <?php echo $this->lang->line('prize_name')?>
                <select name="prize_id[<?php echo $participant['competitions_participant_id']?>]">
                <option value="0"><?php //echo $this->lang->line('select')?></option>
                <?php
                foreach($prizes as $prize)
                {
                    $selected = ($participant['prize_id'] == $prize["prize_id"]? true : false);
                    ?>
                    <option value="<?php echo $prize["prize_id"]?>" <?php echo set_select("prize_id[".$participant['competitions_participant_id']."]", $prize["prize_id"] ,$selected )?>><?php echo $prize["certificate"]?></option>
                    <?php
                }
                ?>                
                </select>
                <?php echo form_error("prize_id[".$participant['competitions_participant_id']."]");?>
                <?php
            }
            ?>
        </td>  
        <td style="background:#ffffff; white-space:nowrap">            
            <?php
            $file_name  = $participant["diploma"];
            $file_url   = file_url()."uploads/competitions/diploma/".$file_name;
            $file_path  = base_path()."uploads/competitions/diploma/".$file_name;
            if( ($participant["project_link_extern"] || $participant["project_filename"]) && $file_name && file_exists($file_path))
            {
                ?>
                <p>
                    <a href="<?php echo $file_url?>" class="go" download>
                        <strong>Download <?php if($participant["prize_id"]) echo "diplama"; else echo "certificat"?></strong>
                    </a>
                </p>                  
                <?php
            }
            ?>
            <div>                
                <a href="<?php echo admin_url()?>competitions/send_email/<?php echo $participant['competitions_participant_id']?>" class="go fancybox_iframe" rel="1000,900"><?php echo $this->lang->line('participant_send_email')?></a>
            </div>

            <div>                
                <a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line("confirm_delete")?>')) window.location='<?php echo admin_url()?>competitions/delete_participant/<?php echo $participant["competitions_participant_id"] ?>'; else return false;" class="delete">
                    <?php //echo $this->lang->line("delete"); ?>
                    Elimina din competitie
                </a>                
            </div>  

            <?php
            if($participant["prize_id"] > 0 && isset($participant["prize"]) && $participant["prize"]["type"] == "prize")
            {
                ?>
                <p>
                    AFISARE PE HOME:
                    <?php
                    //on_home				
                    $aux = array(	"field" 	=> "on_home",
                                    "labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
                                    "values" 	=> array(1, 0),
                                    "classes" 	=> array("", ""),
                                    "url" 		=> admin_url()."competitions/change_participant/".$participant["competitions_participant_id"]."/on_home/".($participant["on_home"]=="1"?"0":"1")	
                                    );
                                       								
                    ?>
                    <a href="<?php echo $aux["url"]?>" class="<?php echo ($participant[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
                        <b style="text-transform:uppercase"><?php echo ($participant[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?></b>                    
                    </a> 					                    
                </p>
                <?php
            }
            ?>    
        </td>             
	</tr>
    <?php       
}
?>
</table>
</form>