
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/styles/app.css">
    <title>Document</title>
</head>
<body>
    <p>Vous êtes bien connecté, bienvenue <?= $username ?></p>
    <p>Vous allez être redirigé vers l'acceuil de Theseviz...</p>
    <?php header("refresh:1;url=/pages/home.php"); ?>
</body>
</html>
