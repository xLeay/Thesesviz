<?php

require_once '/Laragon/www/Thesesviz/includes/auth/conf.php';
require_once '/Laragon/www/Thesesviz/includes/functions/decodeKey.php';

$key = htmlspecialchars($_GET['key']);
$recherche = reduce($key);

debug($key, $recherche);

// debug($_GET);

$sql = decodeKey($key);
$selection = $conn->prepare($sql);

$time_start = microtime(true); // Début du chronomètre
$selection->execute();
$theses = $selection->fetchAll(PDO::FETCH_ASSOC);
$time_end = microtime(true);
$time = $time_end - $time_start; // Fin du chronomètre
$time = round($time, 4);


debug($theses);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/styles/app.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title><?= $key ?> - Recherche de thèse</title>
</head>

<body>

    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <?php include '../components/nav.php'; ?>


    <section>
        <p class="results_nb"><?= count($theses) ?> résultats pour <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes)</p>





        <script src="/src/scripts/app.js"></script>
</body>

</html>