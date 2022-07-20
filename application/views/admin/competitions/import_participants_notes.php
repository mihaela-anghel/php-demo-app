<?php
$this->load->helper('form');
?>

<p><?php if(isset($done_message) && $done_message) echo '<div class = "done">'.$done_message.'</div>'; ?></p>
<p><?php if(isset($error_message) && $error_message) echo '<div class = "error">'.$error_message.'</div>'; ?></p>

<?php echo  validation_errors();?>
<form action="" method="post" enctype="multipart/form-data">
    <input name="file" type="file" class="input" />
    <input name="Import" type="submit" value="Importa" class="button" onclick="$('#loading').html('<img src=<?php echo file_url()?>images/loading.gif width=20> sending...')" />
    <div id="loading"></div>
</form>

<p><strong>IMPORTANT! </strong>Fisierul trebuie sa fie in format XLS si sa contina urmatoarele <strong>5 coloane</strong> in aceasi ordine ca mai jos.
    <br><a href="<?php echo base_url()?>uploads/exemplu_import_participants_notes.xls">Click aici</a> pentru a downloada un exemplu de fisier.
</p>
<ol style="float:left">
    <li><strong>ID USER</strong></li>
    <li><strong>ID CATEGORIE</strong></li>
    <li><strong>ID CATEGORIE VARSTA</strong></li>
    <li><strong>ID PREMIU</strong></li>
    <li><strong>NOTA</strong></li>
</ol>
<p style="clear:both"><strong>IMPORTANT!</strong> Prima linie trebuie sa contina numele coloanei. Notele vor fi importate incepand cu linia a doua din fisierul CSV. Dupa import dati refresh la pagina de produse pentru a vedea notele importate.</p>