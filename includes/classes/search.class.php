<?php

class Search
{

    // public function __construct()
    // {
    //     parent::__construct();
    // }

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4', PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8mb4");

    protected function cnx()
    {
        try {
            $conn = new PDO("mysql:host=$this->servername;dbname=theseviz", $this->username, $this->password, $this->options);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // private $conn = parent::cnx();

    public function __construct()
    {
        $this->conn = $this->cnx();
    }

    public function select(array $cols): string
    {
        $query = "SELECT ";
        $size = count($cols);
        if ($size > 1) {
            $query .= implode(", ", $cols);
        } else {
            $query .= $cols[0];
        }
        return $query;
    }

    public function from($table): string
    {
        $query = "FROM " . $table;
        return $query;
    }

    public function where($condition, $eq, $clause): string
    {
        $query          = " WHERE ";
        $query          .= $condition;
        $query          .= $eq ? " = " : " LIKE ";
        $query          .= $clause;
        return $query;
    }

    public function groupby($col): string
    {
        $query = " GROUP BY " . $col;
        return $query;
    }

    public function orderby($col, $order): string
    {
        $query = " ORDER BY " . $col . " " . $order;
        return $query;
    }

    public function limit($limit): string
    {
        $query = " LIMIT " . $limit;
        return $query;
    }



    // $sqlrepertorie = new Search($conn);
    // $sqlrepertorie->select('idThese')->from('these');
    // $selection1 = $conn->prepare($sqlrepertorie)->execute();

    // $sqlenligne = new Search($conn);
    // $sqlenligne->select('these_accessible')->from('these')->where('these_accessible', true, '1');
    // $selection2 = $conn->prepare($sqlenligne)->execute();

    // $sqletablissement = new Search($conn);
    // $sqletablissement->select('idref')->from('etablissement');
    // $selection3 = $conn->prepare($sqletablissement)->execute();

    // $sqlsujet = new Search($conn);
    // $sqlsujet->select('idSujet')->from('sujet');
    // $selection4 = $conn->prepare($sqlsujet)->execute();

    // $sqlannee = new Search($conn);
    // $sqlannee->select('DATE_FORMAT(date_soutenance, "%Y") as "year", COUNT(*) as count')->from('soutenir')->groupby('DATE_FORMAT(date_soutenance, "%Y")');
    // $selection5 = $conn->prepare($sqlannee)->execute();


    public function loadData()
    {
        global $conn;

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


        // mise de tout dans un array
        $data = array($selection1->rowCount(), $selection2->rowCount(), $selection3->rowCount(), $selection4->rowCount(), $annees, $dernieres, $auteurs, $sujets);

        return $data;
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

        // Si l'id est dans le GET, celà signifie qu'on a cliqué sur une thèse en particulier, et on veut afficher les informations de cette thèse
        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $sqlthese = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, t.discipline, t.resume
            FROM etablissement e, these t NATURAL JOIN soutenir s 
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
        $sqlthese = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM etablissement e, these t NATURAL JOIN soutenir s 
        WHERE s.idEtablissement = e.idEtablissement AND MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE)
        ORDER BY score_titre DESC";

        $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_titre
        FROM these t
        NATURAL JOIN assister a
        NATURAL JOIN personne p
        WHERE MATCH (t.titre) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur de la these'
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
        // debug($option . ' - option');

        switch ($option) {
            case 'key':
                return $queries;
                break;
            case 'titre':
                return $queries;
                break;
            case 'auteur':
                $sqlthese = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, p.prenom, p.nom, a.role, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                JOIN assister a ON a.idThese = t.idThese
                NATURAL JOIN personne p
                WHERE s.idEtablissement = e.idEtablissement AND MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur de la these'
                ORDER BY score_personne DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre, MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_personne
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) 
                ORDER BY score_personne DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE MATCH (p.prenom, p.nom) AGAINST ('$key' IN NATURAL LANGUAGE MODE) AND a.role = 'auteur de la these'
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $AUTHORqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $AUTHORqueries;
                break;
            case 'sujet':
                $sqlthese = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese, su.libelle, MATCH (su.libelle) AGAINST ('$key' IN NATURAL LANGUAGE MODE) score_sujet
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
                $sqlthese = "SELECT @rank:=@rank+1 AS rank, s.date_soutenance, e.nom, t.titre, t.these_accessible, t.idThese
                FROM etablissement e, these t
                NATURAL JOIN soutenir s
                WHERE s.idEtablissement = e.idEtablissement AND s.date_soutenance >= '$key'
                ORDER BY s.date_soutenance DESC";

                $sqlauteur = "SELECT a.role, p.nom, p.prenom, t.idThese, t.titre
                FROM these t
                NATURAL JOIN assister a
                NATURAL JOIN personne p
                WHERE a.role = 'auteur de la these' AND t.idThese IN (SELECT idThese FROM soutenir WHERE date_soutenance >= '$key')
                ORDER BY t.idThese DESC";

                $sqlsujet = "SELECT s.libelle, r.idThese
                FROM reposer r
                NATURAL JOIN sujet s
                NATURAL JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY idThese DESC) so
                WHERE so.date_soutenance >= '$key'";

                $sqlannees = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(date_soutenance) as count
                FROM soutenir s
                WHERE s.date_soutenance >= '$key'
                GROUP BY DATE_FORMAT(date_soutenance, '%Y')";

                $SINCEqueries = array($sqlthese, $sqlauteur, $sqlsujet, $sqlannees);
                return $SINCEqueries;
                break;
            case 'de':
            default:
                // debug($option);
                return $queries;
        }

        // return $queries;
    }

    public function exe($query)
    {
        global $conn;
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

}
