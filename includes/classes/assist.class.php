<?php

include_once "db.class.php";

class Assist extends DB
{

    private $role = "";

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function prepInsert($conn)
    {
        $sql = "INSERT INTO assister (idThese, idPersonne, role) VALUES (:idThese, :idPersonne, :role)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion, $idThese, $idPersonne)
    {
        $insertion->bindParam(':idThese', $idThese);
        $insertion->bindParam(':idPersonne', $idPersonne);
        $insertion->bindParam(':role', $this->role);

        return $insertion;
    }
}
