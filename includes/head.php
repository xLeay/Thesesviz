<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($og) ? $og->title : "Acceuil" ?></title>

    <?php if (isset($og)) : ?>
        <meta property="og:title" content="<?= $og->title ?>">
        <?php if (isset($og->description)) : ?>
            <meta property="og:description" content="<?= $og->description ?>">
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($externalHeadFile) : ?>
        <?php include $externalHeadFile; ?>
    <?php endif; ?>

    <link rel="canonical" href="<?= $canonical ?>">
    <link rel="stylesheet" href="<?= $basepath ?>/src/styles/app.css?v=<?= md5_file("./src/styles/app.css") ?>">
    <?= $appendHead ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

<!-- scroll to top -->
<div class="scroll_to_top" onclick="scrollToTop()">
    <span class="material-symbols-rounded back_icon">arrow_upward</span>
</div>