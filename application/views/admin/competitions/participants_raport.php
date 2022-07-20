<h2><?php echo $competition["name"]?></h2>
<table class="list_table">
<tr>
    <th><?php echo $this->lang->line('competition_category')?></th>
    <th><?php echo $this->lang->line('competition_age_category')?></th>
    <th colspan="3">Total s-au inscris:</th>
    <th colspan="3">Au trimis proiectul:</th>
    <th colspan="3">Nu au trimis proiectul:</th>
</tr>
<?php
foreach($participants as $key=>$participant)
{
    ?>
    <tr>
        <th><?php echo $participant["category_name"]?></th>
        <th><?php echo $participant["age_category_name"]?></th>
        <td><?php echo $participant["nr_users"]?> <?php echo $this->lang->line('competition_participants'); ?></td>
        <td><?php echo $participant["nr_schools"]?> <?php echo $this->lang->line('competition_schools'); ?></td>
        <td><?php echo $participant["nr_countries"]?> <?php echo $this->lang->line('competition_countries'); ?></td>
        <th><?php if(isset($participants_with_project[$key]["nr_users"])) echo $participants_with_project[$key]["nr_users"]; else echo "0";?> <?php echo $this->lang->line('competition_participants'); ?></th>
        <th><?php if(isset($participants_with_project[$key]["nr_schools"])) echo $participants_with_project[$key]["nr_schools"]; else echo "0";?> <?php echo $this->lang->line('competition_schools'); ?></th>
        <th><?php if(isset($participants_with_project[$key]["nr_countries"])) echo $participants_with_project[$key]["nr_countries"]; else echo "0";?> <?php echo $this->lang->line('competition_countries'); ?></th>
        <td><?php if(isset($participants_without_project[$key]["nr_users"])) echo $participants_without_project[$key]["nr_users"]; else echo "0";?> <?php echo $this->lang->line('competition_participants'); ?></td>
        <td><?php if(isset($participants_without_project[$key]["nr_schools"])) echo $participants_without_project[$key]["nr_schools"]; else echo "0";?> <?php echo $this->lang->line('competition_schools'); ?></td>
        <td><?php if(isset($participants_without_project[$key]["nr_countries"])) echo $participants_without_project[$key]["nr_countries"]; else echo "0";?> <?php echo $this->lang->line('competition_countries'); ?></td>
    </tr>
    <?php
}
?>
</table>