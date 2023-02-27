<?php 

namespace Controllers;

use Controllers\BaseController;
use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class ProjetController extends BaseController{

     public function afficherAdmin($page = 1){
          if (!Session::isAdmin()){
               $this->redirectionError();
          }
          $page = $page ? $page : 1;
          $nbParPage = 10;
          $offset = (($page - 1) * $nbParPage) - 1;


          $projets = Bdd::selection(["table" => "projet", "limit" => ($page == 1 ? "" : "$offset," ) . " $nbParPage", "order" => "id DESC" ]);

          $pageMax =  count($projets) !== 10 ?  true : false;
          $this->affichageAdmin("projet/liste.html.php" , [
               "projets" => $projets,
               "pageMax" => $pageMax,
               "page" => $page,
               "banniere" => "Projets.jpg"
          ]);

     }


     public function ajouter(){
          if (!Session::isAdmin()){
               $this->redirectionError();

           }
          if(!empty($_POST)){

               if(($form = Verificateur::verifyNewProject($_POST)) === true){

                    $this->redirection(lienAdmin("projet"));

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

                    $this->redirection(lienAdmin("projet"));
               
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
          $this->redirection(lienAdmin("projet"));
          
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
               $projets = Bdd::selection(["table" => "projet", "compare" => "date_debut", "where" => " <= '". date("Y-m-d",time()) . "'", "and" => true, "andCompare" => "date_fin", "andWhere" => ">= '". date("Y-m-d",time()) . "'",  "order" => "id DESC"]);
               // si il n'y a pas de projet actif
               if (!$projets){
                    Session::messages("secondary","Aucun projet en cours, voici tout les projets qui ont été mener a bien par notre association");
                    return $this->redirection(lien("projet","tout"));
               }
               

               return $this->affichage("projet/accueil.html.php",[
                    "projets" => $projets,
                    "css" => "projet_accueil",
                    "js" => "SliderProjets",
               ]);
          
     }
     
     public function tout($page = 1){

          // le code pour la pagination
          $page = $page ? $page : 1;
          $nbParPage = 24;
          $offset = (($page - 1) * $nbParPage) - 1;

          $projets = Bdd::selection(["table" => "projet", "limit" => ($page == 1 ? "" : "$offset," ) . " $nbParPage", "order" => "id DESC" ]);

          $pageMax =  count($projets) !== 24 ?  true : false;

          return $this->affichage("projet/tout.html.php",[
               "projets" => $projets,
               "pageMax" => $pageMax,
               "page" => $page,
               "css" => "projet_accueil"
          ]);
     }

     public function afficher(null|string $slug){

          $projet = Bdd::selection(["table" => "projet", "compare" => "slug", "where" => " = '$slug'" ]);
          return $this->affichage("projet/afficherProjet.html.php",[
               "projet" => $projet,
               "css" => "projet_accueil",
          ]);
     } 
}