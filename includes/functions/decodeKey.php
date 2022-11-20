<?php

function decodeKey($key)
{
    $options = [
        'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
    ];

    // par défaut, on recherche dans le titre
    $sql = "SELECT * FROM these WHERE titre LIKE '%$key%'";

    // on vérifie si l'utilisateur a spécifié une option de recherche
    foreach ($options as $option) {
        if (strpos($key, $option) !== false) {
            $key = str_replace($option, '', $key);
            $key = trim($key);

            switch ($option) {
                case 'titre:':
                    $sql = "SELECT * FROM these WHERE titre LIKE '%$key%'";
                    break;
                case 'auteur:':
                    $sql = "SELECT * FROM these WHERE auteur LIKE '%$key%'";
                    break;
                case 'sujet:':
                    $sql = "SELECT * FROM these WHERE sujet LIKE '%$key%'";
                    break;
                case 'depuis:':
                    $sql = "SELECT * FROM these WHERE annee >= '$key'";
                    break;
                case 'de:':
                    $sql = "SELECT * FROM these WHERE annee >= '$key'";
                    break;
                case 'à:':
                    $sql = "SELECT * FROM these WHERE annee <= '$key'";
                    break;
            }
        }
    }

    return $sql;
}

function reduce($key)
{
    $options = [
        'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
    ];
    foreach ($options as $option) {
        if (strpos($key, $option) !== false) {
            $key = str_replace($option, '', $key);
            $key = trim($key);
        }
        else {
            return $key;
        }
    }
}
