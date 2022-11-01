<?php

require '../includes/functions/app.php';
// require '../includes/functions/script_import.php';


// $json = file_get_contents('../includes/extract_theses.json');
// $data = json_decode($json, true);

// $i = 0;
// foreach ($data as $key => $value) {
//     if ($i < 2) {
//         debug($value);
//         $i++;
//     }
// }


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/styles/app.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Thesesviz - Acceuil</title>
</head>

<body>
    <nav>
        <form action="" class="search_thesis" onclick="document.getElementById('search').focus()">
            <span class="material-symbols-rounded search_icon">search</span>
            <input type="text" name="" id="search" placeholder="Recherche de thèse par titre, année, auteur...">
            <span class="material-symbols-rounded close_icon">close</span>
        </form>
    </nav>

    <section>
        <p class="section_title">Statistiques de thèses</p>

        <div class="stats_wrap">
            <div class="stats">
                <p class="stats_title">Thèses en ligne</p>
                <p class="stats_data">5 000</p>
            </div>
            <div class="stats">
                <p class="stats_title">Thèses soutenues</p>
                <p class="stats_data">5 000</p>
            </div>
            <div class="stats">
                <p class="stats_title">Sujets de thèses</p>
                <p class="stats_data">5 000</p>
            </div>
        </div>
    </section>


    <section>
        <p class="section_title">Graphiques sur les thèses</p>

    </section>




    <script src="../src/scripts/app.js"></script>
</body>

</html>