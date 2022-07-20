<p><?php echo $this->lang->line('admin_welcome')?></p>
<p><?php echo $this->lang->line('admin_continue')?></p>

<?php
if($_SESSION["admin_auth"]["admin_role"] == "webmaster")
{
    ?>
    <h2>Google Sitemap</h2>
    <a href="<?php echo admin_url()?>home/generate_google_xml_sitemap" class="go">Genereaza </a> |
    <a href="<?php echo base_url()?>sitemap.xml" target="_blank" class="go">Vezi</a>
    <?php
}
?>