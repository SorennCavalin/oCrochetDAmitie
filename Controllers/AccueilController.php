<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;

class AccueilController extends BaseController {

    public function afficher()
    {
        $this->affichage("accueil.html.php", [
            "css" => "accueil"
        ]);
    }
    public function afficherAdmin()
    {
        
        $this->affichageAdmin("stats.html.php");
    }

    public function recherche(){
        header('Access-Control-Allow-Origin: *');
        if($_POST){

            extract($_POST);

            if(isset($table)){
                $check["table"] = true;
            } else {
                $check["table"] = false;
            }
            if(isset($where)){
                $check["where"] = true;
            } else {
                $check["where"] = false;
            }

            if(isset($limit)){
                $check["limit"] = true;
            } else {
                $check["limit"] = false;
            }

            if(isset($search)){
                $check["search"] = true;
            } else {
                $check["search"] = false;
            }

            if($check["search"] === false || $check["table"] === false || $check["where"] === false){
                Session::messages('danger', "Je ne peux pas recherche sans savoir quoi ni avec quoi");
                return $this->affichageAdmin("resultat.html.php",[],false);
            }

            if($where === "id" ){
                $recherche = Bdd::selectionId(["table"=>$table], (int) $search);
                return $this->affichageAdmin("$table/fiche.html.php",[
                    "$table" => $recherche
                ],false);
            }

            if ($where === "projet_id" ){
                if (is_numeric($where)){
                    $recherche = Bdd::selectionId(["table"=>$table], (int) $search);
                } else {
                    $recherche = Bdd::selection(['table' => $table,"where" => "WHERE nom LIKE '%$search%'"]);
                }
                return $this->affichageAdmin("$table/fiche.html.php",[
                    "$table" => $recherche
                ],false);
            }

            if ($where === "nom")

            // if (strpos($where,"date")){
            //     $where = date("Y-m-d",strtotime($where));
            // }


            
            // if ($check["limit"] === true ){
            //     $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%' LIMIT $limit"]);
            // }
            // if($check["limit"] === false){
            //     $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%'"]);
            // }

            

            return $this->affichageAdmin("$table/liste.html.php",[
               "$table" . "s" => $recherche
            ]);


        }
    }
}