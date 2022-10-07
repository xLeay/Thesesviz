<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/styles/app.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Hello World PHP</title>
</head>

<body>

    <div class="wrap">
        <span class="material-symbols-outlined planet_icon">public</span>
        <h1><?= "Hello World." ?></h1>
    </div>

    <section class="connect_sect">
        <div class="connect_wrap">


            <?php
            if (!isset($_POST['con']) || !isset($_POST['ins'])) {
                include '../includes/components/connectBefore.php';
            }

            if (isset($_POST['con'])) {
                include_once '../includes/components/connectItem.php';
                echo '<script>document.querySelector(".connect_before").remove()</script>';
                $_POST = array();
            } elseif (isset($_POST['ins'])) {
                include_once '../includes/components/InscriptItem.php';
                echo '<script>document.querySelector(".connect_before").remove()</script>';
                $_POST = array();
            }

            ?>
        </div>
    </section>
</body>

</html>