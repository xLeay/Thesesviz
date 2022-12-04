<?php

include_once "includes/classes/db.class.php";
include "includes/classes/assist.class.php";
include "includes/classes/rest.class.php";
include "includes/classes/establishment.class.php";
include "includes/classes/person.class.php";
include "includes/classes/subject.class.php";
include "includes/classes/support.class.php";
include "includes/classes/these.class.php";

$db = new DB();
$conn = $db->cnx();

if (isset($_POST['buttomImport---'])) { // enlever les --- pour activer le script

    $json = file_get_contents(ROOT . '/includes/extract_theses.json');
    $data = json_decode($json, true);
    $i = 0;

    $time_start = microtime(true);

    foreach ($data as $these) {

        // ========================================================================
        // INSERTION NUMERO UNE (THESE)
        // ========================================================================

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
        $code_etab = $these['code_etab'];

        $thesecl = new These();

        $thesecl->setAccessible($accessible);
        $thesecl->setEmbargo($embargo);
        $thesecl->setNnt($nnt);
        $thesecl->setOaiSetSpecs($oai_set_specs[0]);
        $thesecl->setResume($resume);
        $thesecl->setSoutenue($soutenue);
        $thesecl->setSurTravaux($sur_travaux);
        $thesecl->setTitre($titre);
        $thesecl->setDiscipline($discipline);
        $thesecl->setCodeEtab($code_etab);


        $thesecl->insertIntoDB($conn)->execute();
        $idThese = $conn->lastInsertId();



        // ========================================================================
        // INSERTION NUMERO DEUX (ÉTABLISSEMENT)
        // ========================================================================

        $etablissements = $these['etablissements_soutenance'];
        foreach ($etablissements as $etablissement) {
            $idref_etablissement = $etablissement['idref'];
            $nom_etablissement = $etablissement['nom'];
        }
        $code_etab = $these['code_etab'];

        $etabcl = new Establishment();

        $etabcl->setCode_etab($code_etab);
        $etabcl->setIdref($idref_etablissement);
        $selection2 = $etabcl->prepSelect($conn);
        $selection = $etabcl->selectFromDB($selection2)->execute();
        $selected = $selection2->fetch();

        if (!$selected) {

            $etabcl->setIdref($idref_etablissement);
            $etabcl->setNom($nom_etablissement);

            $insertion2 = $etabcl->prepInsert($conn);
            $insertion = $etabcl->insertIntoDB($insertion2)->execute();
        }



        // ========================================================================
        // INSERTION NUMERO TROIS (SOUTENIR)
        // ========================================================================

        $date_soutenance = $these['date_soutenance'];

        $supportcl = new Support();

        $supportcl->setDate_soutenance($date_soutenance);
        $selection3 = $supportcl->prepSelect($conn);
        $selection = $supportcl->selectFromDB($selection3)->execute();
        $selected = $selection3->fetch();

        $idEtablissement = $supportcl->getIdEtablissement($conn);

        if (!$selected) {

            $supportcl->setDate_soutenance($date_soutenance);

            $insertion3 = $supportcl->prepInsert($conn);
            $insertion = $supportcl->insertIntoDB($insertion3, $idThese, $idEtablissement[0])->execute();
        }



        // ========================================================================
        // INSERTION NUMERO QUATRE (SUJETS)
        // ========================================================================

        $idSujet = null;
        $libelle_sujet = $these['sujets'];
        isset($these['sujets']['fr']) ? $libelle_sujet = $these['sujets']['fr'] : $libelle_sujet = NULL;


        if ($libelle_sujet != NULL) {
            foreach ($libelle_sujet as $libelle) {

                $subjectcl = new Subject();

                $subjectcl->setLibelle($libelle);
                $selection4 = $subjectcl->prepSelect($conn);
                $selection = $subjectcl->selectFromDB($selection4)->execute();
                $selected = $selection4->fetch();

                if (!$selected) {
                    $subjectcl->setLibelle($libelle);

                    $insertion4 = $subjectcl->prepInsert($conn);
                    $insertion = $subjectcl->insertIntoDB($insertion4)->execute();

                    $idSujet = $conn->lastInsertId();



                    // ========================================================================
                    // INSERTION NUMERO CINQ (REPOSER)
                    // ========================================================================

                    $reposcl = new Rest();
                    $insertion5 = $reposcl->prepInsert($conn);
                    $insertion = $reposcl->insertIntoDB($insertion5, $idSujet, $idThese)->execute();
                }
            }
        }



        // ========================================================================
        // INSERTION NUMERO SIX (PERSONNE) ET NUMERO SEPT (ASSISTER)
        // ========================================================================

        $directeurs_these = $these['directeurs_these'];
        $president_jury = $these['president_jury'];
        $membres_jury = $these['membres_jury'];
        $rapporteurs = $these['rapporteurs'];
        $auteurs = $these['auteurs'];

        if ($directeurs_these != NULL && isset($directeurs_these)) {
            foreach ($directeurs_these as $directeur) {

                $personcl = new Person();

                $personcl->setNom($directeur['nom']);
                $personcl->setPrenom($directeur['prenom']);

                $selection6 = $personcl->prepSelect($conn);
                $selection = $personcl->selectFromDB($selection6)->execute();
                $selected = $selection6->fetch();

                if (!$selected) {
                    $personcl->setNom($directeur['nom']);
                    $personcl->setPrenom($directeur['prenom']);
                    $personcl->setIdref($directeur['idref']);

                    $insertion6 = $personcl->prepInsert($conn);
                    $insertion = $personcl->insertIntoDB($insertion6)->execute();
                    
                    $idPersonne = $conn->lastInsertId();
                    
                } else {
                    $idPersonne = $selected['idPersonne'];
                }

                // ========================================================================
                // INSERTION NUMERO SEPT (ASSISTER)
                // ========================================================================

                $assistercl = new Assist();
                $assistercl->setRole('directeur');
                $insertion7 = $assistercl->prepInsert($conn);
                $insertion = $assistercl->insertIntoDB($insertion7, $idThese, $idPersonne)->execute();
            
            }
        }

        if ($president_jury != NULL && isset($president_jury)) {

            $personcl = new Person();

            $personcl->setNom($president_jury['nom']);
            $personcl->setPrenom($president_jury['prenom']);

            $selection6 = $personcl->prepSelect($conn);
            $selection = $personcl->selectFromDB($selection6)->execute();
            $selected = $selection6->fetch();

            if (!$selected) {
                $personcl->setNom($president_jury['nom']);
                $personcl->setPrenom($president_jury['prenom']);
                $personcl->setIdref($president_jury['idref']);

                $insertion6 = $personcl->prepInsert($conn);
                $insertion = $personcl->insertIntoDB($insertion6)->execute();

                $idPersonne = $conn->lastInsertId();

            } else {
                $idPersonne = $selected['idPersonne'];
            }

            // ========================================================================
            // INSERTION NUMERO SEPT (ASSISTER)
            // ========================================================================

            $assistercl = new Assist();
            $assistercl->setRole('president');
            $insertion7 = $assistercl->prepInsert($conn);
            $insertion = $assistercl->insertIntoDB($insertion7, $idThese, $idPersonne)->execute();
        }

        if ($membres_jury != NULL && isset($membres_jury)) {
            foreach ($membres_jury as $membre) {

                $personcl = new Person();

                $personcl->setNom($membre['nom']);
                $personcl->setPrenom($membre['prenom']);

                $selection6 = $personcl->prepSelect($conn);
                $selection = $personcl->selectFromDB($selection6)->execute();
                $selected = $selection6->fetch();

                if (!$selected) {
                    $personcl->setNom($membre['nom']);
                    $personcl->setPrenom($membre['prenom']);
                    $personcl->setIdref($membre['idref']);

                    $insertion6 = $personcl->prepInsert($conn);
                    $insertion = $personcl->insertIntoDB($insertion6)->execute();

                    $idPersonne = $conn->lastInsertId();

                } else {
                    $idPersonne = $selected['idPersonne'];
                }

                // ========================================================================
                // INSERTION NUMERO SEPT (ASSISTER)
                // ========================================================================

                $assistercl = new Assist();
                $assistercl->setRole('membre');
                $insertion7 = $assistercl->prepInsert($conn);
                $insertion = $assistercl->insertIntoDB($insertion7, $idThese, $idPersonne)->execute();
            }
        }

        if ($rapporteurs != NULL && isset($rapporteurs)) {
            foreach ($rapporteurs as $rapporteur) {

                $personcl = new Person();

                $personcl->setNom($rapporteur['nom']);
                $personcl->setPrenom($rapporteur['prenom']);

                $selection6 = $personcl->prepSelect($conn);
                $selection = $personcl->selectFromDB($selection6)->execute();
                $selected = $selection6->fetch();

                if (!$selected) {
                    $personcl->setNom($rapporteur['nom']);
                    $personcl->setPrenom($rapporteur['prenom']);
                    $personcl->setIdref($rapporteur['idref']);

                    $insertion6 = $personcl->prepInsert($conn);
                    $insertion = $personcl->insertIntoDB($insertion6)->execute();

                    $idPersonne = $conn->lastInsertId();

                } else {
                    $idPersonne = $selected['idPersonne'];
                }

                // ========================================================================
                // INSERTION NUMERO SEPT (ASSISTER)
                // ========================================================================

                $assistercl = new Assist();
                $assistercl->setRole('rapporteur');
                $insertion7 = $assistercl->prepInsert($conn);
                $insertion = $assistercl->insertIntoDB($insertion7, $idThese, $idPersonne)->execute();
            }
        }

        if ($auteurs != NULL && isset($auteurs)) {
            foreach ($auteurs as $auteur) {

                $personcl = new Person();

                $personcl->setNom($auteur['nom']);
                $personcl->setPrenom($auteur['prenom']);

                $selection6 = $personcl->prepSelect($conn);
                $selection = $personcl->selectFromDB($selection6)->execute();
                $selected = $selection6->fetch();

                if (!$selected) {
                    $personcl->setNom($auteur['nom']);
                    $personcl->setPrenom($auteur['prenom']);
                    $personcl->setIdref($auteur['idref']);

                    $insertion6 = $personcl->prepInsert($conn);
                    $insertion = $personcl->insertIntoDB($insertion6)->execute();

                    $idPersonne = $conn->lastInsertId();

                } else {
                    $idPersonne = $selected['idPersonne'];
                }

                // ========================================================================
                // INSERTION NUMERO SEPT (ASSISTER)
                // ========================================================================

                $assistercl = new Assist();
                $assistercl->setRole('auteur');
                $insertion7 = $assistercl->prepInsert($conn);
                $insertion = $assistercl->insertIntoDB($insertion7, $idThese, $idPersonne)->execute();
            }
        }

        // $i++;
        // if ($i == 25) {
        //     break;
        // }


        // break;
    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "$time secondes\n";
}
