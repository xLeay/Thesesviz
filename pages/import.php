<?php
// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";

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