<?php

include_once "db.class.php";

class Search extends DB
{
    public function loadData()
    {
        $conn = $this->cnx();

        // seléction des thèses répertoriées
        $sqlrepertorie = "SELECT idThese
        FROM these";
        $selection1 = $conn->prepare($sqlrepertorie);
        $selection1->execute();

        // sélection des thèses en ligne
        $sqlenligne = "SELECT these_accessible
        FROM these
        WHERE these_accessible = 1";
        $selection2 = $conn->prepare($sqlenligne);
        $selection2->execute();

        // sélection des établissements concernés
        $sqletablissement = "SELECT idref
        FROM etablissement";
        $selection3 = $conn->prepare($sqletablissement);
        $selection3->execute();

        // sélection des sujets de thèses
        $sqlsujet = "SELECT idSujet
        FROM sujet";
        $selection4 = $conn->prepare($sqlsujet);
        $selection4->execute();

        // sélection des années de soutenance
        $sqlannee = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(*) as count FROM soutenir GROUP BY DATE_FORMAT(date_soutenance, '%Y')";
        $selection5 = $conn->prepare($sqlannee);
        $selection5->execute();
        $annees = $selection5->fetchALL(PDO::FETCH_ASSOC);

        // sélection des directeurs de thèses des thèses répertoriées
        $sqldirecteurs = "SELECT role, idAssistance
        FROM assister
        WHERE role = 'directeur'
        GROUP BY idAssistance";
        $selection9 = $conn->prepare($sqldirecteurs);
        $selection9->execute();

        // sélection des 20 dernières thèses ajoutées
        $sql20dernieres = "SELECT @classement:=@classement+1 AS classement, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt
        FROM etablissement e, these t NATURAL JOIN soutenir s
        WHERE s.idEtablissement = e.idEtablissement 
        ORDER BY s.date_soutenance DESC LIMIT 20";
        $conn->query("SET @classement=0");
        $selection6 = $conn->prepare($sql20dernieres);
        $selection6->execute();
        $dernieres = $selection6->fetchALL(PDO::FETCH_ASSOC);

        // sélection des auteurs des 20 dernières thèses ajoutées
        $sqlauteurs20 = "SELECT a.role, p.nom, p.prenom
        FROM soutenir s 
        INNER JOIN assister a ON a.idThese = s.idThese
        INNER JOIN personne p ON p.idPersonne = a.idPersonne
        WHERE a.role = 'auteur'
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


        // mise de tout dans un array
        $data = array($selection1->rowCount(), $selection2->rowCount(), $selection3->rowCount(), $selection4->rowCount(), $selection9->rowCount(), $annees, $dernieres, $auteurs, $sujets);

        return $data;
    }

    public function getTopics()
    {
        $conn = $this->cnx();

        $sql = "SELECT s.libelle, COUNT(*)
        FROM these t, reposer a, sujet s
        WHERE t.idThese = a.idThese AND s.idSujet = a.idThese
        GROUP BY s.libelle";
        $selection = $conn->prepare($sql);
        $selection->execute();
        $topics = $selection->fetchALL(PDO::FETCH_ASSOC);

        return $topics;
    }

    public function getNbThesisByEstab()
    {
        $conn = $this->cnx();

        $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses
        FROM etablissement
        LEFT JOIN soutenir ON etablissement.idEtablissement = soutenir.idEtablissement
        GROUP BY etablissement.nom, etablissement.idref  
        ORDER BY `nb_theses` DESC";
        $selection = $conn->prepare($sql);
        $selection->execute();
        $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

        return $nbThesisByEstab;
    }

