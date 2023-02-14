<?php 

namespace Controllers;

use Controllers\BaseController;
use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class ProjetController extends BaseController{

     public function afficher(){
          if (!Session::isAdmin()){
               $this->redirectionError();
           }
          $page = empty($_GET["id"]) ? 1 : $_GET["id"];
          $nbParPage = 10;
          $offset = (($page - 1) * $nbParPage) - 1;

          $projets = Bdd::selection(["table" => "projet", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]) ;

          $pageMax =  count($projets) !== 10 ?  true : false;
               
          $this->affichageAdmin("projet/liste.html.php" , [
               "projets" => $projets,
               "pageMax" => $pageMax,
               "page" => $page
          ]);

     }


     public function ajouter(){
          if (!Session::isAdmin()){
               $this->redirectionError();

           }
          if(!empty($_POST)){

               if(($form = Verificateur::verifyNewProject($_POST)) === true){

                    $this->redirection(lien("projet"));

               } else{
                    extract($form);
                    
                    $this->affichageAdmin("projet/form.html.php",
                    [
                         "nom" => $nom ?? "",
                         "date_debut" => $date_debut ?? "", 
                         "date_fin" => $date_fin ?? "",
                         "page" => $page ?? ""
                    ]);
               }
          } else {
               $this->affichageAdmin("projet/form.html.php");
          }
     }

     public function modifier($id){

          if (!Session::isAdmin()){
               $this->redirectionError();
          }
          
          $projet = Bdd::selectionId("projet",$id);
          if(!empty($_POST)){

               if(($form = Verificateur::verifyModifProject($_POST,$projet)) !== 1){

                    $this->redirection(lien("projet"));
               
               }else {
                    if(is_array($form)){
                         extract($form);
                    }
                    return $this->affichageAdmin("projet/form.html.php",[
                         "nom" => $nom ?? "",
                         "date_debut" => $date_debut ?? "",
                         "date_fin" => $date_fin ?? "",
                         "page" => $page ?? "",
                         "mode" => "modify"
                    ]);
               }
          } else {
               return $this->affichageAdmin("projet/form.html.php",[
                    "nom" => $projet->getNom(),
                    "date_debut" => $projet->getdate_debut(),
                    "date_fin" => $projet->getdate_fin(),
                    "page" => $projet->getPage(),
                    "mode" => "modify"
               ]);
          }
          
     }

     public function supprimer($id) {

          if (!Session::isAdmin()){
              $this->redirectionError();
  
          }
  
          
          Bdd::dropRelie("projet",$id,"concours");
          $this->redirection(lien("projet"));
          
     }
  

     public function detail($id){
          if (!Session::isAdmin()){
               $this->redirectionError();

          }

          $projet = Bdd::selectionId("projet",$id);
          $this->affichageAdmin("projet/fiche.html.php",[
               "projet" => $projet
          ]);
     }

     public function accueil(){
          if(!$projets = Bdd::selection(["table" => "projet", "where" => "WHERE date_fin >= '". date("Y-m-d",time()) ."' ORDER BY date_fin DESC"])){
               $pause = true;
               $projets = Bdd::selection(["table" => "projet", "where" => "ORDER BY date_fin DESC LIMIT 3"]);
          }
          

          $this->affichage("projet/accueil.html.php",[
               "projets" => $projets,
               "css" => "projet",
               "js" => "projets",
               "pause" => isset($pause) ? $pause : false
          ]);   
     }
}