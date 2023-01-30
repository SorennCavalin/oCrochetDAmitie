<?php


namespace Modeles\Entities;

use Modeles\Bdd;

class Participant{
    
    private int $id;
    private int $user_id;
    private int $concours_id;
    
    public function getConcours(){
        return Bdd::getEntiteRelie("participant","concours",$this->concours_id);
    }
    public function getUser() {
        return Bdd::getEntiteRelie("participant","user",$this->user_id);
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