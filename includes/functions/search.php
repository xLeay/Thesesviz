<?php

require_once '/Laragon/www/Thesesviz/includes/auth/conf.php';

$keyword = htmlspecialchars($_POST['keyword']);
$options = [
    'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
];

// par défaut, on recherche dans le titre
$sql = "SELECT * FROM these WHERE titre LIKE '%$keyword%'";