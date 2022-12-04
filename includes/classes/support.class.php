<?php

include_once "db.class.php";

class Support extends DB
{

    private $date_soutenance = "";

    public function getDate_soutenance()
    {
        return $this->date_soutenance;
    }

    public function setDate_soutenance($date_soutenance)
    {
        $this->date_soutenance = $date_soutenance;
    }

    public function prepInsert($conn)
    {
        $sql = "INSERT INTO soutenir (idEtablissement, idThese, date_soutenance) VALUES (:idEtablissement, :idThese, :date_soutenance)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion, $idThese, $idEtablissement)
    {
        $insertion->bindParam(':idThese', $idThese);
        $insertion->bindParam(':idEtablissement', $idEtablissement);
        $insertion->bindParam(':date_soutenance', $this->date_soutenance);

        return $insertion;
    }


    public function prepSelect($conn)
    {
        $sql = "SELECT soutenue FROM these WHERE soutenue = '0'";
        $selection = $conn->prepare($sql);

        return $selection;
    }

    public function selectFromDB($selection)
    {
        return $selection;
    }

    public function getIdEtablissement($conn)
    {
        $sql = "SELECT e.idEtablissement
        FROM etablissement e NATURAL JOIN these t
        WHERE t.code_etab = e.code_etab
        ORDER BY t.idThese DESC LIMIT 1";
        $selection = $conn->prepare($sql);
        $selection->execute();
        $idEtablissement = $selection->fetch();

        return $idEtablissement;
    }

}
