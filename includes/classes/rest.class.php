<?php

include_once "db.class.php";

class Rest extends DB
{

    public function prepInsert($conn)
    {
        $sql = "INSERT INTO reposer (idSujet, idThese) VALUES (:idSujet, :idThese)";
        $insertion = $conn->prepare($sql);

        return $insertion;
    }

    public function insertIntoDB($insertion, $idSujet, $idThese)
    {
        $insertion->bindParam(':idSujet', $idSujet);
        $insertion->bindParam(':idThese', $idThese);

        return $insertion;
    }
}
