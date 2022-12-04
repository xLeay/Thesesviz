<?php

?>


<form method="post" enctype="multipart/form-data">
    <button class="btn smallbtn" type="submit" value="Importer les données" name="buttomImport">Importer les thèses</button>
</form>


<!-- 
<?php
$json = file_get_contents(ROOT . '/includes/extract_theses.json');
$data = json_decode($json, true);
$i = 0;
foreach ($data as $key => $value) {
    if ($i < 1) {
        debug($value);
        $i++;
    }
}
?> -->