<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class Concours {
    private int $id;
    private string $nom;
    private int|null $projet_id;
    private string $date_debut;
    private string $date_fin;

    public function getParticipants(){
        return Bdd::getEntitesRelies("concours","participant",$this->id);
    }

    public function nombreParticipants(){
        return count($this->getParticipants());
    }

    public function getProjet(){
        return Bdd::getEntiteRelie("concours","projet",$this->projet_id);
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

    /**
     * Get the value of projet_id
     */ 
    public function getProjet_id()
    {
        return $this->projet_id;
    }

    /**
     * Set the value of projet_id
     *
     * @return  self
     */ 
    public function setProjet_id($projet_id)
    {
        $this->projet_id = $projet_id;

        return $this;
    }

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
     * Get the value of date_debut
     */ 
    public function getDate_debut()
    {
        return $this->date_debut;
    }

    /**
     * Set the value of date_debut
     *
     * @return  self
     */ 
    public function setDate_debut($date_debut)
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    /**
     * Get the value of date_fin
     */ 
    public function getDate_fin()
    {
        return $this->date_fin;
    }

    /**
     * Set the value of date_fin
     *
     * @return  self
     */ 
    public function setDate_fin($date_fin)
    {
        $this->date_fin = $date_fin;

        return $this;
    }
}