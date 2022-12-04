<?php

include_once "db.class.php";

class Subject extends DB
{

    private $libelle = "";

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function prepInsert($conn)
    {
        $sql = "INSERT INTO sujet (libelle) VALUES (:libelle)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion)
    {
        $insertion->bindParam(':libelle', $this->libelle);

        return $insertion;
    }


    public function prepSelect($conn)
    {
        $sql = "SELECT libelle FROM sujet WHERE libelle = :libelle";
        $selection = $conn->prepare($sql);

        return $selection;
    }

    public function selectFromDB($selection)
    {
        $selection->bindParam(':libelle', $this->libelle);

        return $selection;
    }
}
