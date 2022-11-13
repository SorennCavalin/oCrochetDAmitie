<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class Participant{
    private int $id;
    private $user_id;
    private $concours_id;

    public function getConcours(){
        return Bdd::getEntiteRelie(["select" => "c.*","table" => "concours c , participant p", "where" => "c.id = ". $this->concours_id]);
    }
    public function getUser() {
        return Bdd::getEntiteRelie(["select" => "u.*","table" => "user u , participant p", "where" => "u.id = " . $this->user_id]);
    }



    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
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