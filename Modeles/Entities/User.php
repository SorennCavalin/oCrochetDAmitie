<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class User  {
    private $id;
    private string $prenom;
    private string $nom;
    private string $email;
    private string $mdp;
    private string|null $telephone;
    private string $region;
    private int $departement;
    private string|null $adresse;
    private string $date_inscription;
    private string $role;


    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

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
     * Get the value of region
     */ 
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set the value of region
     *
     * @return  self
     */ 
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get the value of departement
     */ 
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set the value of departement
     *
     * @return  self
     */ 
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get the value of date_inscription
     */ 
    public function getDate_inscription()
    {
        return $this->date_inscription;
    }

    /**
     * Set the value of date_inscription
     *
     * @return  self
     */ 
    public function setDate_inscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {   
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }


    public function getParticipations(){
        return Bdd::getEntitesRelies("user","participant",$this->id);
    }

    public function getDons(){
        return Bdd::getEntitesRelies("user","don",$this->id,"donataire");
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of telephone
     */ 
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */ 
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

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

    /**
     * Get the value of adresse
     */ 
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set the value of adresse
     *
     * @return  self
     */ 
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get the value of mdp
     */ 
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set the value of mdp
     *
     * @return  self
     */ 
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getAll($all = null){
        $prop = array("nom" => $this->nom,"prenom" => $this->prenom, "email" => $this->email,"adresse" => $this->adresse ?? null,"telephone" => $this->telephone ?? null,"mdp" => $this->mdp ?? null,"region" => $this->region,"departement" => $this->departement,"role" => $this->role);
        if(!$all === null){
            $prop[] =["id" =>  $this->id];
            $prop[] =["date_inscription" =>  $this->date_inscription];
        }
        return $prop;
    }
}