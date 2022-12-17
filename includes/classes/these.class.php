<?php

include_once "db.class.php";

class These extends DB
{

    private $accessible = "";
    private $embargo = "";
    private $nnt = "";
    private $oai_set_specs = "";
    private $resume = "";
    private $soutenue = "";
    private $sur_travaux = "";
    private $titre = "";
    private $discipline = "";
    private $code_etab = "";


    // la cnx est dans la classe parente, on peut donc l'utiliser directement
    public function getTheses()
    {
        $sql = "SELECT * FROM these";
        $stmt = $this->cnx()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public function getAccessible()
    {
        return $this->accessible;
    }

    public function setAccessible($accessible)
    {
        $this->accessible = $accessible;
    }

    public function getEmbargo()
    {
        return $this->embargo;
    }

    public function setEmbargo($embargo)
    {
        $this->embargo = $embargo;
    }

    public function getNnt()
    {
        return $this->nnt;
    }

    public function setNnt($nnt)
    {
        $this->nnt = $nnt;
    }

    public function getOaiSetSpecs()
    {
        return $this->oai_set_specs;
    }

    public function setOaiSetSpecs($oai_set_specs)
    {
        $this->oai_set_specs = $oai_set_specs;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function setResume($resume)
    {
        $this->resume = $resume;
    }

    public function getSoutenue()
    {
        return $this->soutenue;
    }

    public function setSoutenue($soutenue)
    {
        $this->soutenue = $soutenue;
    }

    public function getSurTravaux()
    {
        return $this->sur_travaux;
    }

    public function setSurTravaux($sur_travaux)
    {
        $this->sur_travaux = $sur_travaux;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getDiscipline()
    {
        return $this->discipline;
    }

    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;
    }

    public function getCodeEtab()
    {
        return $this->code_etab;
    }

    public function setCodeEtab($code_etab)
    {
        $this->code_etab = $code_etab;
    }

    public function insertIntoDB($conn)
    {
        $sql = "INSERT INTO these (these_accessible, embargo, nnt, oai_set_specs, resume, soutenue, sur_travaux, titre, discipline, code_etab) VALUES (:these_accessible, :embargo, :nnt, :oai_set_specs, :resume, :soutenue, :sur_travaux, :titre, :discipline, :code_etab)";
        $insertion = $conn->prepare($sql);

        $insertion->bindParam(':these_accessible', $this->accessible);
        $insertion->bindParam(':embargo', $this->embargo);
        $insertion->bindParam(':nnt', $this->nnt);
        $insertion->bindParam(':oai_set_specs', $this->oai_set_specs);
        $insertion->bindParam(':resume', $this->resume);
        $insertion->bindParam(':soutenue', $this->soutenue);
        $insertion->bindParam(':sur_travaux', $this->sur_travaux);
        $insertion->bindParam(':titre', $this->titre);
        $insertion->bindParam(':discipline', $this->discipline);
        $insertion->bindParam(':code_etab', $this->code_etab);

        return $insertion;
    }

}
