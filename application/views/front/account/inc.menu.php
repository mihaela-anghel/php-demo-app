<h4><?php echo $this->lang->line('user_my_account'); ?></h4>
<h5><strong><?php echo $_SESSION['auth']['name']?></strong></h5>

<?php
if(isset($_SESSION['auth']['admin_message']) && $_SESSION['auth']['admin_message'])
{
    ?>
    <div class="alert alert-info mt-2 mb-0">
        <i class="fa fa-comment-dots text-info"></i>
        <?php echo nl2br($_SESSION['auth']['admin_message'])?>
    </div>
    <?php
}
?>

<!--Account links--> 
<ul class="account-menu">  
    <li>&nbsp;</li>
    <?php    
    if(isset($current_competition))
    {
        ?>
        <li>
            <a href="<?php echo base_url().$this->default_lang_url?>account/register_to_competition" class="<?php if($this->uri->rsegment(2) == "register_to_competition") echo 'active';?>" >
                <?php echo $this->lang->line('current_competition')?><br>
                <strong><?php echo $current_competition["name"]?></strong>
            </a>
        </li>
        <?php
    }
    ?>
    <li>
        <a href="<?php echo base_url().$this->default_lang_url?>account/my_competitions" class="<?php if($this->uri->rsegment(2) == "my_competitions") echo 'active';?>" >
            <i class="fa fa-user-graduate"></i> <?php echo $this->lang->line('my_competitions')?>
        </a>
    </li>
    <li>
        <a href="<?php echo base_url().$this->default_lang_url?>account/edit_account" class="<?php if($this->uri->rsegment(2) == "edit_account") echo 'active';?>" >
            <i class="fa fa-user-edit"></i> <?php echo $this->lang->line('user_edit_account')?>
        </a>
    </li>   
    <li>
        <a href="<?php echo base_url().$this->default_lang_url?>account/change_password" class="<?php if($this->uri->rsegment(2) == "change_password") echo 'active';?>" >
            <i class="fa fa-unlock"></i> <?php echo $this->lang->line('user_change_password')?>
        </a>
    </li>
    <li>
        <a href="<?php echo base_url().$this->default_lang_url?>account/logout" class="<?php if($this->uri->rsegment(2) == "logout") echo 'active';?>" >
            <i class="fa fa-sign-out-alt"></i> <?php echo $this->lang->line('user_logout')?>
        </a>
    </li>   
</ul>