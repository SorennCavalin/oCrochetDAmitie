<?php

namespace Modeles; 

use PDO;

class Bdd {

    static function connexion()
    {
        return new PDO("mysql:host=localhost;dbname=ocrochet","root","");
    }



    /**
     * Evoyer un array avec les cléfs suivantes :
     * "select" => colonne de la bdd ex: id | si tout select laisser vide,
     * "table" => nom de l'entité desirée ex: user,
     * (optionnel)"where" => toute la condition ex: WHERE id = 2.
     */
    static function selection(array $para){

        extract($para);

        if(!isset($select)){
            $select = "*";
        }

        $textRequete = "SELECT $select FROM $table ";
        if(isset($where)){
            $textRequete .= " $where";
        }

        if($requete = self::connexion()->query($textRequete)){
            //création du string nécessaire à la recupération des données sous forme d'entité
            //exemple où $table = user : $classFetch =  "Modeles\Entities\User";
            $classFetch = "Modeles\Entities\\" . ucFirst($table);

            return $requete->fetchAll(PDO::FETCH_CLASS, $classFetch);
        }

        return null;
        
    }
    
    
    /**
     * Besoin d'un id
     * 
     * Envoyer un array avec les cléfs suivantes :
     * "select" => colonne de la bdd ex: id | si tout select laisser vide,
     * "table" => nom de l'entité desirée ex: user.
     */

    static function selectionId(array $para, int $id){

        extract($para);
        
        if(!isset($select)){
            $select = "*";
        }
        
        $requete = self::connexion()->query("SELECT $select FROM $table WHERE id = $id");
        
        if($requete) {
            //création du string nécessaire à la recupération des données sous forme d'entité
            //exemple où $table = user : $classFetch =  "Modeles\Entities\User";
            $classFetch = "Modeles\Entities\\" . ucFirst($table);
        
            $requete->setFetchMode(PDO::FETCH_CLASS, $classFetch);

            return $requete->fetch();
        }
        
        return null;
    }




     /**
     * Evoyer un array avec les cléfs suivantes :
     * "select" => colonne de la bdd avec alias ex: c.id,
     * "table" => au moins 2 tables de la bdd avec alias,
     * "where" => $this->id = alias2.this_is
     */
    static function getEntitesRelies(array $para){
        extract($para);
        
        $textRequete = "SELECT $select FROM $table WHERE $where";
        $requete = self::connexion()->query($textRequete);

        if($requete){
            //création du string nécessaire à la recupération des données sous forme d'entité
            //exemple où $table = user : $classFetch =  "Modeles\Entities\User";
            $classFetch = "Modeles\Entities\\" . ucFirst(explode(" ",$table)[0]);

            return $requete->fetchAll(PDO::FETCH_CLASS, $classFetch);
        }

        return null;
        
    }

    /**
     * Evoyer un array avec les cléfs suivantes :
     * "select" => colonne de la bdd avec alias ex: c.id,
     * "table" => au moins 2 tables de la bdd avec alias,
     * "where" => alias de select.id = $this->table_id
     */
    static function getEntiteRelie(array $para){
        extract($para);
        
        $textRequete = "SELECT $select FROM $table WHERE $where";

        $requete = self::connexion()->query($textRequete);

        if($requete){
            //création du string nécessaire à la recupération des données sous forme d'entité
            //exemple où $table = user : $classFetch =  "Modeles\Entities\User";
            $classFetch = "Modeles\Entities\\" . ucFirst(explode(" ",$table)[0]);

            $requete->setFetchMode(PDO::FETCH_CLASS, $classFetch);

            return $requete->fetch();
        }

        return null;
        
    }
    /**
     * dans le tableau des valeurs doit ressembler a ça :
     * ["nom" => $nom, "prenom" => $prenom ...]
     *      
    */
    static function insertBdd($table, array $valeurs) {
        $textRequete = "INSERT INTO $table (";
        $compte = 0;
        $nbv = count($valeurs);
        foreach ($valeurs as $indice => $valeur) {
            $compte += 1;
            if($compte !== $nbv){
                $textRequete.= "$indice,";
            } else {
                $textRequete .= "$indice";
                $compte = 0;
            }
        }
        $textRequete .= ") VALUES (";
        foreach ($valeurs as $valeur) {
            $compte += 1;
            if($compte !== $nbv){
                $textRequete .= "'$valeur',";
            } else {
                $textRequete .= "'$valeur'";
            }
        }
        $textRequete .= ");";


        if (self::connexion()->query($textRequete)){
            return true ;
        } else {
            return false;
        }
       ;
    }

    static function autentication($mdp, $email){
        if(!isset($email) && empty($email)){
            return false;
        }
        $query = self::connexion()->query("SELECT * FROM user WHERE email = '$email'");

        $query->setFetchMode(PDO::FETCH_CLASS,"Modeles\Entities\User");

        if(!$user = $query->fetch()){
            Session::messages("danger", "Aucun utilisateur trouvé");
            return false;
        }


        if (password_verify($mdp,$user->getMdp())){
            Session::connexion($user);
            Session::messages("success", "bonjour " . $user->getPrenom() . " " . $user->getNom() . ", vous êtes bien connecté");
            return true;
        }
        Session::messages("danger","erreur de la connexion");
        return false;
    }

    /**
     * Besoin de la table en question et de l'id de la ligne a supprimer
     */
    static function drop($table, $id){
        $textRequete = "DELETE FROM $table WHERE id = $id";
        if(self::connexion()->query($textRequete)){
            Session::messages("success", "$table n°$id à bien été supprimé" );
            return true;
        } 
        Session::messages("danger","$table n°$id n'a pas pu etre supprimer");
        return false;
    }
    /**
     * Besoin de la table en question et de l'id de la ligne a supprimer
     */
    static function dropRelie($table, $id, $relie){
        $requete1 = "DELETE FROM $relie WHERE " .$table . "_id = $id";
        if(self::connexion()->query($requete1)){
            $textRequete = "DELETE FROM $table WHERE id = $id";
            if(self::connexion()->query($textRequete)){
                Session::messages("success", "$table n°$id à bien été supprimé" );
                return true;
            } 
            Session::messages("danger","$table n°$id n'a pas pu etre supprimer");
            return false;
        }
        Session::messages("danger","Les détails du $table n°$id n'ont pas pu être supprimés ce qui a mis fin à l'opération");
        return false;
    }
   /**
     * dans le tableau des valeurs doit ressembler a ça :
     * ["nom" => $nom, "prenom" => $prenom ...]
     *      
    */
    static function update(string $table, array $valeurs,int $id) {
        $textRequete = "UPDATE $table SET ";
        $compte = 0;
        $nbv = count($valeurs);
        foreach ($valeurs as $indice => $valeur) {
            $compte += 1;
            if($compte !== $nbv){
                $textRequete.= "$indice = '$valeur',";
            } else {
                $textRequete .= "$indice = '$valeur'";
                $compte = 0;
            }
        }
        $textRequete .= "WHERE id = $id";
        if (self::connexion()->query($textRequete)){
            return true ;
        } else {
            return false;
        }
       ;
    }
}