    // On spécifie les options de recherche pour la carte de la france
    public function getNbThesisByEstabByOption($option, $key)
    {
        $conn = $this->cnx();

        // Définir les colonnes à sélectionner en fonction de l'option choisie
        $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses, these.titre, MATCH (these.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM etablissement
        LEFT JOIN soutenir ON etablissement.idEtablissement = soutenir.idEtablissement
        NATURAL JOIN these
        WHERE MATCH (these.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
        GROUP BY etablissement.nom, etablissement.idref, these.titre, score_titre  
        ORDER BY `score_titre` DESC";
        $selection = $conn->prepare($sql);
        $selection->execute();
        $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

        switch ($option) {
            case 'key':
                return $nbThesisByEstab;
                break;

            case 'titre':
                return $nbThesisByEstab;
                break;

            case 'auteur':
                $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses,personne.prenom, personne.nom, assister.role
                FROM these
                NATURAL JOIN soutenir
                NATURAL JOIN assister
                NATURAL JOIN personne
                JOIN etablissement ON soutenir.idEtablissement = etablissement.idEtablissement
                WHERE assister.role = 'auteur' AND personne.prenom = :prenom AND personne.nom = :nom
                GROUP BY etablissement.nom, etablissement.idref, personne.nom, assister.role";
                $selection = $conn->prepare($sql);
                $selection->bindParam(':nom', $key[0]);
                $selection->bindParam(':prenom', $key[1]);
                $selection->execute();
                $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

                return $nbThesisByEstab;
                break;
            
            case 'sujet':
                $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses, sujet.libelle, MATCH (sujet.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_sujet
                FROM these
                NATURAL JOIN soutenir
                NATURAL JOIN reposer
                NATURAL JOIN sujet
                JOIN etablissement ON soutenir.idEtablissement = etablissement.idEtablissement WHERE MATCH (sujet.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) GROUP BY etablissement.nom, etablissement.idref, sujet.libelle ORDER BY score_sujet DESC";
                $selection = $conn->prepare($sql);
                $selection->execute();
                $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

                return $nbThesisByEstab;
                break;

            case 'depuis':
                $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses
                FROM etablissement
                LEFT JOIN soutenir ON etablissement.idEtablissement = soutenir.idEtablissement
                NATURAL JOIN these
                WHERE soutenir.date_soutenance >= CONCAT('$key', '-01-01')
                GROUP BY etablissement.nom, etablissement.idref, these.titre";
                $selection = $conn->prepare($sql);
                $selection->execute();
                $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

                return $nbThesisByEstab;
                break;

            case 'etablissement':
                $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses
                FROM etablissement
                LEFT JOIN soutenir ON etablissement.idEtablissement = soutenir.idEtablissement
                NATURAL JOIN these
                WHERE etablissement.nom = '$key'
                GROUP BY etablissement.nom, etablissement.idref, these.titre";
                $selection = $conn->prepare($sql);
                $selection->execute();
                $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

                return $nbThesisByEstab;
                break;

            case 'discipline':
                $sql = "SELECT etablissement.nom, etablissement.idref, COUNT(DISTINCT soutenir.idThese) AS nb_theses, these.discipline, MATCH (these.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_discipline
                FROM these
                NATURAL JOIN soutenir
                JOIN etablissement ON soutenir.idEtablissement = etablissement.idEtablissement
                WHERE MATCH (these.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                GROUP BY etablissement.nom, etablissement.idref, these.discipline
                ORDER BY score_discipline DESC";
                $selection = $conn->prepare($sql);
                $selection->execute();
                $nbThesisByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

                return $nbThesisByEstab;
                break;
            default:
                return $nbThesisByEstab;
                break;
        }
    }

    public function getRegionByEstab()
    {
        $conn = $this->cnx();

        $sql = "SELECT f.`Libellé`, f.`Code région`, e.`idref`
        FROM etablissement e
        LEFT JOIN fr_etab f ON f.identifiant_idref = e.idref
        ORDER BY f.Libellé DESC";
        $selection = $conn->prepare($sql);
        $selection->execute();
        $regionByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

        return $regionByEstab;
    }

    public function getEstabWikipediaPage($nom)
    {
        $conn = $this->cnx();

        $sql = "SELECT f.`Libellé`, f.`Page Wikipédia en français`, e.`idref`
            FROM etablissement e
            LEFT JOIN fr_etab f ON f.identifiant_idref = e.idref
            WHERE e.nom = :nom
            LIMIT 1";
        $selection = $conn->prepare($sql);
        $selection->bindParam(':nom', $nom);
        $selection->execute();
        $regionByEstab = $selection->fetchALL(PDO::FETCH_ASSOC);

        return $regionByEstab;
    }


    public function reduce($key)
    {
        $options = [
            'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
        ];
        foreach ($options as $option) {
            if (strpos($key, $option) !== false) {
                $key = str_replace($option, '', $key);
                $key = trim($key);
            } else {
                return $key;
            }
        }
    }


    public function decodeKey($key)
    {

        // On échappe les apostrophes pour éviter les erreurs SQL
        $key = str_replace("'", "\'", $key);

        // Si l'id est dans le GET, celà signifie qu'on a cliqué sur une thèse en particulier, et on veut afficher les informations de cette thèse
        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.discipline, t.resume, t.nnt
            FROM etablissement e, these t
            NATURAL JOIN soutenir s 
            WHERE s.idEtablissement = e.idEtablissement AND t.idThese = $id
            ORDER BY t.idThese DESC";

            $sqlpersonnes = "SELECT a.role, p.nom, p.prenom
            FROM these t
            NATURAL JOIN assister a
            NATURAL JOIN personne p
            WHERE t.idThese = $id";

            $sqlsujet = "SELECT s.libelle, r.idThese
            FROM reposer r
            NATURAL JOIN sujet s
            NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
            WHERE r.idThese = $id";

            $IDqueries = array($sqlthese, $sqlpersonnes, $sqlsujet);
            return $IDqueries;
        }

        // Recherche par défaut en regardant dans le titre
        $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt, MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM etablissement e, these t
        NATURAL JOIN soutenir s 
        WHERE s.idEtablissement = e.idEtablissement AND MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
        ORDER BY score_titre DESC";

        $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM these t
        NATURAL JOIN assister a
        NATURAL JOIN personne p
        WHERE MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur'
        ORDER BY score_titre DESC";

        $sqlsujet = "SELECT s.libelle, r.idThese, MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM reposer r
        NATURAL JOIN sujet s
        NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
        NATURAL JOIN these t
        WHERE MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE)";

        $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
        FROM soutenir s
        NATURAL JOIN these t
        WHERE MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
        GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

        $queries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);

        $options = [
            'titre:', 'auteur:', 'sujet:', 'depuis:', 'de:', 'à:'
        ];

        // on vérifie si l'utilisateur a spécifié une option de recherche
        $option = key($_GET);

        switch ($option) {
            case 'key':
                return $queries;
                break;
            case 'titre':
                return $queries;
                break;
            case 'auteur':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                JOIN assister a ON a.idThese = t.idThese
                NATURAL JOIN personne p
                WHERE s.idEtablissement = e.idEtablissement AND MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur'
                ORDER BY score_personne DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur'
                ORDER BY score_personne DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur'
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $AUTHORqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $AUTHORqueries;
                break;
            case 'sujet':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, su.libelle, t.nnt, MATCH (su.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_sujet
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                JOIN reposer r ON r.idThese = t.idThese
                NATURAL JOIN sujet su
                WHERE s.idEtablissement = e.idEtablissement AND MATCH (su.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                ORDER BY score_sujet DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (s.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_sujet
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                JOIN reposer r ON r.idThese = t.idThese
                NATURAL JOIN sujet s
                WHERE MATCH (s.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                ORDER BY score_sujet DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese, MATCH (s.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_sujet
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
                NATURAL JOIN these t
                WHERE MATCH (s.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE)";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                NATURAL JOIN reposer r
                NATURAL JOIN sujet su
                WHERE MATCH (su.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $SUBJECTqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $SUBJECTqueries;
                break;
            case 'depuis':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                WHERE s.idEtablissement = e.idEtablissement AND s.date_soutenance >= CONCAT('$key', '-01-01')
                ORDER BY s.date_soutenance DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE a.role = 'auteur' AND t.idThese IN (SELECT idThese FROM soutenir WHERE date_soutenance >= CONCAT('$key', '-01-01'))
                ORDER BY t.idThese DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
                WHERE so.date_soutenance >= CONCAT('$key', '-01-01')";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                WHERE s.date_soutenance >= CONCAT('$key', '-01-01')
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $SINCEqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $SINCEqueries;
                break;
            case 'etablissement':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                WHERE s.idEtablissement = e.idEtablissement AND e.nom LIKE '%$key%'
                ORDER BY s.date_soutenance DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE a.role = 'auteur' AND t.idThese IN (SELECT idThese FROM soutenir WHERE idEtablissement IN (SELECT idEtablissement FROM etablissement WHERE nom LIKE '%$key%'))
                ORDER BY t.idThese DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
                WHERE so.idThese IN (SELECT idThese FROM soutenir WHERE idEtablissement IN (SELECT idEtablissement FROM etablissement WHERE nom LIKE '%$key%'))";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                WHERE s.idEtablissement IN (SELECT idEtablissement FROM etablissement WHERE nom LIKE '%$key%')
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $SCHOOLqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $SCHOOLqueries;
                break;
            case 'personne':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.nnt, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                JOIN assister a ON a.idThese = t.idThese
                NATURAL JOIN personne p
                WHERE s.idEtablissement = e.idEtablissement AND MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                ORDER BY score_personne DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE t.idThese IN (
                    SELECT t.idThese
                    FROM these t
                    NATURAL JOIN assister a
                    NATURAL JOIN personne p
                    WHERE MATCH (prenom, nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                )";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $PERSONqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $PERSONqueries;
                break;
            case 'discipline':
                $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.discipline, t.nnt, MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_discipline
                FROM etablissement e, these t
                NATURAL JOIN soutenir s 
                WHERE s.idEtablissement = e.idEtablissement AND MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                ORDER BY score_discipline DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, t.discipline, MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_discipline
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur'
                ORDER BY score_discipline DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese, MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_discipline
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
                NATURAL JOIN these t
                WHERE MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE)";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                NATURAL JOIN these t
                WHERE MATCH (t.discipline) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $queries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);

            default:
                return $queries;
        }
    }

    public function exe($query)
    {
        $conn = $this->cnx();
        $selection = $conn->prepare($query);

        if (isset($_GET['id'])) {
            $selection->execute();
            $result = $selection->fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }

        $selection->execute();
        $result = $selection->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getConn()
    {
        return $this->conn;
    }
}
