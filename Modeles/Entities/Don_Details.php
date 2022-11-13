<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class Don_Details{
    private int $id;
    private $nom;
    private $don_id;
    private $qte;

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of don_id
     */ 
    public function getDon_id()
    {
        return $this->don_id;
    }

    /**
     * Set the value of don_id
     *
     * @return  self
     */ 
    public function setDon_id($don_id)
    {
        $this->don_id = $don_id;

        return $this;
    }

    /**
     * Get the value of qte
     */ 
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set the value of qte
     *
     * @return  self
     */ 
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    public function getDon(){
        return Bdd::getEntiteRelie(["select" => "d.*","table" => "don d , don_details dd", "where" => "d.id = " . $this->don_id]);
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}