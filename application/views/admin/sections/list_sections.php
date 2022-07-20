<!--SUBMENU-->
<ul class="submenu">		
	<li>
    	<a href="<?php echo admin_url()?>sections/add_section" class="fancybox_iframe" rel="500,300">
			<?php echo $this->lang->line('add_section'); ?>
        </a>
	</li>	
</ul>

<!--LISTING-->
<table class="list_table">
	<tr>
		<th><?php echo $this->lang->line('section_name')?></th>
		<th><?php echo $this->lang->line('right_name')?></th>
		<th><?php echo $this->lang->line('section_url')?></th>
		<th><?php echo $this->lang->line('order')?></th>
        <th><?php echo $this->lang->line('display_in_menu')?></th>
		<th><?php echo $this->lang->line('status')?></th>        
		<th><?php echo $this->lang->line('actions')?></th>
	</tr>
	<?php
	if(!$sections)
	{
		?><tr><td colspan="6"><?php echo $this->lang->line('no_entries');?></td></tr><?php
	} 
	foreach($sections as $section)
	{
		?>
		<tr>
			<td>
            	<strong><?php echo $section['admin_section_name']?></strong>
			</td>
			<td>
				<table width="100%">
				<?php	
				//rights
				foreach($section['rights'] as $key=>$right)
				{		
					?>
					<tr>
						<td width="40%">
							<?php echo $right['order'].'. '.$right['admin_right_name']?>
						</td>
						<td width="30%">
							<?php echo $right['admin_right_url']?>
						</td>
						<td width="30%">
							<a href="<?php echo admin_url()?>sections/edit_right/<?php echo $right['admin_right_id'] ?>" class = "fancybox_iframe edit" rel="500,300"><?php echo $this->lang->line('edit')?></a>
							<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo admin_url()?>sections/delete_right/<?php echo $right['admin_right_id'] ?>'" class="delete"><?php echo $this->lang->line('delete')?></a>			
						</td>			
					</tr>
					<?php				
				}	
				?>
				</table>	
			</td>
			<td>
            	<a href = "<?php echo admin_url().$section['admin_section_url']; ?>">
					<?php echo $section['admin_section_url']?>
				</a>
			</td>
			<td>
            	<input type="text" value="<?php echo $section['order']?>" onblur="window.location='<?php echo admin_url()?>sections/change_section/<?php echo $section['admin_section_id'] ?>/order/'+this.value" style="width:30px;"/>
			</td>
            <td>
				<?php		
				//menu		
				$aux = array(	"field" 	=> "menu",
								"labels" 	=> array($this->lang->line("yes"), $this->lang->line("no")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> $this->config->item("admin_url")."sections/change_section/".$section["admin_section_id"]	
								);				
				?>              
				<span class="hide"><?php echo json_encode($aux)?></span>
				<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($section[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
					<?php echo ($section[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
				</a>                  			
			</td>
			<td>
				<?php		
				//active		
				$aux = array(	"field" 	=> "active",
								"labels" 	=> array($this->lang->line("active"), $this->lang->line("inactive")),
								"values" 	=> array(1, 0),
								"classes" 	=> array("access", "noaccess"),
								"url" 		=> $this->config->item("admin_url")."sections/change_section/".$section["admin_section_id"]	
								);				
				?>              
				<span class="hide"><?php echo json_encode($aux)?></span>
				<a href="javascript:void(0)" onclick="change_ajax($(this))" class="<?php echo ($section[$aux["field"]]==$aux["values"][0]?$aux["classes"][0]:$aux["classes"][1])?>"> 
					<?php echo ($section[$aux["field"]]==$aux["values"][0]?$aux["labels"][0]:$aux["labels"][1])?>                    
				</a>                              		
			</td>            
			<td>				
                <a href="<?php echo admin_url()?>sections/add_right/<?php echo $section['admin_section_id'] ?>" class="fancybox_iframe add" rel="500,300">
					<?php echo $this->lang->line('add_right'); ?>
				</a>
				<a href="<?php echo admin_url()?>sections/edit_section/<?php echo $section['admin_section_id'] ?>" class="fancybox_iframe edit" rel="500,300">
					<?php echo $this->lang->line('edit'); ?>
                </a>
				<a href="javascript:void(0)" onclick="if(confirm('<?php echo $this->lang->line('confirm_delete')?>')) window.location='<?php echo admin_url()?>sections/delete_section/<?php echo $section['admin_section_id'] ?>'" class="delete">
					<?php echo $this->lang->line('delete'); ?>
                </a>
			</td>
		</tr>
		<?php
	}
	?>
</table>
