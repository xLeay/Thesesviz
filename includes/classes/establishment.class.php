<?php

include_once "db.class.php";

class Establishment extends DB
{

    private $idref = "";
    private $nom = "";
    private $code_etab = "";

    public function getIdref()
    {
        return $this->idref;
    }

    public function setIdref($idref)
    {
        $this->idref = $idref;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getCode_etab()
    {
        return $this->code_etab;
    }

    public function setCode_etab($code_etab)
    {
        $this->code_etab = $code_etab;
    }

    public function prepInsert($conn)
    {
        $sql = "INSERT INTO etablissement (idref, nom, code_etab) VALUES (:idref, :nom, :code_etab)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion)
    {
        $insertion->bindParam(':idref', $this->idref);
        $insertion->bindParam(':nom', $this->nom);
        $insertion->bindParam(':code_etab', $this->code_etab);

        return $insertion;
    }


    public function prepSelect($conn)
    {
        $sql = "SELECT idref FROM etablissement WHERE idref = :idref";
        $selection = $conn->prepare($sql);

        return $selection;
    }

    public function selectFromDB($selection)
    {
        $selection->bindParam(':idref', $this->idref);

        return $selection;
    }

}
