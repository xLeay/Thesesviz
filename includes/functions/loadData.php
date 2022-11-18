<?php

require_once 'app.php';
require_once '/Laragon/www/Thesesviz/includes/auth/conf.php';


// seléction des thèses répertoriées
$sqlrepertorie = "SELECT idThese FROM these";
$selection1 = $conn->prepare($sqlrepertorie);
$selection1->execute();

// sélection des thèses en ligne
$sqlenligne = "SELECT these_accessible FROM these WHERE these_accessible = 1";
$selection2 = $conn->prepare($sqlenligne);
$selection2->execute();

// sélection des établissements concernés
$sqletablissement = "SELECT idref FROM etablissement";
$selection3 = $conn->prepare($sqletablissement);
$selection3->execute();

// sélection des sujets de thèses
$sqlsujet = "SELECT idSujet FROM sujet";
$selection4 = $conn->prepare($sqlsujet);
$selection4->execute();

// sélection des années de soutenance
$sqlannee = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(*) as count FROM soutenir GROUP BY DATE_FORMAT(date_soutenance, '%Y')";
$selection5 = $conn->prepare($sqlannee);
$selection5->execute();
$annees = $selection5->fetchALL(PDO::FETCH_ASSOC);


// sélection des 20 dernières thèses ajoutées
$sql20dernieres = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese
FROM etablissement e, these t NATURAL JOIN soutenir s 
WHERE s.idEtablissement = e.idEtablissement 
ORDER BY s.date_soutenance DESC LIMIT 20";
$conn->query("SET @rank=0");
// $selection6 = $conn->query($sql20dernieres);
$selection6 = $conn->prepare($sql20dernieres);
$selection6->execute();
$dernieres = $selection6->fetchALL(PDO::FETCH_ASSOC);

// sélection des auteurs des 20 dernières thèses ajoutées
$sqlauteurs20 = "SELECT a.role, p.nom, p.prenom
FROM soutenir s 
INNER JOIN assister a ON a.idThese = s.idThese
INNER JOIN personne p ON p.idPersonne = a.idPersonne
WHERE a.role = 'auteur de la these'
ORDER BY s.date_soutenance DESC LIMIT 20;";
$selection7 = $conn->prepare($sqlauteurs20);
$selection7->execute();
$auteurs = $selection7->fetchALL(PDO::FETCH_ASSOC);

// sélection des sujets des 20 dernières thèses ajoutées à l'aide de l'idThese
$sqlsujets20 = "SELECT s.libelle, r.idThese
FROM reposer r
INNER JOIN sujet s ON s.idSujet = r.idSujet
INNER JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY date_soutenance DESC LIMIT 20) so ON so.idThese = r.idThese";
$selection8 = $conn->prepare($sqlsujets20);
$selection8->execute();
$sujets = $selection8->fetchALL(PDO::FETCH_ASSOC);


