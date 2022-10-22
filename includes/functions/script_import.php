<?php

include '/Laragon/www/Thesesviz/includes/auth/conf.php';

// $json = file_get_contents('../extract_theses.json');
// $data = json_decode($json, true);



// [
//     {
//     "these_sur_travaux": "non",
//     "date_soutenance": "2006-01-01",
//     "directeurs_these": [
//       {
//         "nom": "Meynadier",
//         "prenom": "Laure",
//         "idref": "114430489"
//       }
//     ],
//     "etablissements_soutenance": [
//       {
//         "idref": "027542084",
//         "nom": "Paris 7"
//       }
//     ],
//     "discipline": {
//       "fr": "Géochimie"
//     },
//     "oai_set_specs": [
//       "ddc:550",
//       "ddc:540"
//     ],
//     "president_jury": {},
//     "@version": "1",
//     "iddoc": 712269,
//     "nnt": "2006PA077132",
//     "ecoles_doctorales": [],
//     "embargo": null,
//     "status": "soutenue",
//     "source": "sudoc",
//     "accessible": "non",
//     "langue": "fr",
//     "@timestamp": "2022-03-01T13:04:48.489Z",
//     "code_etab": "PA07",
//     "cas": "cas5",
//     "titres": {
//       "en": "Neodymium isotopic stratigraphy in the Indian Ocean : Oceanic paleocirculation and Continental erosion",
//       "fr": "Stratigraphie isotopique de néodyme dans l'Océan Indien : Paléocirulation océanique et Erosion continentale"
//     },
//     "resumes": {
//       "fr": "La composition isotopique du Néodyme dans les sédiments marins est un outil potentiel pour étudier la circulation océanique dans le passé."
//     },
//     "sujets": {},
//     "membres_jury": [],
//     "partenaires_recherche": [],
//     "sujets_rameau": [
//       {
//         "type_vedette": "vedetteRameauGenreForme",
//         "element_entree": {
//           "contenu": null,
//           "idref": null
//         },
//         "subdivisions": []
//       },
//       {
//         "type_vedette": "vedetteRameauNomCommun",
//         "element_entree": {
//           "contenu": "Géochimie",
//           "idref": "027282694"
//         },
//         "subdivisions": []
//       },
//       {
//         "type_vedette": "vedetteRameauNomCommun",
//         "element_entree": {
//           "contenu": "Érosion",
//           "idref": "027388514"
//         },
//         "subdivisions": []
//       },
//       {
//         "type_vedette": "vedetteRameauNomCommun",
//         "element_entree": {
//           "contenu": "Néodyme",
//           "idref": "031779107"
//         },
//         "subdivisions": [
//           {
//             "type": "subdivisionGeographique",
//             "contenu": "Indien, Océan",
//             "idref": "027405478"
//           }
//         ]
//       },
//       {
//         "type_vedette": "vedetteRameauNomCommun",
//         "element_entree": {
//           "contenu": "Tectonique des plaques",
//           "idref": "027253619"
//         },
//         "subdivisions": []
//       },
//       {
//         "type_vedette": "vedetteRameauNomCommun",
//         "element_entree": {
//           "contenu": "Stratigraphie",
//           "idref": "027249565"
//         },
//         "subdivisions": []
//       }
//     ],
//     "rapporteurs": [],
//     "auteurs": [
//       {
//         "nom": "Gourlan",
//         "prenom": "Alexandra",
//         "idref": "118063383"
//       }
//     ]
//   }
// ]


// foreach ($data as $product) {
//     $stmt = $conn->prepare('insert into product(name, price, quantity) values(:name, :price, :quantity)');
//     $stmt->bindValue('name', $product->name);
//     $stmt->bindValue('price', $product->price);
//     $stmt->bindValue('quantity', $product->quantity);
//     $stmt->execute();
// }


