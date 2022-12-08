<?php

namespace Modeles; 

use PDO;

class Bdd {

    static function connexion()
    {
        return new PDO("mysql:host=localhost;dbname=ocrochet","root","");
    }



    /**
     * attention a bien mettre les guillement simple sur les chaine de caractères ￣へ￣
     * Evoyer un array avec les cléfs suivantes :
     * @param"select" => colonne de la bdd ex: id | si tout select laisser vide,
     * @param"table" => nom de l'entité desirée ex: user,
     * @param(optionnel/obligatoire avec where/like)"compare" => colonne de comparaison pour where ("prenom")
     * @param(optionnel)"where" => symbole et valeur (" = 'Jean-Paul'") (WHERE prenom (compare) = 'Jean-Paul' (where))
     * @param(optionnel|incompatible avec where)"like" => possibilité de where avec like ("'%Jean%'")(WHERE prenom(compare) LIKE '%Jean%'(like))
     * @param(optionnel)"limit" => quantitée de résultat voulu (10) possibilité de mettre offset (10,2)
     * @param(optionnel)"order" => range les resultats reçus et limite les doublons si il y a ('prenom DESC(optionnel)')(ORDER BY prenom DESC)
     * @param(optionnel)"and" => rajouter une autre condition a la recherche. Rajouter and devant le paramettre pour en faire un paramettre and ("andCompare" => "nom")
     * pas de parametres supplémentaires. Faites des requêtes et recevez vos resultats comme un chef o(〃＾▽＾〃)o
     * @return array|bool
     */
    static function selection(array $para){

        extract($para);

        // pose les valeurs de toutes les variables rentrées et en met par default pour les autres
        if(!isset($select)){
            $select = "*";
        }
        // verifie si where a été rentré avec compare si oui, prépare le morceau de la requete avec where 
        if(isset($where) && isset($compare)){
            $where = "WHERE $compare $where";
        } else {
            $where = "";
        }
        // vérifie si like a été rentré en premier avant de verifier compare et mettre where à "" pour ne pas effacer where plus haut si il n'est pas entré en parametre 
        if (isset($like) ){
            if (isset($compare)){
                    $where = "WHERE $compare LIKE $like";
            } else {
                $where = "";
            }
        }
            
        if(isset($limit) && $limit){
            $limit = "LIMIT $limit";
        } else {
            $limit = "";
        }

        if(isset($order) && $order){
            $order = "ORDER BY $order";
        } else {
            $order = "";
        }

        if(isset($and)&&isset($andCompare)){
            if(isset($andWhere)){
                $and = "AND $andCompare $andWhere";
            } 
            if(isset($andLike)){
                $and = "AND $andCompare $andLike";
            } else {
                $and = "";
            }
        } else {
            $and = "";
        }
        
        // pose toutes les variables dans l'ordre. Les variables qui n'ont pas été entrée en parametre contiennent un string vide

        $textRequete = "SELECT $select FROM $table $where $and $order $limit";
        if($requete = self::connexion()->query($textRequete)){
            //création du string nécessaire à la recupération des données sous forme d'entité
            //exemple où $table = user : $classFetch =  "Modeles\Entities\User";
            $classFetch = "Modeles\Entities\\" . ucFirst($table);

            return $requete->fetchAll(PDO::FETCH_CLASS, $classFetch);
        }
        // si la requete n'a pas fonctionner retourne false

        return false;
        
    }
    
    
    /**
     * Besoin d'un id
     * 
     * Envoyer un array avec les cléfs suivantes :
     * "select" => colonne de la bdd ex: id | si tout select laisser vide,
     * "table" => nom de l'entité desirée ex: user.
     * "limit" => nombre de resultat max voulu
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


    static public function recherche(array $array){
        extract($array);
        if (isset($table) && isset($where)){
            
            $text = "SELECT * FROM $table WHERE $where = $search";
            if(isset($order)){
                $text .= " ORDER BY $order";
            }
            if(isset($limit)){
                $text .= " LIMIT $limit";
            }
            if($requete = self::connexion()->query($text)){
                return $requete->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

    static public function getTablesNames($table){
        return self::connexion()->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$table'")->fetchAll(PDO::FETCH_ASSOC);
    }
}