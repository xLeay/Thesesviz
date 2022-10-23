<?php

include '/Laragon/www/Thesesviz/includes/auth/conf.php';


if (isset($_POST['buttomImport'])) {

    $json = file_get_contents('/Laragon/www/Thesesviz/includes/extract_theses.json');
    $data = json_decode($json, true);
    $i = 0;

    foreach ($data as $these) {

        $time_start = microtime(true);
        // INSERTION NUMERO UNE (THESE)

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


        $sql = "INSERT INTO these (these_accessible, embargo, nnt, oai_set_specs, resume, soutenue, sur_travaux, titre, discipline) VALUES (:these_accessible, :embargo, :nnt, :oai_set_specs, :resume, :soutenue, :sur_travaux, :titre, :discipline)";
        $insertion = $conn->prepare($sql);
        $insertion->bindParam(':these_accessible', $accessible);
        $insertion->bindParam(':embargo', $embargo);
        $insertion->bindParam(':nnt', $nnt);
        foreach ($oai_set_specs as $oai_set_spec) {
            $insertion->bindParam(':oai_set_specs', $oai_set_spec);
        }
        $insertion->bindParam(':resume', $resume);
        $insertion->bindParam(':soutenue', $soutenue);
        $insertion->bindParam(':sur_travaux', $sur_travaux);
        $insertion->bindParam(':titre', $titre);
        $insertion->bindParam(':discipline', $discipline);

        $insertion->execute();

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        echo "$time secondes\n";

        
        // INSERTION NUMERO DEUX (ETABLISSEMENT)

        $etablissements = $these['etablissements_soutenance'];
        foreach ($etablissements as $etablissement) {
            $idref_etablissement = $etablissement['idref'];
            $nom_etablissement = $etablissement['nom'];
        }

        $sql2 = "INSERT INTO etablissement (idref, nom) VALUES (:idref, :nom)";
        $insertion2 = $conn->prepare($sql2);
        $insertion2->bindParam(':idref', $idref_etablissement);
        $insertion2->bindParam(':nom', $nom_etablissement);

        $insertion2->execute();


        // INSERTION NUMERO TROIS (SOUTENIR)

        $date_soutenance = $these['date_soutenance'];

        $sql3 = "INSERT INTO soutenir (idEtablissement, idThese, date_soutenance) SELECT e.idEtablissement, t.idThese, :date_soutenance FROM `etablissement` e JOIN `these` t ORDER BY t.idThese DESC, e.idEtablissement DESC LIMIT 1";

        $insertion3 = $conn->prepare($sql3);
        $insertion3->bindParam(':date_soutenance', $date_soutenance);

        $insertion3->execute();


        // INSERTION NUMERO QUATRE (SUJETS)

        $libelle_sujet = $these['sujets'];
        isset($these['sujets']['fr']) ? $libelle_sujet = $these['sujets']['fr'] : $libelle_sujet = NULL;

        $sql4 = "INSERT INTO `sujet` (libelle) SELECT :libelle_sujet WHERE NOT EXISTS (SELECT * FROM `sujet` WHERE libelle = :libelle_sujet)";
        $insertion4 = $conn->prepare($sql4);
        if ($libelle_sujet != NULL) {
            foreach ($libelle_sujet as $libelle) {
                $insertion4->bindParam(':libelle_sujet', $libelle);
                $insertion4->execute();

            }
        }
        else {
            $insertion4->bindParam(':libelle_sujet', $libelle_sujet);
            $insertion4->execute();
        }


        // INSERTION NUMERO CINQ (REPOSER)

        $sql5 = "INSERT INTO `reposer` (idSujet, idThese) SELECT s.idSujet, t.idThese FROM `sujet` s JOIN `these` t ORDER BY t.idThese DESC, s.idSujet DESC LIMIT 1";
        $insertion5 = $conn->prepare($sql5);
        $insertion5->execute();


        // INSERTION NUMERO SIX (PERSONNE) ET NUMERO SEPT (ASSISTER)

        $personnes = array();
        $personnes[] = $these['directeurs_these'];
        $personnes[] = $these['president_jury'];
        $personnes[] = $these['membres_jury'];
        $personnes[] = $these['rapporteurs'];
        $personnes[] = $these['auteurs'];

        $sql6 = "INSERT INTO personne (nom, prenom, idref) VALUES (:nom, :prenom, :idref) ON DUPLICATE KEY UPDATE nom = nom, prenom = prenom, idref = idref";
        $insertion6 = $conn->prepare($sql6);

        $sql7 = "INSERT INTO assister (idPersonne, idThese, role) SELECT p.idPersonne, t.idThese, :role FROM `personne` p JOIN `these` t ORDER BY t.idThese DESC, p.idPersonne DESC LIMIT 1 ON DUPLICATE KEY UPDATE idPersonne = p.idPersonne, idThese = t.idThese, role = :role";

        $insertion7 = $conn->prepare($sql7);

        $role = array();
        $role[] = 'directeur de these';
        $role[] = 'president de jury';
        $role[] = 'membre de jury';
        $role[] = 'rapporteur';
        $role[] = 'auteur de la these';

        $roleN = 0;

        foreach ($personnes as $personne) {
            foreach ($personne as $key => $value) {

                if (gettype($key) == 'string') {
                    $insertion6->bindParam(':' . $key, $value);
                } else if (gettype($key) == 'integer') {
                    $insertion6->bindParam(':nom', $value['nom']);
                    $insertion6->bindParam(':prenom', $value['prenom']);
                    $insertion6->bindParam(':idref', $value['idref']);
                }

                $insertion6->execute();
            }

            if ($personne != NULL) {

                $insertion7->bindParam(':role', $role[$roleN]);
                
                $insertion7->execute();
            }
            $roleN++;
        }

        $i++;
        if ($i == 1) {
            break;
        }
        // break;
    }
}
