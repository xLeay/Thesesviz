<?php

// function decodeKey($key)
// {
//     $options = [
//         'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
//     ];

//     // par défaut, on recherche dans le titre
//     $sql = "SELECT * FROM these WHERE titre LIKE '%$key%'";

//     // on vérifie si l'utilisateur a spécifié une option de recherche
//     foreach ($options as $option) {
//         if (strpos($key, $option) !== false) {
//             $key = str_replace($option, '', $key);
//             $key = trim($key);

//             switch ($option) {
//                 case 'titre:':
//                     $sql = "SELECT * FROM these WHERE titre LIKE '%$key%'";
//                     break;
//                 case 'auteur:':
//                     $sql = "SELECT * FROM these WHERE auteur LIKE '%$key%'";
//                     break;
//                 case 'sujet:':
//                     $sql = "SELECT * FROM these WHERE sujet LIKE '%$key%'";
//                     break;
//                 case 'depuis:':
//                     $sql = "SELECT * FROM these WHERE annee >= '$key'";
//                     break;
//                 case 'de:':
//                     $sql = "SELECT * FROM these WHERE annee >= '$key'";
//                     break;
//                 case 'à:':
//                     $sql = "SELECT * FROM these WHERE annee <= '$key'";
//                     break;
//             }
//         }
//     }

//     return $sql;
// }

// function reduce($key)
// {
//     $options = [
//         'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
//     ];
//     foreach ($options as $option) {
//         if (strpos($key, $option) !== false) {
//             $key = str_replace($option, '', $key);
//             $key = trim($key);
//         } else {
//             return $key;
//         }
//     }
// }


// // sélection des 20 dernières thèses ajoutées
// $sql20dernieres = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese
// FROM etablissement e, these t NATURAL JOIN soutenir s 
// WHERE s.idEtablissement = e.idEtablissement 
// ORDER BY s.date_soutenance DESC LIMIT 20";
// $conn->query("SET @rank=0");
// $selection6 = $conn->prepare($sql20dernieres);
// $selection6->execute();
// $dernieres = $selection6->fetchALL(PDO::FETCH_ASSOC);

// // sélection des auteurs des 20 dernières thèses ajoutées
// $sqlauteurs20 = "SELECT a.role, p.nom, p.prenom
// FROM soutenir s 
// INNER JOIN assister a ON a.idThese = s.idThese
// INNER JOIN personne p ON p.idPersonne = a.idPersonne
// WHERE a.role = 'auteur'
// ORDER BY s.date_soutenance DESC LIMIT 20";
// $selection7 = $conn->prepare($sqlauteurs20);
// $selection7->execute();
// $auteurs = $selection7->fetchALL(PDO::FETCH_ASSOC);

// // sélection des sujets des 20 dernières thèses ajoutées à l'aide de l'idThese
// $sqlsujets20 = "SELECT s.libelle, r.idThese
// FROM reposer r
// INNER JOIN sujet s ON s.idSujet = r.idSujet
// INNER JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY date_soutenance DESC LIMIT 20) so ON so.idThese = r.idThese";
// $selection8 = $conn->prepare($sqlsujets20);
// $selection8->execute();
// $sujets = $selection8->fetchALL(PDO::FETCH_ASSOC);


// $sqlannee = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(*) as count FROM soutenir GROUP BY DATE_FORMAT(date_soutenance, '%Y')";
// $selection5 = $conn->prepare($sqlannee);
// $selection5->execute();
// $annees = $selection5->fetchALL(PDO::FETCH_ASSOC);



// function decodeKey($key)
// {
//     $options = [
//         'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
//     ];

//     $sqlthese = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese
//     FROM etablissement e, these t NATURAL JOIN soutenir s 
//     WHERE s.idEtablissement = e.idEtablissement AND t.titre LIKE '%$key%'
//     ORDER BY t.idThese DESC";

//     $sqlauteur = "SELECT a.role, p.nom, p.prenom, s.idThese
//     FROM soutenir s
//     INNER JOIN assister a ON a.idThese = s.idThese
//     INNER JOIN personne p ON p.idPersonne = a.idPersonne
//     WHERE a.role = 'auteur'
//     ORDER BY s.idThese DESC";

//     $sqlsujet = "SELECT s.libelle, r.idThese
//     FROM reposer r
//     INNER JOIN sujet s ON s.idSujet = r.idSujet
//     INNER JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so ON so.idThese = r.idThese";

//     $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
//     FROM soutenir s
//     INNER JOIN these t ON t.idThese = s.idThese
//     WHERE t.titre LIKE '%$key%'
//     GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

//     $queries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);




//     // on vérifie si l'utilisateur a spécifié une option de recherche
//     foreach ($options as $option) {
//         if (strpos($key, $option) !== false) {
//             $key = str_replace($option, '', $key);

//             debug($key, $option . 'key');

//             $key = trim($key);

//             debug($key);

//             switch ($option) {
//                 case 'titre:':
//                     return $queries;
//                     break;
//                 case 'auteur:':
//                     // TODO : requête pour les auteurs
//             }
//         }
//     }

//     return $queries;
// }
