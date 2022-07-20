<?php //menu ?>
<table id="table_menu">
	<tr>
    	<td>        
            <div id="my_menu">        
                <ul class="my_menu">                   
                    <li>
                    	<a href="<?php echo admin_url(); ?>" <?php if($this->uri->segment('2') == "" || $this->uri->segment('2') == "home") echo 'class = "selected"'; ?> >
							<?php echo $this->lang->line("home")?>
                        </a>
					</li>
                    <?php 
					foreach($this->global_admin->get_menu_sections() as $key=>$section)	
                    {				
                        //if($section['admin_section_url'] == "blog_categories")					           
                        if($key==13)
						{
							?>
							</ul>
                            <ul class="my_menu">   
							<?php
						}
						?>
                        <li>
                            <a href="<?php echo admin_url().$section['admin_section_url']; ?>"  <?php if($this->uri->segment('2') == $section['admin_section_url']) echo 'class = "selected"'; ?> >
                                <?php echo $section['admin_section_name']; ?>
                            </a>
                        </li>
						<?php							
                    }			
                    ?>	
                </ul> 
            </div>            
        </td>
	</tr>  
</table>

<?php //header ?>
<table id="table_header">
	<tr>
		<td class="cms"><?php echo $this->lang->line('cms');?></td>
  		<td class="welcome"><?php echo $this->lang->line('welcome')?>, <strong><?php echo $_SESSION['admin_auth']['admin_username']; ?></strong> [<a href="<?php echo $this->config->item('admin_url')?>login/logout"><strong>Logout</strong></a>]</td>
	</tr> 
</table>
