<?php

require '../includes/functions/app.php';
require '../includes/functions/script_import.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theseviz - Acceuil</title>
</head>

<body>
    <h1>Tests en pagaille</h1>

    <form method="post" enctype="multipart/form-data">
        <input type="submit" value="Importer les données" name="buttomImport">
    </form>


    <?php
    $json = file_get_contents('../includes/extract_theses.json');
    $data = json_decode($json, true);

    $i = 0;
    foreach ($data as $key => $value) {
        if ($i < 4) {
            debug($value);
            $i++;
        }
    }

    ?>

    

</body>

</html>