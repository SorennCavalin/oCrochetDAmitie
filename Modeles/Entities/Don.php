<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class Don  {
    private int $id;
    private string $type;
    private string $organisme ;
    private string|int $donataire ;
    private string $date ;
    private int $concours_id;

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of organisme
     */ 
    public function getOrganisme()
    {
        return $this->organisme;
    }

    /**
     * Set the value of organisme
     *
     * @return  self
     */ 
    public function setOrganisme($organisme)
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * Get the value of donataire
     */ 
    public function getDonataire()
    {
        return $this->donataire;
    }

    /**
     * Set the value of donataire
     *
     * @return  self
     */ 
    public function setDonataire($donataire)
    {
        $this->donataire = $donataire;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
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

    // public function getConcours(){
    //     return Bdd::getEntiteRelie("don","concours",$this->id);
    // }

    public function getDetails(){
        return Bdd::getEntitesRelies("don","don_details",$this->id);
    }

    public function getUser(){
        if (is_int($donataire)){
            return Bdd::getEntiteRelie("don","user",$this->donataire);
        }
        return [];
    }

    public function getTaille(){
        $qte = 0;
        foreach($this->getDetails() as $detail){
            $qte += $detail->getQte();
        }
        return $qte;
    }

    /**
     * Get the value of concours_id
     */ 
    public function getConcours_id()
    {
        return $this->concours_id;
    }

    /**
     * Set the value of concours_id
     *
     * @return  self
     */ 
    public function setConcours_id($concours_id)
    {
        $this->concours_id = $concours_id;

        return $this;
    }
}