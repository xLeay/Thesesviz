<?php

include_once "db.class.php";

class Person extends DB
{

    private $nom = "";
    private $prenom = "";
    private $idref = "";

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getIdref()
    {
        return $this->idref;
    }

    public function setIdref($idref)
    {
        $this->idref = $idref;
    }


    public function prepInsert($conn)
    {
        $sql = "INSERT INTO personne (nom, prenom, idref) VALUES (:nom, :prenom, :idref)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion)
    {
        $insertion->bindParam(':nom', $this->nom);
        $insertion->bindParam(':prenom', $this->prenom);
        $insertion->bindParam(':idref', $this->idref);

        return $insertion;
    }


    public function prepSelect($conn)
    {
        $sql = "SELECT * FROM personne WHERE nom = :nom AND prenom = :prenom";
        $selection = $conn->prepare($sql);

        return $selection;
    }

    public function selectFromDB($selection)
    {
        $selection->bindParam(':nom', $this->nom);
        $selection->bindParam(':prenom', $this->prenom);

        return $selection;
    }

}
