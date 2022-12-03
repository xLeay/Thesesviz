<?php

include "includes/classes/these.class.php";


// $classes = glob(dirname(__FILE__) . '*.php');

// foreach ($classes as $class) {
//     include_once $class;
// }

// $cnx = new Search();
// $conn = $cnx->getConn();

debug($_POST);

// $json = file_get_contents(ROOT . '../includes/extract_theses.json');
// $data = json_decode($json, true);

// $i = 0;
// foreach ($data as $key => $value) {
//     if ($i < 1) {
//         debug($value);
//         $i++;
//     }
// }

if (isset($_POST['buttomImport'])) {

    $json = file_get_contents(ROOT . '/includes/extract_theses.json');
    $data = json_decode($json, true);
    $i = 0;

    $time_start = microtime(true);

    foreach ($data as $these) {

        // INSERTION NUMERO UNE (THESE) ------------------------------------------------------------------------

        $accessible = ($these['accessible'] == "non") ? 0 : 1;
        $embargo = $these['embargo'];
        $nnt = $these['nnt'];
        $oai_set_specs = $these['oai_set_specs'];
        $resume = $these['resumes'];
        isset($these['resumes']['fr']) ? $resume = $these['resumes']['fr'] : $resume = NULL;
        $soutenue = ($these['status'] == "non") ? 0 : 1;
        $sur_travaux = ($these['these_sur_travaux'] == "non") ? 0 : 1;
        $titre = end($these['titres']);
        $discipline = $these['discipline']['fr'];

        // $sql = "INSERT INTO these (these_accessible, embargo, nnt, oai_set_specs, resume, soutenue, sur_travaux, titre, discipline) VALUES (:these_accessible, :embargo, :nnt, :oai_set_specs, :resume, :soutenue, :sur_travaux, :titre, :discipline)";
        // $insertion = $conn->prepare($sql);
        // $insertion->bindParam(':these_accessible', $accessible);
        // $insertion->bindParam(':embargo', $embargo);
        // $insertion->bindParam(':nnt', $nnt);
        // foreach ($oai_set_specs as $oai_set_spec) {
        //     $insertion->bindParam(':oai_set_specs', $oai_set_spec);
        // }
        // $insertion->bindParam(':resume', $resume);
        // $insertion->bindParam(':soutenue', $soutenue);
        // $insertion->bindParam(':sur_travaux', $sur_travaux);
        // $insertion->bindParam(':titre', $titre);
        // $insertion->bindParam(':discipline', $discipline);

        // $insertion->execute();
        // $idThese = $conn->lastInsertId();

        $thesecl = new These();

        // debug($thesecl);
        
        // $thesecl->getTheses();
        $thesecl->setAccessible($accessible);
        $thesecl->setEmbargo($embargo);
        $thesecl->setNnt($nnt);
        $thesecl->setOaiSetSpecs($oai_set_specs[0]);
        $thesecl->setResume($resume);
        $thesecl->setSoutenue($soutenue);
        $thesecl->setSurTravaux($sur_travaux);
        $thesecl->setTitre($titre);
        $thesecl->setDiscipline($discipline);


        $thesecl->insertIntoDB();

        // break;






        // // INSERTION NUMERO DEUX (ÉTABLISSEMENT) ------------------------------------------------------------------------

        // $etablissements = $these['etablissements_soutenance'];
        // foreach ($etablissements as $etablissement) {
        //     $idref_etablissement = $etablissement['idref'];
        //     $nom_etablissement = $etablissement['nom'];
        // }

        // $sql2_0 = "SELECT idref FROM etablissement WHERE idref = :idref";
        // $sql2 = "INSERT INTO etablissement (idref, nom) VALUES (:idref, :nom)";
        // $selection2 = $conn->prepare($sql2_0);
        // $insertion2 = $conn->prepare($sql2);

        // $selection = $selection2->bindParam(':idref', $idref_etablissement);
        // $selection = $selection2->execute();
        // $selected = $selection2->fetch();

        // if (!$selected) {

        //     $insertion2->bindParam(':idref', $idref_etablissement);
        //     $insertion2->bindParam(':nom', $nom_etablissement);

        //     $insertion2->execute();
        //     $idEtablissement = $conn->lastInsertId();
        // }


        // // INSERTION NUMERO TROIS (SOUTENIR)

        // $date_soutenance = $these['date_soutenance'];

        // $sql3 = "INSERT INTO soutenir (idEtablissement, idThese, date_soutenance) VALUES (:idEtablissement, :idThese, :date_soutenance)";

        // $insertion3 = $conn->prepare($sql3);
        // $insertion3->bindParam(':idEtablissement', $idEtablissement);
        // $insertion3->bindParam(':idThese', $idThese);
        // $insertion3->bindParam(':date_soutenance', $date_soutenance);

        // $insertion3->execute();



        // // INSERTION NUMERO QUATRE (SUJETS) ------------------------------------------------------------------------

        // $idSujet = NULL;

        // $libelle_sujet = $these['sujets'];
        // isset($these['sujets']['fr']) ? $libelle_sujet = $these['sujets']['fr'] : $libelle_sujet = NULL;

        // $sql4_0 = "SELECT * FROM sujet WHERE libelle = :libelle_sujet";
        // $sql4 = "INSERT INTO sujet (libelle) VALUES (:libelle_sujet)";
        // $selection4 = $conn->prepare($sql4_0);
        // $insertion4 = $conn->prepare($sql4);

        // $sql5 = "INSERT INTO `reposer` (idSujet, idThese) VALUES (:idSujet, :idThese)";

        // if ($libelle_sujet != NULL) {
        //     foreach ($libelle_sujet as $libelle) {

        //         $selection = $selection4->bindParam(':libelle_sujet', $libelle);
        //         $selection = $selection4->execute();
        //         $selected = $selection4->fetch();

        //         if (!$selected) {
        //             $insertion4->bindParam(':libelle_sujet', $libelle);
        //             $insertion4->execute();

        //             $idSujet = $conn->lastInsertId();


        //             // INSERTION NUMERO CINQ (REPOSER) ------------------------------------------------------------------------

        //             $insertion5 = $conn->prepare($sql5);
        //             $insertion5->bindParam(':idSujet', $idSujet);
        //             $insertion5->bindParam(':idThese', $idThese);

        //             $insertion5->execute();
        //         }
        //     }
        // }


        // // INSERTION NUMERO SIX (PERSONNE) ET NUMERO SEPT (ASSISTER) ------------------------------------------------------------------------

        // $personnes = array();
        // $personnes[] = $these['directeurs_these'];
        // $personnes[] = $these['president_jury'];
        // $personnes[] = $these['membres_jury'];
        // $personnes[] = $these['rapporteurs'];
        // $personnes[] = $these['auteurs'];

        // $role = array();
        // $role[] = 'directeur de these';
        // $role[] = 'president de jury';
        // $role[] = 'membre de jury';
        // $role[] = 'rapporteur';
        // $role[] = 'auteur de la these';

        // $roleN = 0;
        // $identiteN = 0;
        // $idid = array();

        // $sql6_0 = "SELECT * FROM personne WHERE nom = :nom AND prenom = :prenom";
        // $sql6 = "INSERT INTO personne (nom, prenom, idref) VALUES (:nom, :prenom, :idref)";
        // $sql7 = "INSERT INTO assister (idPersonne, idThese, role) VALUES (:idPersonne, :idThese, :role)";
        // $selection6 = $conn->prepare($sql6_0);
        // $insertion6 = $conn->prepare($sql6);
        // $insertion7 = $conn->prepare($sql7);

        // foreach ($personnes as $personne) {
        //     if ($personne != NULL && isset($personne)) {
        //         foreach ($personne as $key => $value) {
        //             if (gettype($key) == 'string') {
        //                 if ($key == 'idref') {
        //                     foreach ($idid as $key => $id2) {
        //                         if (gettype($id2) == 'array') {

        //                             $selection = $selection6->bindParam(':nom', $id2['nom']);
        //                             $selection = $selection6->bindParam(':prenom', $id2['prenom']);
        //                             $selection = $selection6->execute();
        //                             $selected = $selection6->fetch();

        //                             if (!$selected) {

        //                                 $insertion6->bindParam(':nom', $id2['nom']);
        //                                 $insertion6->bindParam(':prenom', $id2['prenom']);
        //                                 $insertion6->bindParam(':idref', $id2['idref']);

        //                                 $insertion6->execute();
        //                                 $idPersonne = $conn->lastInsertId();

        //                                 $insertion7->bindParam(':idPersonne', $idPersonne);
        //                                 $insertion7->bindParam(':idThese', $idThese);
        //                                 $insertion7->bindParam(':role', $role[$roleN]);

        //                                 $insertion7->execute();
        //                             }
        //                         }
        //                     }
        //                 }
        //             } else if (gettype($key) == 'integer') {

        //                 $selection = $selection6->bindParam(':nom', $value['nom']);
        //                 $selection = $selection6->bindParam(':prenom', $value['prenom']);
        //                 $selection = $selection6->execute();
        //                 $selected = $selection6->fetch();

        //                 if (!$selected) {
        //                     $insertion6->bindParam(':nom', $value['nom']);
        //                     $insertion6->bindParam(':prenom', $value['prenom']);
        //                     $insertion6->bindParam(':idref', $value['idref']);

        //                     $insertion6->execute();
        //                     $idPersonne = $conn->lastInsertId();

        //                     $insertion7->bindParam(':idPersonne', $idPersonne);
        //                     $insertion7->bindParam(':idThese', $idThese);
        //                     $insertion7->bindParam(':role', $role[$roleN]);

        //                     $insertion7->execute();
        //                 }
        //             }
        //             $identiteN_tampon = $identiteN;
        //             $identiteN++;
        //             $idid[] = $value;
        //         }
        //     }
        //     $roleN++;
        // }

        // // $i++;
        // // if ($i == 4) {
        // //     break;
        // // }
        // // break;
    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "$time secondes\n";
}