if (isset($_POST['buttomImport'])) {

    $json = file_get_contents('/Laragon/www/Thesesviz/includes/extract_theses.json');
    $data = json_decode($json, true);

    // // ETABLISSEMENT
    // $idref_etablissement = $data['etablissements_soutenance']['idref'];
    // $nom_etablissement = $data['etablissements_soutenance']['nom'];

    // // SOUTENIR
    // $date_soutenance = $data['date_soutenance'];

    // // THESE
    // $accessible = $data['accessible'];
    // $embargo = $data['embargo'];
    // $nnt = $data['nnt'];
    // $oai_set_specs = $data['oai_set_specs'];
    // $resume = $data['resumes'];
    // $soutenue = $data['status'];
    // $sur_travaux = $data['these_sur_travaux'];
    // $titre = $data['titres'];
    // $discipline = $data['discipline'];

    // // REPOSER

    // // SUJET
    // $libellé_sujet = $data['sujets'];

    // // ASSISTER
    // // on doit trouver le role : directeur de these, president de jury, membre de jury, rapporteur, auteur de la these à partir de l'idThese et de l'idPersonne
    // $role = $data['role'];

    // // PERSONNE
    // $directeurs_theseNom = $data['directeurs_these']['nom'];
    // $directeurs_thesePrenom = $data['directeurs_these']['prenom'];
    // $directeurs_theseIdref = $data['directeurs_these']['idref'];

    // $president_juryNom = $data['president_jury']['nom'];
    // $president_juryPrenom = $data['president_jury']['prenom'];
    // $president_juryIdref = $data['president_jury']['idref'];

    // $membres_juryNom = $data['membres_jury']['nom'];
    // $membres_juryPrenom = $data['membres_jury']['prenom'];
    // $membres_juryIdref = $data['membres_jury']['idref'];

    // $rapporteursNom = $data['rapporteurs']['nom'];
    // $rapporteursPrenom = $data['rapporteurs']['prenom'];
    // $rapporteursIdref = $data['rapporteurs']['idref'];

    // $auteursNom = $data['auteurs']['nom'];
    // $auteursPrenom = $data['auteurs']['prenom'];
    // $auteursIdref = $data['auteurs']['idref'];



    $i = 0;

    foreach ($data as $these) {

        $personnes_noms = [];
        $personnes_prenoms = [];
        $personnes_idref = [];

        $directeurs_these = $these['directeurs_these'];
        foreach ($directeurs_these as $directeur_these) {
            $directeurs_theseNom = $directeur_these['nom'];
            $directeurs_thesePrenom = $directeur_these['prenom'];
            $directeurs_theseIdref = $directeur_these['idref'];

            array_push($personnes_noms, $directeurs_theseNom);
            array_push($personnes_prenoms, $directeurs_thesePrenom);
            array_push($personnes_idref, $directeurs_theseIdref);
        }

        
        $president_jury = $these['president_jury'];
        if ($president_jury != null) {
            $president_juryNom = $president_jury['nom'];
            $president_juryPrenom = $president_jury['prenom'];
            $president_juryIdref = $president_jury['idref'];

            array_push($personnes_noms, $president_juryNom);
            array_push($personnes_prenoms, $president_juryPrenom);
            array_push($personnes_idref, $president_juryIdref);
        }

        // $president_jury != null ? debug('-> DIRECTEUR ->', $directeurs_these, '-> PRESIDENT ->', $president_jury) : debug('-> DIRECTEUR ->', $directeurs_these);
        
        
        
        $membres_jury = $these['membres_jury'];
        foreach ($membres_jury as $membre) {
            $membres_juryNom = $membre['nom'];
            $membres_juryPrenom = $membre['prenom'];
            $membres_juryIdref = $membre['idref'];
            
            array_push($personnes_noms, $membres_juryNom);
            array_push($personnes_prenoms, $membres_juryPrenom);
            array_push($personnes_idref, $membres_juryIdref);
        }
        
        
        
        $rapporteurs = $these['rapporteurs'];
        foreach ($rapporteurs as $rapporteur) {
            $rapporteursNom = $rapporteur['nom'];
            $rapporteursPrenom = $rapporteur['prenom'];
            $rapporteursIdref = $rapporteur['idref'];

            array_push($personnes_noms, $rapporteursNom);
            array_push($personnes_prenoms, $rapporteursPrenom);
            array_push($personnes_idref, $rapporteursIdref);
        }
        
        $auteurs = $these['auteurs'];
        foreach ($auteurs as $auteur) {
            $auteursNom = $auteur['nom'];
            $auteursPrenom = $auteur['prenom'];
            $auteursIdref = $auteur['idref'];

            array_push($personnes_noms, $auteursNom);
            array_push($personnes_prenoms, $auteursPrenom);
            array_push($personnes_idref, $auteursIdref);
        }
        
        // debug($personnes_noms, $personnes_prenoms, $personnes_idref);

        
        // debug('------------------------------------------------------------------    ' . $i, '-> PERSONNES_NOMS ->', $personnes_noms, '-> PERSONNES_PRENOMS ->', $personnes_prenoms, '-> PERSONNES_IDREF ->', $personnes_idref);
        debug('------------------------------------------------------------------    ' . $i, '-> PERSONNES_NOMS ->', $personnes_noms);
        $i++;

        // $sql = "INSERT INTO personne (nom, prenom, idref) VALUES (:nom, :prenom, :idref)";
        // $insertion = $conn->prepare($sql);
        // // $insertion->bindParam(':nom', $directeurs_theseNom);
        // // $insertion->bindParam(':prenom', $directeurs_thesePrenom);
        // // $insertion->bindParam(':idref', $directeurs_theseIdref);
        // foreach ($personnes_noms as $personne_nom) {
        //     $insertion->bindParam(':nom', $personne_nom);
        // }
        // foreach ($personnes_prenoms as $personne_prenom) {
        //     $insertion->bindParam(':prenom', $personne_prenom);
        // }
        // foreach ($personnes_idref as $personne_idref) {
        //     $insertion->bindParam(':idref', $personne_idref);
        // }


        $sql = "INSERT INTO personne (nom) VALUES (:nom)";
        $insertion = $conn->prepare($sql);
        foreach ($personnes_noms as $personne_nom) {
            $insertion->bindParam(':nom', $personne_nom);
        }


        try {
            $insertion->execute();
            $_POST = array();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

// LA THESE

// $accessible = ($these['accessible'] == "non") ? 0 : 1;
// $embargo = $these['embargo'];
// $nnt = $these['nnt'];
// $oai_set_specs = $these['oai_set_specs'];
// $resume = $these['resumes'];
// isset($these['resumes']['fr']) ? $resume = $these['resumes']['fr'] : $resume = NULL;
// $soutenue = ($these['status'] == "non") ? 0 : 1;
// $sur_travaux = ($these['these_sur_travaux'] == "non") ? 0 : 1;
// $titre = end($these['titres']);
// $discipline = $these['discipline']['fr'];


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


// L'ÉTABLISSEMENT

// $etablissements = $these['etablissements_soutenance'];
// foreach ($etablissements as $etablissement) {
//     $idref_etablissement = $etablissement['idref'];
//     $nom_etablissement = $etablissement['nom'];
// }

// $sql = "INSERT INTO etablissement (idref, nom) VALUES (:idref, :nom)";
// $insertion = $conn->prepare($sql);
// $insertion->bindParam(':idref', $idref_etablissement);
// $insertion->bindParam(':nom', $nom_etablissement);