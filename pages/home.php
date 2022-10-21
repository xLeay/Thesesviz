<?php

require '../includes/functions/app.php';

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
    <h1>dfdf</h1>
    <?php
        $json = file_get_contents('../includes/extract_theses.json');
        $data = json_decode($json, true);

        // faire les 10 premiers
        $i = 0;
        foreach ($data as $key => $value) {
            if ($i < 250) {
                debug($value);
                $i++;
            }
        }
    ?>
</body>
</html>